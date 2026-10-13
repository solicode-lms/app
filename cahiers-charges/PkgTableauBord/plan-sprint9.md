# Plan d'Implémentation — Sprint 9 : Indicateur 09 (Règle Docimologique 65/35 & Bordereaux d'Examen — $\tau_{\text{conformité}}$)

## 🎯 1. Description du Sprint & Objectifs Métier

Ce neuvième sprint implémente la tranche verticale complète de l'**Indicateur 09 — Taux de Conformité de Conversion Institutionnelle 65/35 ($\tau_{\text{conformité}}$)** sur le module `PkgTableauBord` :
- **BDD** : 
  - Ajout des colonnes docimologiques officielles sur `realisation_modules` : `note_cc_officielle` (`decimal(4,2) NULL`), `note_efm_officielle` (`decimal(4,2) NULL`) et `statut_docimologique` (`string DEFAULT 'en_cours'`).
  - Création de la table `bordereaux_officiels` (avec champ `reference` unique obligatoire, `module_id`, `groupe_id`, `annee_formation_id`, `nb_apprenants`, `taux_conformite_calcul`, `statut`, `date_scellement`, `hash_signature`, `genere_par_user_id`, `timestamps()`).
- **Backend / Algorithmique** : Implémentation du moteur docimologique réglementaire 65/35 :
  - **Note Contrôle Continu (CC)** sur 20 : Moyenne arithmétique des notes de Live Coding individuel sur les prototypes ($N2$).
  - **Note Examen de Fin de Module (EFM)** sur 40 : Combinaison pondérée 65% Prototype direct ($N2$) + 35% Projet intégrateur libre assisté par IA ($N3$), ramenée sur 40 :
    $$\text{Note EFM}(i) = 2 \times \left( 0{,}65 \times \text{Note CC}(i) + 0{,}35 \times \text{Note}(N3, i) \right) \in [0, 40]$$
  - **Taux de Conformité des Bordereaux ($\tau_{\text{conformité}}$)** : Proportion de bordereaux certifiés sans aucune omission de note ni discordance d'arrondi ($100\%$ des apprenants évalués).
- **Services Métier** : Trait `DashboardIndicateur09Trait` dans `TableauBordService` calculant :
  - **Pour l'Apprenant** : Note CC provisoire (sur 20), Note EFM estimée (sur 40), transparence des coefficients (65% Prototype vs 35% Projet).
  - **Pour le Formateur** : Tableau docimologique certifié avec statut de complétude des notes ($20 / 20$ évalués), prévisualisation du bordereau officiel et bouton de scellement réglementaire.
  - **Pour le Responsable** : KPI Macro *"Conformité des bordereaux d'examen : 100%"*, historique des bordereaux scellés et exports certifiés (Excel / PDF signé).
- **Composants Blade V2** : Nouveau composant `kpi-docimologie.blade.php`, composant de scellement `bordereau-officiel-panel.blade.php`, intégration dans `apprenant/index.blade.php`, `formateur/index.blade.php` et `responsable/index.blade.php`.
- **Simulation & Recette** : Commande artisan `dashboard:simuler-docimologie` calibrant la promotion étalon à 100% de complétude et scellant le bordereau officiel avec hash de signature.

---

## 🚦 2. Seuils Docimologiques & Statuts Visuels

| Zone | Valeur $\tau_{\text{conformité}}$ | Diagnostic Docimologique | Statut & Badge UI |
| :--- | :---: | :--- | :--- |
| 🟢 **Conformité Absolue** | $100\,\%$ | $100\%$ des apprenants évalués, zéro omission, zéro divergence. Bordereau prêt pour scellement institutionnel. | `bg-emerald-50 text-emerald-700` *"Bordereau Conforme (100%)"* |
| 🟡 **Évaluation en Cours** | $80\,\% \le \tau < 100\,\%$ | Évaluations des soutenances en phase finale, quelques notes de prototypes à consigner. | `bg-indigo-50 text-indigo-700` *"En cours de finalisation"* |
| 🔴 **Divergence / Incomplet** | $< 80\,\%$ | Retard d'évaluation ou notes manquantes empêchant le scellement officiel. | `bg-rose-50 text-rose-700` *"Verrouillé — Notes incomplètes"* |

---

## 📅 3. Déroulement des Étapes du Sprint 9

### Étape 1 : Schéma BDD & Migrations (`app-migration`)
- Migration 1 : `modules/PkgApprentissage/Database/Migrations/2026_10_13_220000_add_docimologie_columns_to_realisation_modules_table.php`
- Migration 2 : `modules/PkgTableauBord/Database/Migrations/2026_10_13_220100_create_bordereaux_officiels_table.php`
- Modèle : `Modules\PkgTableauBord\Models\BordereauOfficiel`
- Action Développeur : Validation puis exécution manuelle `php artisan migrate`.

### Étape 2 : Couche Service & Calculs Docimologiques (`pkg-tableau-bord`, `app-service`)
- Création du trait : `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur09Trait.php`
  - `getIndicateurDocimologieApprenant(?RealisationProjet $realisationProjet, ?Apprenant $apprenant): array`
  - `getIndicateurDocimologieFormateur(?AffectationProjet $affectationProjet, ?Groupe $groupe): array`
  - `getIndicateurDocimologieResponsable(array $groupeIds = [], ?int $projetId = null): array`
- Intégration dans `TableauBordService.php`.

### Étape 3 : Contrôleurs & Vues Blade V2 (`app-controller`, `app-blade`)
- Injection de `$kpiDocimologie` dans `TableauBordApprenantController`, `TableauBordFormateurController`, `TableauBordResponsableController`.
- Composant carte KPI : `resources/views/components/kpi-docimologie.blade.php`.
- Composant grille de scellement : `resources/views/components/bordereau-officiel-panel.blade.php`.
- Intégration dans les 3 vues sous `layouts.admin-v2`.

### Étape 4 : Simulation & Recette Live
- Commande artisan : `php artisan dashboard:simuler-docimologie`.
- Recette live sur les 3 profils.
- Mise à jour `.deploy/update_14.md` et `.agent/skills/pkg-tableau-bord/SKILL.md`.
