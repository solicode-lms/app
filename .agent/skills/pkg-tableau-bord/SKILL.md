---
name: pkg-tableau-bord
description: Expert du module PkgTableauBord — Architecture de données, calculs des Indicateurs 01 (T_amorcage), 02 (R_gating), 03 (F_revision), 04 (C_logistique) et 05 (T_file), et composants UI V2. Enrichi de façon itérative par sprint.
---

# Skill : Expert Tableau de Bord (PkgTableauBord) — Sprints 1, 2, 3, 4 & 5

## 🎯 Périmètre Global
**Mission** : Fournir à l'IA une maîtrise rigoureuse de l'architecture, des flux de données, des calculs statistiques et des composants graphiques du module **PkgTableauBord** pour les indicateurs développés :
- **Sprint 1 : Indicateur 01 — Démarrage du travail ($T_{\text{amorçage}}$)**
- **Sprint 2 : Indicateur 02 — Validation du Prototype / Gating ($R_{\text{gating}}$)**
- **Sprint 3 : Indicateur 03 — Qualité du code & Boucles de révision ($F_{\text{révision}}$)**
- **Sprint 4 : Indicateur 04 — Charge Logistique & Administrative du Formateur ($C_{\text{logistique}}$)**
- **Sprint 5 : Indicateur 05 — Délai Moyen de la File d'Attente Live Coding ($T_{\text{file}}$)**

> [!NOTE]
> **Démarche Itérative** : Conformément à la méthodologie projet, ce skill est enrichi sprint par sprint. Il contient uniquement les connaissances et règles des Sprints 1 à 5, sans anticiper les sprints ultérieurs (6 à 9).

### 🚫 Interdictions Globales (Règles d'Or)
1. **Zéro Calcul dans les Contrôleurs ou Blade (CRITIQUE)** : Il est formellement interdit de calculer un indicateur, une moyenne ou une formule mathématique dans un contrôleur ou une vue Blade. Tout calcul passe exclusivement par la couche Service (`TableauBordService`, `DashboardIndicateur01Trait`, `DashboardIndicateur02Trait`, `DashboardIndicateur03Trait`).
2. **Ordre d'Appel ViewState Obligatoire** : Toujours appeler `$this->viewState->setContextKeyIfEmpty(...)` **AVANT** tout appel à `loadLastFilterIfEmpty()` pour retrouver les filtres persistés de l'utilisateur.
3. **Intégrité BDD & Convention `cache_`** : Ne jamais modifier les fichiers `Base/` générés par Gapp. Tout champ dénormalisé ou issu d'un calcul pour accélérer les requêtes KPI DOIT porter le préfixe `cache_`. L'agent ne doit jamais exécuter `php artisan migrate` (réservé au développeur).
4. **Surcharge `$fillable` Interdite** : Ne jamais surcharger `$fillable` manuellement dans les modèles enfants ; la synchronisation est régénérée automatiquement par Gapp dans les classes `Base/` via `gapp make:crud`.
5. **Stack Frontend V2 & Layout Partagé (CRITIQUE)** :
   - Toute vue de tableau de bord V2 doit obligatoirement étendre `@extends('layouts.admin-v2')`.
   - Interdiction formelle de recopier la structure globale de maquette (doctype, head, aside sidebar, header, footer, scripts) dans les vues Blade de contenu.
   - Les vues de production contiennent exclusivement les indicateurs fonctionnels calculés des sprints actifs (Indicateurs 01, 02 et 03), à l'exclusion de toute carte ou bloc factice issu de maquettes des sprints futurs.

---

## ⚡ Actions (Orchestration Sprints 1 & 2)

