# Plan d'Implémentation — Sprint 7 : Indicateur 07 (Cohérence Docimologique & Intégrité face à l'IA — Pearson $r$)

## 🎯 1. Description du Sprint & Objectifs Métier

Ce septième sprint implémente la tranche verticale complète de l'**Indicateur 07 — Écart d'Authenticité Évaluative et Corrélation d'Intégrité face à l'IA ($r$)** sur le module `PkgTableauBord` :
- **BDD** : Ajout d'index composites de performance sur les tables de notation existantes (`realisation_ua_prototypes` et `realisation_ua_projets`).
- **Backend / Algorithmique** : Implémentation statistique complète du coefficient de corrélation linéaire de Pearson ($r \in [-1, +1]$) et du calcul individuel de l'écart d'authenticité ($\Delta_{\text{IA}} = N3 - N2$).
- **Services Métier** : Trait `DashboardIndicateur07Trait` dans `TableauBordService` calculant :
  - **Pour l'Apprenant** : Données docimologiques d'authenticité (neutre/bienveillant pour éviter le stress de surveillance).
  - **Pour le Formateur** : Taux $r$ de promotion, tableau d'appariement nominatif avec détection des alertes d'écart suspect ($\Delta_{\text{IA}} > +5\text{ pts}$).
  - **Pour le Responsable** : Carte KPI $r$, Scatter Plot SVG interactif (nuage de points avec diagonale idéale $y=x$, zone d'anomalie rouge et fiches de signalement) et colonne dédiée dans la matrice benchmark inter-promotions.
- **Composants Blade V2** : Nouveau composant `kpi-integrite-ia.blade.php`, composant graphique `scatter-plot-ia.blade.php`, et tableau de contrôle formateur sous `layouts.admin-v2`.
- **Simulation & Recette** : Commande artisan `dashboard:simuler-integrite-ia` configurant les 2 cas suspects conformes à la maquette (`Yassir Alami` $\Delta = +10.0$ pts et `Bilal Chami` $\Delta = +7.5$ pts, $r = 0.78$).

### Formules Mathématiques Métier :
$$x_i = \frac{\text{note}(N2_i)}{\text{bareme}(N2_i)} \times 20 \quad (\text{Prototype Live Coding sous surveillance})$$
$$y_i = \frac{\text{note}(N3_i)}{\text{bareme}(N3_i)} \times 20 \quad (\text{Projet Fil Rouge avec assistance IA})$$
$$r = \frac{\sum_{i=1}^{N} (x_i - \bar{x})(y_i - \bar{y})}{\sqrt{\sum_{i=1}^{N} (x_i - \bar{x})^2 \sum_{i=1}^{N} (y_i - \bar{y})^2}}$$
$$\Delta_{\text{IA}}(i) = y_i - x_i$$

---

## 🚦 2. Seuils Didactiques & Statuts Visuels

| Zone | Valeur Pearson $r$ | Diagnostic Docimologique | Statut & Badge UI |
| :--- | :---: | :--- | :--- |
| 🟢 **Alignement Robuste** | $r \ge 0{,}70$ | **Authenticité avérée** : Compétences en direct réinvesties efficacement dans le projet assisté par IA. | `bg-emerald-50 text-emerald-700` *"Alignement Robuste (&ge; 0.70)"* |
| 🟡 **Dispersion Modérée** | $0{,}45 \le r < 0{,}70$ | Corrélation acceptable, hétérogénéité d'autonomie ou retards de finalisation. | `bg-amber-50 text-amber-700` *"Dispersion Modérée"* |
| 🔴 **Divergence / Déconnexion** | $r < 0{,}45$ | **Illusion de compétence** : Risque de délégation aveugle à l'IA sans maîtrise des bases. | `bg-rose-50 text-rose-700` *"Divergence Docimologique"* |

### Seuils Individuels $\Delta_{\text{IA}} = N3 - N2$ :
- $\Delta_{\text{IA}} \le +3\text{ pts}$ : **Conforme / Alignement sain**.
- $+3 < \Delta_{\text{IA}} \le +5\text{ pts}$ : **Vigilance**.
- $\Delta_{\text{IA}} > +5\text{ pts}$ : **Alerte Décrochage IA** (*"Vérifier maîtrise du code en soutenance"*).

---

## 📅 3. Déroulement des Étapes du Sprint 7

### Étape 1 : Optimisation Schéma BDD (`app-migration`)
- Migration : `modules/PkgApprentissage/Database/Migrations/2026_10_13_200000_add_performance_indexes_for_pearson_to_realisation_uas.php`
- Index sur `realisation_ua_prototypes` et `realisation_ua_projets`.
- Action Développeur : `php artisan migrate`.

### Étape 2 : Couche Service & Calculs Statistiques (`pkg-tableau-bord`, `app-service`)
- Création du trait : `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur07Trait.php`
  - `getIndicateurIntegriteApprenant(?RealisationProjet $realisationProjet): array`
  - `getIndicateurIntegriteFormateur(?AffectationProjet $affectationProjet): array`
  - `getIndicateurIntegriteResponsable(array $groupeIds = [], ?int $projetId = null): array`
- Intégration dans `TableauBordService.php`.

### Étape 3 : Contrôleurs & Vues Blade V2 (`app-controller`, `app-blade`)
- Injection de `$kpiIntegriteIA` dans `TableauBordApprenantController`, `TableauBordFormateurController`, `TableauBordResponsableController`.
- Composant carte KPI : `resources/views/components/kpi-integrite-ia.blade.php`.
- Composant graphique : `resources/views/components/scatter-plot-ia.blade.php` (reproduction fidèle du Scatter Plot SVG avec zone rouge $\Delta > 5$ pts et panneau de signalement).
- Tableau d'audit formateur : intégration du tableau nominatif d'écart docimologique.
- Matrice Responsable : intégration de la colonne *"Indice Cohérence IA"* et de la section Scatter Plot interactive.

### Étape 4 : Simulation & Recette Live
- Commande artisan : `php artisan dashboard:simuler-integrite-ia`.
- Recette live sur les 3 profils.
- Mise à jour `.deploy/update_14.md` et `.agent/skills/pkg-tableau-bord/SKILL.md`.
