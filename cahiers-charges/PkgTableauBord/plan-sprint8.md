# Plan d'Implémentation — Sprint 8 : Indicateur 08 (Progression dans le Référentiel National — $\text{ICP}$)

## 🎯 1. Description du Sprint & Objectifs Métier

Ce huitième sprint implémente la tranche verticale complète de l'**Indicateur 08 — Indicateur de Couverture Pédagogique du Référentiel National ($\text{ICP}$)** sur le module `PkgTableauBord` :
- **BDD** : Ajout d'index composites de performance sur la table existante `realisation_micro_competences` (`apprenant_id, etat_realisation_micro_competence_id` et `micro_competence_id, etat_realisation_micro_competence_id`).
- **Backend / Algorithmique** : Implémentation du calcul de l'$\text{ICP}$ individuel (Apprenant), méso (Promotion/Groupe) et macro (Responsable de formation), et détection automatique des **impasses didactiques** (micro-compétences acquises par moins de 50% de la classe).
- **Services Métier** : Trait `DashboardIndicateur08Trait` dans `TableauBordService` calculant :
  - **Pour l'Apprenant** : Taux individuel de micro-compétences validées sur le total prescrit ($85\%$, 17 / 20 validées).
  - **Pour le Formateur** : Taux $\text{ICP}$ de la promotion ($84\%$) et diagnostic des micro-compétences à risque / impasses ($< 50\%$).
  - **Pour le Responsable** : $\text{ICP}$ moyen d'établissement ($91.2\%$), conformité réglementaire nationale et colonne dédiée dans la matrice comparative inter-promotions.
- **Composants Blade V2** : Nouveau composant `kpi-couverture-pedagogique.blade.php`, composant de diagnostic `impasses-pedagogiques-panel.blade.php`, intégration dans `apprenant/index.blade.php`, `formateur/index.blade.php` et `responsable/index.blade.php`.
- **Simulation & Recette** : Commande artisan `dashboard:simuler-couverture-pedagogique` configurant la cohorte étalon à $\sim 85\%$ et 2 compétences en impasse didactique.

### Formules Mathématiques Métier :
$$\text{ICP}_{\text{apprenant}}(i) = \frac{C_{\text{validées}}(i)}{C_{\text{référentiel}}} \times 100$$
$$\text{ICP}_{\text{groupe}} = \frac{\sum_{i=1}^{N} C_{\text{validées}}(i)}{N \times C_{\text{référentiel}}} \times 100$$
$$\text{ICP}_{\text{macro}} = \frac{1}{\sum N_g \times C_{\text{réf}}(g)} \sum_{g} \left( \sum_{i=1}^{N_g} C_{\text{validées}}(i, g) \right) \times 100$$

---

## 🚦 2. Seuils Didactiques & Statuts Visuels

| Zone | Seuil $\text{ICP}$ | Diagnostic Docimologique | Statut & Badge UI |
| :--- | :---: | :--- | :--- |
| 🟢 **Excellence Complète** | $\ge 90\,\%$ | **Couverture exhaustive** du référentiel national. Aucune impasse technique. | `bg-emerald-50 text-emerald-700` *"Couverture optimale (&ge; 90%)"* |
| 🟡 **Vigilance** | $75\,\% \le \text{ICP} < 90\,\%$ | Progression active, quelques micro-compétences secondaires en attente. | `bg-indigo-50 text-indigo-700` *"Progression active"* |
| 🔴 **Alerte Impasse** | $< 75\,\%$ | **Parcours tronqué** : contournement de compétences techniques clés par manque de temps. | `bg-rose-50 text-rose-700` *"Alerte Impasse"* |

### Seuil de Détection d'Impasse Didactique Promotion :
- $\text{Taux Acquisition Micro-Compétence} < 50\,\%$ : **Alerte Impasse Didactique** (*"Intégrer en atelier de consolidation sur le prochain sprint"*).

---

## 📅 3. Déroulement des Étapes du Sprint 8

### Étape 1 : Optimisation Schéma BDD (`app-migration`)
- Migration : `modules/PkgApprentissage/Database/Migrations/2026_10_13_210000_add_performance_indexes_for_icp_to_realisation_micro_competences.php`
- Action Développeur : Validation puis exécution manuelle `php artisan migrate`.

### Étape 2 : Couche Service & Calculs Métier (`pkg-tableau-bord`, `app-service`)
- Création du trait : `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur08Trait.php`
  - `getIndicateurCouvertureApprenant(?RealisationProjet $realisationProjet, ?Apprenant $apprenant): array`
  - `getIndicateurCouvertureFormateur(?AffectationProjet $affectationProjet, ?Groupe $groupe): array`
  - `getIndicateurCouvertureResponsable(array $groupeIds = [], ?int $projetId = null): array`
- Intégration dans `TableauBordService.php`.

### Étape 3 : Contrôleurs & Vues Blade V2 (`app-controller`, `app-blade`)
- Injection de `$kpiCouverture` dans `TableauBordApprenantController`, `TableauBordFormateurController`, `TableauBordResponsableController`.
- Composant carte KPI : `resources/views/components/kpi-couverture-pedagogique.blade.php`.
- Composant panel des impasses : `resources/views/components/impasses-pedagogiques-panel.blade.php`.
- Intégration dans les 3 vues sous `layouts.admin-v2`.

### Étape 4 : Simulation & Recette Live
- Commande artisan : `php artisan dashboard:simuler-couverture-pedagogique`.
- Recette live sur les 3 profils.
- Mise à jour `.deploy/update_14.md` et `.agent/skills/pkg-tableau-bord/SKILL.md`.