### Action A : Comprendre la Structure de Données
> **Description** : Expliquer les colonnes d'enrichissement et relations de données pour les indicateurs d'amorçage et de gating.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-bdd-pkg-tableau-bord.md`
- **Entrées** : `Question sur les colonnes cache_`
- **Sorties** : Description des colonnes, index, règles de persistance, tables d'accueil (`affectation_projets`, `realisation_projets`) et commandes artisan de rattrapage.

### Action B : Calculer ou Déboguer l'Indicateur 01 ($T_{\text{amorçage}}$)
> **Description** : Expliquer, implémenter ou corriger l'algorithme du temps d'amorçage individuel et collectif.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-indicateurs-pkg-tableau-bord.md`
- **Sorties** : Formule mathématique, seuils d'alerte didactiques, code PHP de calcul et formatage (ex: `1h15`).

### Action C : Calculer ou Déboguer l'Indicateur 02 ($R_{\text{gating}}$)
> **Description** : Expliquer, implémenter ou corriger l'algorithme du taux de validation du prototype dès le premier essai.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-indicateurs-pkg-tableau-bord.md`
- **Sorties** : Formule micro/méso/macro, détection de premier essai, seuils d'excellence/alerte et gestion des remédiations.

### Action D : Concevoir et Intégrer les Composants Dashboard V2
> **Description** : Guider la création des composants Blade réactifs avec Tailwind CSS pour les 3 acteurs et leur intégration navigation.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-fonctionnalites-pkg-tableau-bord.md`
- **Sorties** :
  - Layout : `layouts.admin-v2`
  - Vues : `PkgTableauBord::apprenant.index`, `PkgTableauBord::formateur.index`, `PkgTableauBord::responsable.index`
  - Composants : `PkgTableauBord::components.kpi-amorcage`, `PkgTableauBord::components.kpi-gating`, `PkgTableauBord::components.session-selector`
  - Navigation : Intégration dans `resources/views/layouts/menu-sidebar.blade.php` conditionnée par rôles.

### Action E : Calculer ou Déboguer l'Indicateur 03 ($F_{\text{révision}}$)
> **Description** : Expliquer, implémenter ou corriger l'algorithme des boucles de révision et du Top 3 des tâches à réaligner.
- **Ressources Utilisées** :
  - `docs/calcul-indicateur-boucles-revision.md`
- **Sorties** : Fréquence unitaire, micro (Apprenant), méso (Formateur avec Top 3 tâches) et macro (Responsable avec benchmark).

### Action F : Télémétrer ou Calculer l'Indicateur 04 ($C_{\text{logistique}}$)
> **Description** : Expliquer le dispositif de télémétrie client (heartbeat avec verrou d'inactivité à 5 min), l'ingestion API et le calcul de la charge hebdomadaire ($T_{\text{pilotage}}$, $T_{\text{file}}$, $T_{\text{bordereaux}}$).
- **Ressources Utilisées** :
  - `cahiers-charges/PkgTableauBord/indicateurs/ind-04-charge-logistique.md`
- **Sorties** :
  - Table : `log_activite_formateurs`
  - Télémétrie : `ActiviteFormateurController@ping`, composant JS `telemetrie-activite.blade.php`
  - Service : `DashboardIndicateur04Trait` (individuel et macro responsable)
  - UI V2 : `kpi-charge-logistique.blade.php`

### Action G : Réguler la File FIFO et Calculer l'Indicateur 05 ($T_{\text{file}}$)
> **Description** : Expliquer le cycle de vie dans la file FIFO, le calcul du rang pour l'apprenant, la console d'arbitrage 1-à-1 vs mutualisation 4x4, et le benchmark des délais d'attente.
- **Ressources Utilisées** :
  - `cahiers-charges/PkgTableauBord/indicateurs/ind-05-delai-file-attente.md`
- **Sorties** :
  - Colonnes `realisation_taches` : `cache_date_entree_file`, `cache_date_debut_live_coding`, `cache_delai_attente_minutes`, `cache_session_live_coding_id`
  - Service : `DashboardIndicateur05Trait`
  - UI V2 : `kpi-file-attente.blade.php`, `file-attente-console.blade.php`, bandeau dynamique apprenant

---

## 🛠️ Règles de Gestion (Savoir-Faire Métier)

### 1. `règle-gestion-bdd-pkg-tableau-bord.md`
- **Rôle** : Description des colonnes `cache_` et de la table `log_activite_formateurs` pour les Sprints 1 à 5.

### 2. `règle-gestion-indicateurs-pkg-tableau-bord.md`
- **Rôle** : Spécification complète des Indicateurs 01 ($T_{\text{amorçage}}$), 02 ($R_{\text{gating}}$), 03 ($F_{\text{révision}}$), 04 ($C_{\text{logistique}}$) et 05 ($T_{\text{file}}$).

### 3. `règle-gestion-fonctionnalites-pkg-tableau-bord.md`
- **Rôle** : Fonctionnalités des Sprints 1 à 5 pour les trois profils (Apprenant, Formateur, Responsable).

---

## 🔄 Scénarios d'Exécution

### Scénario 1 : Calcul de l'amorçage ($T_{\text{amorçage}}$)
1. Lire `règle-gestion-indicateurs-pkg-tableau-bord.md`.
2. Calculer l'écart `cache_date_premiere_soumission - cache_date_lancement` en minutes.
3. Comparer à la moyenne de promotion pour badge vert ("Dans les temps") ou rouge ("En retard").

### Scénario 2 : Évaluation du Prototype ($R_{\text{gating}}$)
1. Lors de l'évaluation de la tâche N2 / Live Coding par le formateur :
2. Incrémenter `cache_nombre_tentatives_prototype`.
3. Si `tentative == 1`, figer `cache_est_valide_prototype_premier_essai` (`true` si `APPROVED`, `false` sinon).
4. Si `tentative > 1`, préserver le résultat de la 1ère tentative (idempotence de l'indicateur).

### Scénario 3 : Boucles de Révision ($F_{\text{révision}}$)
1. Lors du passage d'une tâche à `REVISION_NECESSAIRE` :
2. Incrémenter `cache_nombre_revisions`.
3. Calculer $F_{\text{révision}} = \frac{\sum \text{rejets}}{\text{total tâches}}$ et $\tau_{\text{conforme\_1er\_jet}} = \frac{\text{tâches sans rejet}}{\text{total tâches}} \times 100$.
4. Extraire le Top 3 des tâches concentrant le plus grand nombre de rejets pour le formateur.

### Scénario 4 : Suivi de la Charge Administrative ($C_{\text{logistique}}$)
1. Ping régulier de 60s émis par `telemetrie-activite.blade.php` tant qu'il y a des interactions utilisateur (< 5 min d'inactivité).
2. Endpoint `ActiviteFormateurController@ping` incrémente `duree_secondes` dans `log_activite_formateurs`.
3. `DashboardIndicateur04Trait` convertit en heures décimales par semaine :
   - $\le 2.5\,h/\text{sem}$ : badge vert (*"Logistique optimisée"*).
   - $2.5\,h < C \le 5.0\,h/\text{sem}$ : badge ambre (*"Charge notable"*).
   - $> 5.0\,h/\text{sem}$ : badge rose (*"Surcharge bureaucratique"*).
4. La vue Responsable agrège la moyenne d'équipe et dresse le benchmark individuel par formateur.

### Scénario 5 : Régulation de la File Live Coding ($T_{\text{file}}$)
1. Dès que l'apprenant soumet son prototype N2, la tâche passe à `READY_FOR_LIVE_CODING` et fige `cache_date_entree_file = now()`.
2. L'apprenant voit en temps réel son rang d'attente FIFO (ex: Rang #2, ~18 min estimées).
3. Le formateur consulte sa console ordonnée ASC et appelle le suivant, ou active une session mutualisée 4x4 si la file s'accumule (> 4 apprenants ou attente > 60 min).
4. Au passage à `IN_LIVE_CODING`, `cache_date_debut_live_coding` est enregistré et `cache_delai_attente_minutes` est calculé.
