# Règle de Gestion : Fonctionnalités PkgTableauBord (Sprints 1 & 2)

Ce document décrit les fonctionnalités du module `PkgTableauBord` pour les **Sprints 1 (Indicateur 01 — $T_{\text{amorçage}}$)** et **Sprint 2 (Indicateur 02 — $R_{\text{gating}}$)** déclinées par profil d'acteur.
Les fonctionnalités des sprints ultérieurs (3 à 9) seront intégrées de manière itérative.

---

## 🏗️ Architecture des Vues & Layout V2

> [!IMPORTANT]
> - **Layout Unique** : Toutes les vues de tableau de bord étendent obligatoirement `@extends('layouts.admin-v2')`.
> - **Gestion de la Session Active (Projet de Formateur)** : Chaque tableau de bord intègre dans `@section('actions')` le composant `PkgTableauBord::components.session-selector` permettant à l'acteur de changer de session de projet (`Modules\PkgCreationProjet\Models\Projet`). Dans l'architecture SoliLMS, chaque `Projet` est une session de formation concrète pilotée par le formateur (la classe `SessionFormation` étant réservée à la planification théorique). Le choix est persisté en session PHP (`active_projet_id`) et tous les indicateurs sont contextualisés sur le périmètre du projet sélectionné.
> - **Interdiction du Boilerplate de Maquette** : Les vues Blade ne doivent jamais dupliquer le doctype, le header ou la barre latérale (`<aside>`). Tout le shell est géré par le layout.
> - **Indicateurs Fonctionnels Exclusivement** : Les vues de production contiennent uniquement les sections et indicateurs calculés des sprints actifs (Indicateurs 01 et 02). Aucun bloc ou carte factice issu de maquettes des sprints ultérieurs ne doit être présent.
> - **Navigation Sidebar** : Les accès aux 3 dashboards sont enregistrés dans `resources/views/layouts/menu-sidebar.blade.php` et filtrés dynamiquement selon les rôles Spatie de l'utilisateur connecté (`admin`/`root` $\rightarrow$ arborescence des 3 vues ; `formateur` $\rightarrow$ lien direct ; `apprenant` $\rightarrow$ lien direct).

---

## 1. Tableau de Bord Apprenant (Sprints 1 & 2)

- **Acteur** : Apprenant.
- **Route** : `tableau-bord.apprenant` (`/tableau-bord/apprenant`)
- **Contrôleur** : `TableauBordApprenantController@index`
- **Titre de la vue** : `@section('page_title', 'Mon Tableau de Bord')`
- **Objectif** : Auto-régulation, maîtrise des prérequis et réduction de l'anxiété.
- **Fonctionnalités Sprint 1 ($T_{\text{amorçage}}$)** :
  - **En-tête contextuel** : Nom de l'apprenant, groupe/promotion active et projet en cours.
  - **Carte KPI *"Mon délai de démarrage"*** (`PkgTableauBord::components.kpi-amorcage`) :
    - Affichage de son temps d'amorçage individuel formaté (ex: `1h15`).
    - Statut visuel immédiat : Badge vert `Dans les temps` si $\le \text{moyenne promo}$ (ou $\le 90\text{ min}$), sinon badge d'alerte `En retard`.
    - Comparatif didactique : Mention de la moyenne de la classe (ex: `Moyenne classe : 1h45`).
- **Fonctionnalités Sprint 2 ($R_{\text{gating}}$)** :
  - **Carte KPI *"Validation du Prototype"*** (`PkgTableauBord::components.kpi-gating`) :
    - Statut d'évaluation :
      - `Validé (1er essai)` : Badge émeraude si réussi dès la 1ère évaluation.
      - `En remédiation` : Badge ambre si ajourné lors du 1er passage (2ème essai en attente).
      - `En attente d'évaluation` : Badge neutre tant que non évalué.
    - Droit d'accès au Projet Fil Rouge : `Débloqué (Niveau 3)` vs `Verrouillé (Attente soutenance)`.

---

## 2. Tableau de Bord Formateur (Sprints 1 & 2)

- **Acteur** : Formateur.
- **Route** : `tableau-bord.formateur` (`/tableau-bord/formateur`)
- **Contrôleur** : `TableauBordFormateurController@index`
- **Titre de la vue** : `@section('page_title', 'Pilotage Didactique')`
- **Objectif** : Régulation des passages, suivi du rythme et déclenchement ciblé des ateliers de remédiation.
- **Fonctionnalités Sprint 1 ($T_{\text{amorçage}}$)** :
  - **En-tête de cohorte** : Code du groupe, titre du projet actif et date officielle de lancement.
  - **Carte KPI *"Temps moyen de mise au travail"*** (`PkgTableauBord::components.kpi-amorcage`) :
    - Valeur moyenne de la cohorte formatée (ex: `1h45`).
    - Badge de rythme : `Optimal (<= 2h)` ou `Alerte (> 2h)`.
    - Ratio de cohorte : Nombre d'apprenants amorcés sur l'effectif total (ex: `17 / 20`).
  - **Liste d'alerte des apprenants en retard** : Affichage des stagiaires n'ayant pas encore amorcé de tâche.
- **Fonctionnalités Sprint 2 ($R_{\text{gating}}$)** :
  - **Carte KPI *"Validation au 1er passage"*** (`PkgTableauBord::components.kpi-gating`) :
    - Taux de réussite au premier essai : $R_{\text{gating}}\%$ (ex: `75%`).
    - Ratio chiffré : `$valides / $evalues ($en_remediation en rattrapage)`.
    - Statut didactique : `Excellence` ($\ge 80\%$), `Vigilance` ($65-80\%$), `Alerte` ($< 65\%$).
  - **Liste nominative des apprenants en remédiation** : Stagiaires ajournés à orienter vers un atelier d'étayage avant leur 2ème passage.

---

## 3. Tableau de Bord Responsable de Formation (Sprints 1 & 2)

- **Acteur** : Responsable de formation / Directeur pédagogique.
- **Route** : `tableau-bord.responsable` (`/tableau-bord/responsable`)
- **Contrôleur** : `TableauBordResponsableController@index`
- **Titre de la vue** : `@section('page_title', 'Tableau de Bord de Gouvernance Pédagogique')`
- **Objectif** : Suivi macroscopique, harmonisation des exigences et contrôle de l'équité certificative.
- **Fonctionnalités Sprint 1 ($T_{\text{amorçage}}$)** :
  - **Carte KPI Macroscopique** : Délai moyen global de démarrage sur toutes les promotions actives récentes.
- **Fonctionnalités Sprint 2 ($R_{\text{gating}}$)** :
  - **Carte KPI Macroscopique** : Taux moyen global de réussite au gating direct.
  - **Matrice Comparative / Benchmark Inter-Promotions** :
    - Colonne $T_{\text{amorçage}}$ : Délai moyen de démarrage.
    - Colonne $R_{\text{gating}}$ : Taux de validation au 1er essai (%) par promotion.
    - Effectifs amorcés et évalués.
    - Statuts de conformité pédagogique (`Conforme` vs `Vigilance`).
