# Plan d'Action — Phase 5 : Planification des Sprints de Réalisation Technique

## 🎯 Objectif de la Phase
Ordonnancer et exécuter l'intégration technique du module **PkgTableauBord** dans le code source de Solicode LMS. Le plan suit rigoureusement les règles critiques de développement (notamment le regroupement de l'ensemble des migrations BDD dans le Sprint 1) et garantit la séparation des responsabilités (Contrôleur $\rightarrow$ Service $\rightarrow$ Modèle/Composant).

---

## 🗺️ 1. Feuille de Route & Diagramme de Déploiement

Le déploiement technique est adossé au planning Gantt validé dans [plan.mmd](file:///c:/AppServer/solicode-lms/cahiers-charges/PkgTableauBord/plan.mmd) :

```mermaid
gantt
    title Déploiement du Module PkgTableauBord
    dateFormat  YYYY-MM-DD
    section Sprint 1 : Conception & BDD
    Définition BDD & Entités / Vues SQL         :done, s1_1, 2026-10-13, 2d
    Migration & Gapp meta:sync                 :done, s1_2, after s1_1, 1d
    Charte Graphique HTML/CSS V2               :active, s1_3, after s1_2, 2d
    section Sprint 2 : Maquettes UI
    Maquette HTML/CSS Apprenant                :s2_1, after s1_3, 2d
    Maquette HTML/CSS Formateur (FIFO & 10min)  :s2_2, after s2_1, 2d
    Maquette HTML/CSS Responsable (Gouvernance):s2_3, after s2_2, 2d
    section Sprint 3 : Services de Calcul
    LearningAnalyticsService (Annexe M)        :s3_1, after s2_3, 3d
    Algorithme de conversion docimologique 65/35:s3_2, after s3_1, 2d
    section Sprint 4 : Intégration V2 & Alpine
    Composants Blade V2 & Layout dédié         :s4_1, after s3_2, 3d
    Contrôleurs et injection ViewState         :s4_2, after s4_1, 2d
    Interactions dynamiques Alpine.js          :s4_3, after s4_2, 2d
```

---

## 🏃 2. Détail des Sprints de Développement

### Sprint 1 : Base de Données, Adaptations SoliLMS & Charte V2
> **Règle d'or** : Toutes les modifications de la base de données doivent être regroupées dans ce premier sprint.
- [ ] Création / modification des migrations nécessaires dans SoliLMS (`app-migration`) :
  - Ajout des nouveaux états dans `realisation_taches` (ex: `Besoin de révision`).
  - Ajout des colonnes de timestamps pour la file d'attente FIFO Live Coding.
  - Ajout des colonnes ou tables nécessaires aux logs d'activité formateur.
- [ ] Exécution manuelle de la migration par le développeur (`php artisan migrate`).
- [ ] Synchronisation des métadonnées Gapp (`gapp meta:sync`) et mise à jour CRUD (`sys-gapp`).
- [ ] Finalisation de la page de référence `charte-graphique/index.html`.
- **Skills requis** : `app-migration`, `app-model`, `sys-gapp`, `sys-ux`.

---

### Sprint 2 : Prototypage HTML / CSS des Maquettes
> **Objectif** : Valider visuellement et ergonomiquement les 3 écrans avant le développement PHP.
- [ ] Création de `dashboard-apprenant.html` (progression, $T_{\text{amorçage}}$, statut gating).
- [ ] Création de `dashboard-formateur.html` (file FIFO interactive, détection seuil 10 min, $T_{\text{file}}$).
- [ ] Création de `dashboard-responsable.html` (gouvernance, corrélation IA $r$, conformité 65/35).
- [ ] Revue ergonomique et validation des maquettes avec les parties prenantes.
- **Skills requis** : `app2-front-end`, `sys-ux`.

---

### Sprint 3 : Couche Métier & Services de Calcul (Backend)
> **Objectif** : Implémenter toute la logique métier et les calculs statistiques dans la couche Service (sans logique dans les contrôleurs ni dans Blade).
- [ ] Création de `TableauBordService` étendant `BaseService` (`app-service`).
- [ ] Création du trait `DashboardCalculTrait` pour isoler les calculs complexes :
  - Calcul du temps d'amorçage moyen ($T_{\text{amorçage}}$).
  - Taux de validation au gating au 1er essai ($R_{\text{gating}}$).
  - Gestion des transitions d'historique et boucles de révision ($F_{\text{révision}}$).
  - Temps d'attente FIFO ($T_{\text{file}}$) et taux d'intervention ($T_{\text{intervention}}$).
  - Coefficient de corrélation linéaire de Pearson ($r$ d'intégrité IA).
  - Indicateur de Couverture Pédagogique ($\text{ICP}$).
- [ ] Implémentation du service docimologique : calcul automatique officiel des notes CC (sur 20) et EFM (sur 40) selon la règle 65/35 ($\tau_{\text{conformité}}$).
- **Skills requis** : `app-service`, `pkg-apprentissage`, `pkg-realisation-tache`.

---

### Sprint 4 : Contrôleurs, Routage & Intégration V2 (Blade & Alpine.js)
> **Objectif** : Exposer les écrans, gérer les filtres persistés et connecter les composants Blade réactifs.
- [ ] Définition des routes web sécurisées (`app-route`) avec permissions Spatie :
  - `/tableau-bord/apprenant`
  - `/tableau-bord/formateur`
  - `/tableau-bord/responsable`
- [ ] Création de `TableauBordController` (`app-controller`) :
  - Respect de l'ordre d'appel ViewState : `setContextKeyIfEmpty()` avant tout chargement de filtre.
  - Injection des données calculées via `compact()`.
- [ ] Découpage en composants anonymes Blade V2 (`app-blade`, `app2-front-end`) :
  - `<x-tableau-bord.kpi-card>`
  - `<x-tableau-bord.fifo-queue>`
  - `<x-tableau-bord.remediation-alert>`
  - `<x-tableau-bord.scatter-plot>`
- [ ] Dynamisation frontend avec Alpine.js (changement de vue, appel FIFO, micro-interactions).
- **Skills requis** : `app-controller`, `app-route`, `app-blade`, `app2-front-end`.

---

## 🎯 3. Critères d'Acceptation & Clôture
1. **Conformité des calculs** : Les 9 formules mathématiques de l'Annexe M produisent des résultats exacts et cohérents.
2. **Fluidité UI** : L'interface réagit instantanément sans rechargement de page pour la file FIFO.
3. **Zéro altération BDD non tracée** : Toutes les modifications de tables ont été faites via migrations officielles au Sprint 1.
4. **Documentation** : Toutes les issues de réalisation sont archivées dans `issues_closed/` après validation.
