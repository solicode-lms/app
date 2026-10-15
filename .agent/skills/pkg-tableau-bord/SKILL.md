---
name: pkg-tableau-bord
description: Expert du module PkgTableauBord — Architecture générale du tableau de bord, règles générales inviolables, modèle de données, ergonomie Frontend V2 100% inline, et calculs des 9 indicateurs didactiques (T_amorcage, R_gating, F_revision, C_logistique, T_file, T_intervention, TRVS, ICP, TCP-UA).
---

# Skill : Expert Tableau de Bord (PkgTableauBord)

## 🎯 Périmètre & Mission Globale

Le module **PkgTableauBord** constitue la console de gouvernance et de régulation pédagogique de SoliLMS. Il orchestre le pilotage multi-acteurs à travers trois espaces interconnectés :
1. **Espace Apprenant** (`admin.PkgTableauBord.apprenant`) : Autorégulation, transparence sur les prérequis et suivi du rythme personnel.
2. **Espace Formateur** (`admin.PkgTableauBord.formateur`) : Pilotage synchrone en classe, détection des ruptures d'autonomie et régulation des soutenances.
3. **Espace Responsable** (`admin.PkgTableauBord.responsable`) : Gouvernance macroscopique, benchmark multi-filières et équité certificative.

Ce skill fournit à l'IA une maîtrise rigoureuse des règles architecturales transversales, des conventions de persistance, de l'ergonomie Frontend V2 et de la modélisation des **9 indicateurs didactiques officiels**.

---

## 🛑 Règles Générales du Tableau de Bord (Inviolables)

### 1. Zéro Calcul dans les Contrôleurs ou Blade (CRITIQUE)
- Il est formellement interdit de calculer un indicateur, une moyenne, un délai ou une formule mathématique dans un Contrôleur ou une vue Blade.
- **Flux d'Architecture Strict** :
  $$\text{Contrôleur HTTP} \longrightarrow \text{TableauBordService} \longrightarrow \text{DashboardIndicateur0XTrait} \longrightarrow \text{Modèles / DB}$$
- Le Contrôleur gère uniquement la requête HTTP, l'autorisation et la transmission du tableau de données préparé (`$kpi*`, `$detail`). La vue Blade se contente de l'affichage.

### 2. Ordre d'Appel ViewState Obligatoire (CRITIQUE)
- Le `ViewStateService` organise son état et ses filtres persistés par `contextKey`.
- **Règle absolue** : **TOUJOURS appeler `$this->viewState->setContextKeyIfEmpty(...)` AVANT tout appel à `loadLastFilterIfEmpty()`.**
```php
$this->viewState->setContextKeyIfEmpty('responsable.dashboard'); // 1. Définir le contexte
$this->tableauBordService->loadLastFilterIfEmpty();              // 2. Charger les filtres persistés
$filtres = $this->viewState->getFilterVariables('responsable');  // 3. Lire les variables
```

### 3. Conventions BDD & Préfixe `cache_` (CRITIQUE)
- Tout champ dénormalisé ou issu d'un calcul d'agrégation persisté pour accélérer les requêtes de pilotage **DOIT impérativement porter le préfixe `cache_`** (ex : `cache_date_lancement`, `cache_nombre_tentatives_prototype`, `cache_date_entree_file`).
- **Gouvernance Gapp & `$fillable`** : Ne **JAMAIS** modifier les fichiers situés dans `Base/` et ne **JAMAIS** surcharger `$fillable` manuellement dans les modèles enfants Eloquent. La synchronisation est assurée par Gapp via `gapp make:crud`.
- **Migrations** : L'agent a l'interdiction stricte d'exécuter `php artisan migrate` ; il produit la migration et délègue l'exécution au développeur.

