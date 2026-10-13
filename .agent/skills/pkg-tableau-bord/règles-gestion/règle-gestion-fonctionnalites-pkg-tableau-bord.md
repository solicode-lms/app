# Règle de Gestion : Fonctionnalités PkgTableauBord (Sprint 1)

Ce document décrit les fonctionnalités du module `PkgTableauBord` pour le **Sprint 1 (Indicateur 01 — $T_{\text{amorçage}}$)** déclinées par profil d'acteur.
Les fonctionnalités des sprints suivants seront intégrées de manière itérative.

---

## 🏗️ Architecture des Vues & Layout V2

> [!IMPORTANT]
> - **Layout Unique** : Toutes les vues de tableau de bord étendent obligatoirement `@extends('layouts.admin-v2')`.
> - **Interdiction du Boilerplate de Maquette** : Les vues Blade ne doivent jamais dupliquer le doctype, le header ou la barre latérale (`<aside>`). Tout le shell est géré par le layout.
> - **Indicateurs Fonctionnels Exclusivement** : Les vues de production contiennent uniquement les sections et indicateurs calculés du Sprint 1 ($T_{\text{amorçage}}$). Aucun bloc ou carte factice issu de maquettes des sprints ultérieurs ne doit être présent.
> - **Navigation Sidebar** : Les accès aux 3 dashboards sont enregistrés dans `resources/views/layouts/menu-sidebar.blade.php` et filtrés dynamiquement selon les rôles Spatie de l'utilisateur connecté (`admin`/`root` $\rightarrow$ arborescence des 3 vues ; `formateur` $\rightarrow$ lien direct ; `apprenant` $\rightarrow$ lien direct).

---

## 1. Tableau de Bord Apprenant (Sprint 1)

- **Acteur** : Apprenant.
- **Route** : `tableau-bord.apprenant` (`/tableau-bord/apprenant`)
- **Contrôleur** : `TableauBordApprenantController@index`
- **Titre de la vue** : `@section('page_title', 'Mon Tableau de Bord')`
- **Objectif** : Auto-régulation et réduction de l'anxiété de démarrage.
- **Fonctionnalité Sprint 1** :
  - **En-tête contextuel** : Nom de l'apprenant, groupe/promotion active et projet en cours.
  - **Carte KPI *"Mon délai de démarrage"*** (`PkgTableauBord::components.kpi-amorcage`) :
    - Affichage de son temps d'amorçage individuel formaté (ex: `1h15`).
    - Statut visuel immédiat : Badge vert `Dans les temps` si $\le \text{moyenne promo}$ (ou $\le 90\text{ min}$), sinon badge d'alerte `En retard`.
    - Comparatif didactique : Mention de la moyenne de la classe (ex: `Moyenne classe : 1h45`).
    - Explication contextuelle : *"Délai entre le lancement du sprint et votre premier rendu soumis."*

---

## 2. Tableau de Bord Formateur (Sprint 1)

- **Acteur** : Formateur.
- **Route** : `tableau-bord.formateur` (`/tableau-bord/formateur`)
- **Contrôleur** : `TableauBordFormateurController@index`
- **Titre de la vue** : `@section('page_title', 'Pilotage Didactique')`
- **Objectif** : Pilotage du lancement de session et identification immédiate des apprenants en difficulté d'amorce.
- **Fonctionnalités Sprint 1** :
  - **En-tête de cohorte** : Code du groupe, titre du projet actif et date officielle de lancement.
  - **Carte KPI *"Temps moyen de mise au travail"*** (`PkgTableauBord::components.kpi-amorcage`) :
    - Valeur moyenne de la cohorte formatée (ex: `1h45`).
    - Badge de rythme : `Optimal (<= 2h)` ou `Alerte (> 2h)`.
    - Ratio de cohorte : Nombre d'apprenants amorcés sur l'effectif total (ex: `17 / 20 (3 en attente)`).
  - **Liste d'alerte des apprenants en retard** : Affichage des stagiaires de la promotion n'ayant encore soumis aucune tâche sur le projet en cours pour relance ciblée.

---

## 3. Tableau de Bord Responsable de Formation (Sprint 1)

- **Acteur** : Responsable de formation / Directeur pédagogique.
- **Route** : `tableau-bord.responsable` (`/tableau-bord/responsable`)
- **Contrôleur** : `TableauBordResponsableController@index`
- **Titre de la vue** : `@section('page_title', 'Tableau de Bord de Gouvernance Pédagogique')`
- **Objectif** : Suivi macroscopique et harmonisation du rythme de prise en main des sprints entre cohortes.
- **Fonctionnalités Sprint 1** :
  - **Carte KPI Macroscopique** : Temps moyen global de démarrage sur toutes les promotions actives récentes (ex: `48h18`).
  - **Matrice Comparative / Benchmark Inter-Promotions** : Tableau listant les cohortes récentes avec :
    - Groupe / Promotion (`DWB-101`, `DMB-101`, etc.)
    - Délai moyen d'amorçage ($T_{\text{amorçage}}$)
    - Effectif amorcé vs total
    - Taux d'amorçage en %
    - Statut de conformité didactique (`Conforme` si $\ge 75\%$, sinon `En cours`).
