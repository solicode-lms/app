# Règle de Gestion : Fonctionnalités PkgTableauBord (Sprint 1)

Ce document décrit les fonctionnalités du module `PkgTableauBord` pour le **Sprint 1 (Indicateur 01 — $T_{\text{amorçage}}$)** déclinées par profil d'acteur.
Les fonctionnalités des sprints suivants seront intégrées de manière itérative.

---

## 1. Tableau de Bord Apprenant (Sprint 1)

- **Acteur** : Apprenant.
- **Objectif** : Auto-régulation et réduction de l'anxiété de démarrage.
- **Fonctionnalité Sprint 1** :
  - **Carte KPI *"Mon délai de démarrage"*** :
    - Affichage de son temps d'amorçage individuel formaté (ex: `1h15`).
    - Statut visuel immédiat : Badge vert `Dans les temps` si $\le \text{moyenne promo}$ (ou $\le 90\text{ min}$), sinon badge d'alerte `En retard`.
    - Comparatif didactique : Mention de la moyenne de la classe (ex: `Moyenne classe : 1h45`).
    - Explication contextuelle : *"Délai entre le lancement du sprint et votre premier rendu soumis."*

---

## 2. Tableau de Bord Formateur (Sprint 1)

- **Acteur** : Formateur.
- **Objectif** : Pilotage du lancement de session et identification immédiate des apprenants en difficulté d'amorce.
- **Fonctionnalités Sprint 1** :
  - **Carte KPI *"Temps moyen de mise au travail"*** :
    - Valeur moyenne de la cohorte formatée (ex: `1h45`).
    - Badge de rythme : `Optimal (<= 2h)` ou `Alerte (> 2h)`.
    - Ratio de cohorte : Nombre d'apprenants amorcés sur l'effectif total (ex: `17 / 20 (3 en attente)`).
    - Liste des apprenants en retard d'amorçage pour relance ciblée.

---

## 3. Tableau de Bord Responsable de Formation (Sprint 1)

- **Acteur** : Responsable de formation / Directeur pédagogique.
- **Objectif** : Suivi macroscopique et harmonisation du rythme de prise en main des sprints entre cohortes.
- **Fonctionnalités Sprint 1** :
  - **Carte KPI *"Délai moyen de prise en main du sprint"*** :
    - Temps moyen global de démarrage sur la filière (ex: `1h50`).
    - Benchmark comparatif entre promotions (ex: `DWB-101 (1h45)` vs `DMB-101 (1h55)`).