### 4. Évitement de l'Erreur MySQL 1093 sur les Mises à Jour Dénormalisées
- L'injection de sous-requêtes sur la même table via les scopes globaux (ex: scope d'apprenant actif) provoque l'erreur MySQL `1093: You can't specify target table for update in FROM clause`.
- **Solution Obligatoire** : Pour les updates dénormalisés par clé primaire ou les incrémentations de compteurs `cache_`, toujours utiliser :
  `DB::table('table_name')->where('id', $id)->update(...)` ou `Model::withoutGlobalScopes()->where('id', $id)->update(...)`.

### 5. Stack Frontend V2 & Ergonomie 100% Inline (CRITIQUE)
- **Layout Unique** : Toute vue de tableau de bord et page de détail V2 étend obligatoirement `@extends('layouts.admin-v2')`.
- **Interdiction de Popups au Chargement (Zéro Modale)** : Les tableaux de bord et pages de détail doivent s'afficher instantanément de manière **100% inline**. Aucune fenêtre modale ne doit s'ouvrir automatiquement au chargement de page.
- **Réactivité Alpine.js & Tailwind CSS** : Les interactions (recherche textuelle instantanée, filtrage par tranches, onglets `tabActive`) sont pilotées par Alpine.js (`x-data`, `x-model`, `x-show`, `x-cloak`) avec les classes utilitaires de Tailwind CSS.
- **Composants Réutilisables** : Les cartes KPI sont encapsulées dans `PkgTableauBord::components.kpi-*` et partagées entre les profils via la prop `type` (`'apprenant'`, `'formateur'`, `'responsable'`).

### 6. Principe de Clarté / Zéro Fallback Magique (Non-Ambiguïté)
- Interdiction formelle d'injecter des valeurs de secours arbitraires (ex : fallback masqué de `1h45` ou `100%`) lorsqu'une métrique ne dispose pas de données réelles.
- Lorsqu'aucune donnée n'est disponible (ex : zéro apprenant amorcé), la valeur vaut sémantiquement `null`, affichée `'--'`. Le statut est obligatoirement `'En attente'` avec un badge neutre (`bg-slate-50 text-slate-700 border border-slate-200`).

### 7. Architecture des Pages de Détail par Indicateur
- Chaque indicateur dispose d'une page d'analyse granulaire sous la route :
  `admin.PkgTableauBord.indicateurs.{nom}` (ex : `amorcage`, `gating`, `revisions`, `charge-logistique`, `file-attente`, `intervention`, `trvs`, `couverture`, `tcp-ua`).
- Le contrôleur unique [IndicateurDetailController.php](file:///c:/AppServer/solicode-lms/modules/PkgTableauBord/Controllers/IndicateurDetailController.php) résout le contexte de filtrage multidimensionnel (`resolveFilterContext`), appelle la méthode de détail du service, et transmet la structure `$detail` à la vue Blade.
- Chaque carte KPI du dashboard intègre un lien direct et cliquable vers sa page d'analyse détaillée.

---

## 📂 Organisation des Règles & Capacités

```
.agent/skills/pkg-tableau-bord/
├── SKILL.md                                 # Présent document : Règles générales & Actions
├── règles-gestion/
│   └── règle-gestion-pkg-tableau-bord.md   # Modèle BDD, Profils d'acteurs & Matrice canonique
└── capacités/
    ├── capacité-indicateur-01-amorcage.md             # T_amorçage (Délai d'amorçage & Absences)
    ├── capacité-indicateur-02-gating.md               # R_gating (Validation prototype 1er essai)
    ├── capacité-indicateur-03-revision.md             # F_révision (Boucles de révision & Top 3)
    ├── capacité-indicateur-04-charge-logistique.md    # C_logistique (Action-Cost Model 26h/sem)
    ├── capacité-indicateur-05-file-attente.md         # T_file (File FIFO & Discord 4x4)
    ├── capacité-indicateur-06-remediation.md          # T_intervention (Ruptures & Remédiation)
    ├── capacité-indicateur-07-trvs.md                 # TRVS (Réalisation validée & Impact Live Coding)
    ├── capacité-indicateur-08-couverture-competences.md # ICP (Compétences & Faux positifs temporels)
    └── capacité-indicateur-09-planification-ua.md     # TCP-UA (Planification évaluative des UA)
```

---

## ⚡ Actions Expertes (Capacités Dédiées)

### Action A : Maîtriser le Modèle de Données & les Profils d'Acteurs
> **Description** : Consulter la structure des tables (`affectation_projets`, `realisation_projets`, `realisation_taches`, `remediations`, `alignement_uas`), les règles de nommage `cache_`, et la répartition des fonctionnalités entre Apprenant, Formateur et Responsable.
- **Ressource Référente** : [règle-gestion-pkg-tableau-bord.md](file:///c:/AppServer/solicode-lms/.agent/skills/pkg-tableau-bord/r%C3%A8gles-gestion/r%C3%A8gle-gestion-pkg-tableau-bord.md).

### Action B : Calculer ou Déboguer l'Indicateur 01 ($T_{\text{amorçage}}$)
> **Description** : Gérer le délai moyen de mise au travail, la détection des séances de lancement ($S1, S2, S3$), l'exclusion des démarrages avec absence, et le traitement des valeurs `'--'`.
- **Capacité Référente** : [capacité-indicateur-01-amorcage.md](file:///c:/AppServer/solicode-lms/.agent/skills/pkg-tableau-bord/capacit%C3%A9s/capacité-indicateur-01-amorcage.md).

### Action C : Calculer ou Déboguer l'Indicateur 02 ($R_{\text{gating}}$)
> **Description** : Gérer le taux de validation du prototype Niveau 2 ($N2$) dès le 1er essai, l'idempotence docimologique du statut, et le déblocage du Fil Rouge ($N3$).
- **Capacité Référente** : [capacité-indicateur-02-gating.md](file:///c:/AppServer/solicode-lms/.agent/skills/pkg-tableau-bord/capacit%C3%A9s/capacité-indicateur-02-gating.md).

### Action D : Calculer ou Déboguer l'Indicateur 03 ($F_{\text{révision}}$)
> **Description** : Gérer les rejets de code (`REVISION_NECESSAIRE`), le taux de premier jet conforme, et l'extraction automatique du Top 3 des tâches à réaligner.
- **Capacité Référente** : [capacité-indicateur-03-revision.md](file:///c:/AppServer/solicode-lms/.agent/skills/pkg-tableau-bord/capacit%C3%A9s/capacité-indicateur-03-revision.md).

### Action E : Calculer la Charge Logistique Réelle du Formateur ($C_{\text{logistique}}$)
> **Description** : Appliquer le Standard Action-Cost Model par acte administratif de session (décompté au niveau `Tache`), sanctuariser l'observation pédagogique à 0 min, et étalonner sur 26h/semaine.
- **Capacité Référente** : [capacité-indicateur-04-charge-logistique.md](file:///c:/AppServer/solicode-lms/.agent/skills/pkg-tableau-bord/capacit%C3%A9s/capacité-indicateur-04-charge-logistique.md).

### Action F : Réguler la File FIFO et Calculer l'Indicateur 05 ($T_{\text{file}}$)
> **Description** : Gérer la file d'attente FIFO des prototypes N2, la console d'appel temps réel, les soutenances solo (40-60 min), et la régulation par session mutualisée 4x4 Discord.
- **Capacité Référente** : [capacité-indicateur-05-file-attente.md](file:///c:/AppServer/solicode-lms/.agent/skills/pkg-tableau-bord/capacit%C3%A9s/capacité-indicateur-05-file-attente.md).

### Action G : Traiter l'Alerte et Calculer l'Indicateur 06 ($T_{\text{intervention}}$)
> **Description** : Vérifier la présence impérative d'un démonstrateur (`is_live_coding = 1`), déclencher la remédiation automatique après $t_0 + 30\text{ min}$, inscrire dans `remediations`, afficher exclusivement les apprenants bloqués sans modale, et résoudre vers `VALIDEE`.
- **Capacité Référente** : [capacité-indicateur-06-remediation.md](file:///c:/AppServer/solicode-lms/.agent/skills/pkg-tableau-bord/capacit%C3%A9s/capacité-indicateur-06-remediation.md).

### Action H : Mesurer la Réalisation Validée et l'Impact Live Coding ($\text{TRVS}$)
> **Description** : Appliquer la règle d'achèvement strict (`DONE`), confronter les sessions avant vs après Live Coding synchrone pour calculer le gain $\Delta \text{TRVS}$, et suivre l'évolution séquentielle.
- **Capacité Référente** : [capacité-indicateur-07-trvs.md](file:///c:/AppServer/solicode-lms/.agent/skills/pkg-tableau-bord/capacit%C3%A9s/capacité-indicateur-07-trvs.md).

### Action I : Évaluer la Progression dans le Référentiel National ($\text{ICP}$)
> **Description** : Confronter dynamiquement `progression_cache` avec le calendrier idéal `progression_ideal_cache`, éradiquer les faux positifs temporels, et qualifier les impasses didactiques réelles.
- **Capacité Référente** : [capacité-indicateur-08-couverture-competences.md](file:///c:/AppServer/solicode-lms/.agent/skills/pkg-tableau-bord/capacit%C3%A9s/capacité-indicateur-08-couverture-competences.md).

### Action J : Mesurer le Taux de Couverture de Planification des UA ($\text{TCP-UA}$)
> **Description** : Calculer le ratio d'anticipation préventive des Unités d'Apprentissage couvertes par une `SessionFormation`, appliquer la règle d'unicité stricte, et alerter sur les UA non planifiées par module.
- **Capacité Référente** : [capacité-indicateur-09-planification-ua.md](file:///c:/AppServer/solicode-lms/.agent/skills/pkg-tableau-bord/capacit%C3%A9s/capacité-indicateur-09-planification-ua.md).
