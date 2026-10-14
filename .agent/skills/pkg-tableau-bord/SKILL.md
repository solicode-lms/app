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
6. **Zéro Fallback Magique / Principe de Clarté (CRITIQUE)** :
   - Interdiction formelle d'injecter des valeurs de secours arbitraires (ex : fallback masqué de `1h45` / `105 min`) lorsqu'une métrique ne dispose pas de données réelles.
   - Lorsqu'aucun apprenant n'a amorcé le projet (`nb_amorces === 0`), le délai moyen vaut sémantiquement `null`, affiché `'--'`. Le statut associé est obligatoirement `'En attente'` avec badge neutre (`bg-slate-50 text-slate-700 border border-slate-200`), et non un statut arbitraire comme `'Optimal (<= 3h)'` ou `'Au-delà du SLA'`.
7. **Architecture des Pages de Détail par Indicateur (CRITIQUE)** :
   - Chaque indicateur dispose d'une page d'analyse détaillée dédiée sous `admin.PkgTableauBord.indicateurs.*` (contrôleur `IndicateursController`). Les cartes KPI du tableau de bord (titre cliquable + bouton *"Analyse détaillée"*) mènent directement vers cette page.
   - Les pages de détail comportent : en-tête contextuel, jauge synthétique, 5 cartes clés, histogramme de distribution temporelle, tableau interactif filtrable (Alpine.js) et benchmark comparatif des sessions de formation (affectations N2).

---

## ⚡ Actions (Orchestration Sprints 1 & 2)

### Action A : Comprendre la Structure de Données
> **Description** : Expliquer les colonnes d'enrichissement et relations de données pour les indicateurs d'amorçage et de gating.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-bdd-pkg-tableau-bord.md`
- **Entrées** : `Question sur les colonnes cache_`
- **Sorties** : Description des colonnes, index, règles de persistance, tables d'accueil (`affectation_projets`, `realisation_projets`) et commandes artisan de rattrapage.

### Action B : Calculer ou Déboguer l'Indicateur 01 ($T_{\text{amorçage}}$)
> **Description** : Expliquer, implémenter ou corriger l'algorithme du temps d'amorçage individuel et collectif, avec détection des séances de lancement et exclusion des absences.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-indicateurs-pkg-tableau-bord.md`
- **Sorties** :
  - Créneaux didactiques officiels (`ParametrePedagogiqueService`) : Séance 1 (09:00-11:30), Séance 2 (11:30-14:00), Séance 3 (14:30-17:00).
  - Détection présence : 1ère tâche passée à `En cours` (`IN_PROGRESS`) avant fin de séance (`determinerFinSeanceLancement`).
  - Démarrages avec absence (`demarrage_avec_absence`) : 1ère tâche passée en cours post-séance $\rightarrow$ exclus de la moyenne collective.
  - Détection précise : `determinerDatePremiereTacheEnCours` via `HistoriqueRealisationTache` à la seconde.
  - Formule mathématique moyenne sur présents, médiane globale, seuils didactiques (SLA $\le$ 3h) et formatage (ex: `1h15` ou `'--'`).

### Action C : Calculer ou Déboguer l'Indicateur 02 ($R_{\text{gating}}$)
> **Description** : Expliquer, implémenter ou corriger l'algorithme du taux de validation du prototype dès le premier essai.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-indicateurs-pkg-tableau-bord.md`
- **Sorties** : Formule micro/méso/macro, détection de premier essai, seuils d'excellence/alerte et gestion des remédiations.

### Action D : Concevoir et Intégrer les Composants Dashboard & Pages de Détail V2
> **Description** : Guider la création des composants Blade réactifs avec Tailwind CSS pour les 3 acteurs, l'intégration navigation et les pages d'analyse détaillée par indicateur.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-fonctionnalites-pkg-tableau-bord.md`
- **Sorties** :
  - Layout : `layouts.admin-v2`
  - Vues Dashboard : `PkgTableauBord::apprenant.index`, `PkgTableauBord::formateur.index`, `PkgTableauBord::responsable.index`
  - Vues Détail : `PkgTableauBord::indicateurs.amorcage` (contrôleur `IndicateursController@amorcage`)
  - Composants : `PkgTableauBord::components.kpi-amorcage` (liens cliquables vers le détail), `PkgTableauBord::components.kpi-gating`, `PkgTableauBord::components.session-selector`, `PkgTableauBord::components.responsable-filter-bar`
  - Filtres interactifs : Roster apprenants réactif sous Alpine.js (`x-data`, filtres par tranches, recherche textuelle instantanée)
  - Navigation : Intégration dans `resources/views/layouts/menu-sidebar.blade.php` conditionnée par rôles.

### Action E : Calculer ou Déboguer l'Indicateur 03 ($F_{\text{révision}}$)
> **Description** : Expliquer, implémenter ou corriger l'algorithme des boucles de révision et du Top 3 des tâches à réaligner.
- **Ressources Utilisées** :
  - `docs/calcul-indicateur-boucles-revision.md`
- **Sorties** : Fréquence unitaire, micro (Apprenant), méso (Formateur avec Top 3 tâches) et macro (Responsable avec benchmark).

### Action F : Calculer la Charge Logistique Réelle du Formateur ($C_{\text{logistique}}$)
> **Description** : Modèle déterministe par actes réels de gestion (Standard Action-Cost Model) avec distinction stricte entre gestion administrative et temps pédagogique.
- **Ressources Utilisées** :
  - `modules/PkgTableauBord/Services/ParametrePedagogiqueService.php`
  - `modules/PkgTableauBord/Services/Traits/DashboardIndicateur04Trait.php`
- **Règles Didactiques & Précédents (CRITIQUE)** :
  - **Temps de gestion pure vs Temps pédagogique** : Le suivi individuel des réalisations des apprenants (`RealisationTache`) relève de l'observation et de l'accompagnement pédagogique, donc **exclu du calcul de la charge logistique** (0 min).
  - **Suivi opérationnel** : Évalué au niveau de la tâche du projet (`Tache`), étalonné à **2 min / tâche** (gestion administrative de suivi de session, hors pédagogie).
  - **Capacité horaire formateur** : Référentiel de **26 heures par semaine** (et non 35h).
  - **Durée de session & Plafonds** :
    - Calculé par **Session** : le nombre de semaines est calculé depuis `date_debut` et `date_fin` de l'affectation (`AffectationProjet`). En l'absence de dates, la valeur par défaut est de **2 semaines**.
    - Les plafonds didactiques sont proportionnels à la durée de la session : $\text{Plafond cible} = N_{\text{semaines}} \times 3\text{h}$ (ex : $2 \times 3\text{h} = 6\text{h}$ pour une session de 2 semaines).
    - Capacité totale formateur sur la session : $N_{\text{semaines}} \times 26\text{h}$ (ex : $2 \times 26\text{h} = 52\text{h}$).
    - Disponibilité tutorat direct : $\frac{\text{Capacité} - \text{Charge logistique}}{\text{Capacité}} \times 100\%$.
  - **Barème unitaire par acte (Décompté par Tâche, gestion administrative hors pédagogie)** :
    - Création Session / Projet : 15 min
    - Mobilisation d'UA : 1 min / UA
    - Conception de tâche : 3 min / tâche
    - Affectation au groupe : 3 min / affectation
    - Suivi administratif : 2 min / tâche créée (gestion hors accompagnement direct)
    - Validation administrative : 1 min / tâche validée dans la session (au niveau de l'entité `Tache`, non par `RealisationTache`)
    - Feedbacks administratifs : 1 min / tâche ayant reçu au moins un feedback (au niveau de l'entité `Tache`, non par `RealisationTache`)
    - Consultation Dashboard/Kanban : 0 min (observation pédagogique sanctuarisée).
    - Bordereaux / Notes : 0 min (automatisé par SoliLMS).
  - **Affichage horaire** : Format lisible et naturel : si $< 60\text{ min}$, affiche `X min` (ex : `6 min`, `26 min`, `0 min` au lieu de `0h06`) ; si $\ge 60\text{ min}$, affiche `XhYY` (ex : `1h01`, `1h51`).
- **Sorties** :
  - Service : `DashboardIndicateur04Trait` (calcul session, formateur et macro responsable).
  - UI V2 : `kpi-charge-logistique.blade.php`, `charge-logistique.blade.php` (analyse détaillée, modale de décompte arithmétique).

### Action G : Réguler la File FIFO et Calculer l'Indicateur 05 ($T_{\text{file}}$)
> **Description** : Expliquer le cycle de vie dans la file FIFO, le calcul du rang pour l'apprenant, la console d'arbitrage 1-à-1 vs mutualisation 4x4 via Discord, et le benchmark des délais d'attente.
- **Ressources Utilisées** :
  - `cahiers-charges/PkgTableauBord/indicateurs/ind-05-delai-file-attente.md`
  - `modules/PkgTableauBord/Services/ParametrePedagogiqueService.php`
- **Règles Didactiques & Précédents (CRITIQUE)** :
  - **Durée de Live Coding par apprenant** : Chaque apprenant passe **entre 40 min et 60 min** en épreuve de Live Coding synchrone sur son prototype ($N2$ - Adapter).
  - **Régulation du goulet d'étranglement (Session Discord 4x4)** :
    - En situation de saturation ($\ge 5$ apprenants en attente ou $T_{\text{file}} > 60\text{ min}$), le formateur active une **session mutualisée 4x4 via Discord**.
    - Les 4 apprenants partagent leur écran en direct simultanément pendant **40 à 60 min** (durée collective équivalente à un créneau standard).
    - Le formateur observe un **écran mosaïque à 4 flux vidéo en direct** : il peut alterner entre une focalisation sur un seul flux précis et une supervision globale des 4 apprenants simultanément.
    - Le débit d'absorption est ainsi quadruplé pour éliminer les temps morts sans dégrader l'exigence d'évaluation.
  - **Paramètres de cadence** :
    - Solo : `file_duree_passage_solo_minutes = 40` ($\approx 40-60\text{ min}$).
    - Session collective : `file_duree_passage_groupe_minutes = 45` ($\approx 40-60\text{ min}$ par vague de 4 apprenants).
    - Cible fluidité : $\le 30\text{ min}$ ; Vigilance : $30-60\text{ min}$ ; Saturation : $> 60\text{ min}$ ou $\ge 5$ en attente.
- **Sorties** :
  - Colonnes `realisation_taches` : `cache_date_entree_file`, `cache_date_debut_live_coding`, `cache_delai_attente_minutes`, `cache_session_live_coding_id`
  - Service : `DashboardIndicateur05Trait` (calculs individuels, console opérationnelle FIFO, projections, et actions AJAX)
  - UI V2 : `kpi-file-attente.blade.php`, `file-attente.blade.php` (analyse détaillée, console FIFO réactive, boutons d'appel solo et session Discord 4x4)

### Action H : Traiter l'Alerte Critique et Calculer l'Indicateur 06 ($T_{\text{intervention}}$)
> **Description** : Expliquer la fin du Live Coding collectif marquée par l'état `A_VALIDER_LIVE_CODING` ou `APPROVED` sur la tâche du démonstrateur (`is_live_coding = true`), le chronomètre d'autonomie (+30 min configurable via `seuil_blocage_minutes`), la bascule automatique par le système des tâches retardataires vers l'état `EN_REMEDIATION`, l'affichage exclusif des apprenants en remédiation sur le tableau de bord (sans action modale ni typologie Trinôme/Atelier/Solo), et le déblocage par le formateur vers la validation de tâche.
- **Ressources Utilisées** :
  - `cahiers-charges/PkgTableauBord/plan-sprint6.md`
- **Règles Didactiques & Précédents (CRITIQUE)** :
  - **Condition d'Existence Live Coding (`is_live_coding = true`)** : Pour qu'une tâche soit éligible à la remédiation, **il DOIT impérativement exister une réalisation de cette tâche ayant `is_live_coding = true`** (l'apprenant qui a fait la démonstration au tableau). Si aucune réalisation d'une tâche n'a `is_live_coding = true`, le système ne doit **JAMAIS** mettre cette tâche en remédiation.
  - **Condition de Fin Démonstrateur** : La réalisation ayant `is_live_coding = true` doit avoir atteint l'état **« À valider Live coding »** (`A_VALIDER_LIVE_CODING`) ou **« Validée »** (`APPROVED` / `VALIDEE`).
  - **Affichage Exclusif** : Sur le Tableau de bord et dans la vue Indicateur 06, on affiche **exclusivement les apprenants qui ont une tâche en état `EN_REMEDIATION`**. Les apprenants autonomes ou déjà validés ne figurent pas dans cette liste d'intervention.
  - **Absence d'Action Modale & Pas de Typologie Fictive** : Le système ne génère pas de façon de remédiation (*"Trinôme"*, *"Atelier"*, *"Solo"*). Il n'y a pas de bouton d'action ni de popup modal pour consigner un type d'étayage.
  - **Workflow Formateur** : Le formateur intervient en direct auprès des apprenants listés en remédiation. Une fois le blocage résolu, le formateur passe l'état de la tâche de `EN_REMEDIATION` à `VALIDEE` (ou `APPROVED`). L'apprenant quitte alors automatiquement la liste des remédiations.
- **Sorties** :
  - Workflow : `WorkflowTache` code `A_VALIDER_LIVE_CODING` (ID 12) et `EN_REMEDIATION` (ID 11)
  - Colonnes `affectation_projets` : `date_fin_live_coding_collectif`, `seuil_blocage_minutes` (défaut 30 min)
  - Service : `DashboardIndicateur06Trait` (détection dynamique $t_0$, `synchroniserApprenantsEnRemediation` par tâche avec `is_live_coding`, calculs $T_{\text{intervention}}$ sur les tâches `EN_REMEDIATION`)
  - UI V2 : `kpi-intervention.blade.php`, `flashbox-alerte-blocages.blade.php`, `intervention.blade.php` (tableau exclusif des stagiaires en remédiation, sans bouton d'action, benchmark inter-sessions)
  - Artisan : `dashboard:simuler-remediations` (analyse et synchronisation des données réelles de BDD)

### Action I : Mesurer la Concordance Docimologique & Intégrité face à l'IA ($r$)
> **Description** : Expliquer le calcul du coefficient de corrélation linéaire de Pearson ($r \in [-1, +1]$) confrontant les notes du prototype surveillé ($N2$) et du projet libre assisté par IA ($N3$), la détection des alertes d'écart suspect ($\Delta_{\text{IA}} > +5\text{ pts}$), le tableau d'audit des soutenances et le Scatter Plot SVG interactif.
- **Ressources Utilisées** :
  - `cahiers-charges/PkgTableauBord/indicateurs/ind-07-integrite-ia-pearson.md`
  - `cahiers-charges/PkgTableauBord/plan-sprint7.md`
- **Sorties** :
  - Index BDD : `idx_rup_ua_note`, `idx_ruproj_ua_note`
  - Service : `DashboardIndicateur07Trait`
  - UI V2 : `kpi-integrite-ia.blade.php`, `scatter-plot-ia.blade.php`, tableau d'audit formateur

### Action J : Évaluer la Couverture Pédagogique Nationale (ICP) & Diagnostiquer les Impasses
> **Description** : Expliquer la modélisation mathématique du taux d'assimilation du programme national (Indicateur de Couverture Pédagogique — $\text{ICP}$), le calcul individuel apprenant, la détection des impasses didactiques ($< 50\%$ de validation dans le groupe), et le benchmark macroscopique multi-promotions.
- **Ressources Utilisées** :
  - `cahiers-charges/PkgTableauBord/indicateurs/ind-08-couverture-pedagogique.md`
  - `cahiers-charges/PkgTableauBord/plan-sprint8.md`
- **Sorties** :
  - Index BDD : `idx_rmc_appr_etat`, `idx_rmc_mc_etat`
  - Service : `DashboardIndicateur08Trait` (`getIndicateurCouvertureDetail`, `getIndicateurCouvertureFormateur`, `getIndicateurCouvertureResponsable`)
  - Route : `admin.PkgTableauBord.indicateurs.couverture` (`IndicateurDetailController@couverture`)
  - UI V2 : `kpi-couverture-pedagogique.blade.php`, `impasses-pedagogiques-panel.blade.php`, `indicateurs/couverture.blade.php` (matrice complète du référentiel, diagnostic impasses, grille individuelle et benchmark)
  - Artisan : `dashboard:simuler-couverture-pedagogique` (mode audit réel par défaut, `--calibrate` optionnel)

### Action K : Mesurer le Taux de Couverture de Planification des UA (TCP-UA) par SessionFormation
> **Description** : Expliquer la modélisation mathématique du Taux de Couverture de Planification des Unités d'Apprentissage ($\text{TCP-UA}$), le calcul de complétude par session de formation, la détection des UA orphelines non planifiées, et le benchmark macroscopique multi-filières.
- **Ressources Utilisées** :
  - `cahiers-charges/PkgTableauBord/indicateurs/ind-8-v2.md`
  - `cahiers-charges/PkgTableauBord/plan-sprint9.md`
- **Sorties** :
  - Tables BDD : `unite_apprentissages`, `session_formations`, `alignement_uas`
  - Service : `DashboardIndicateur09Trait` (`getIndicateurTcpUaDetail`, `getIndicateurTcpUaFormateur`, `getIndicateurTcpUaResponsable`, `getIndicateurTcpUaApprenant`)
  - Route : `admin.PkgTableauBord.indicateurs.tcp-ua` (`IndicateurDetailController@tcpUa`)
  - UI V2 : `kpi-docimologie.blade.php`, `bordereau-officiel-panel.blade.php`, `indicateurs/tcp-ua.blade.php` (matrice complète des UA, sessions déployées, alertes par module et benchmark filières)

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

### Scénario 1 : Calcul de l'amorçage ($T_{\text{amorçage}}$) & Détection des Absences
1. Identifier la date de lancement officiel du projet (`cache_date_lancement` ou `date_debut` de l'affectation).
2. Déterminer la fin de séance de lancement via `ParametrePedagogiqueService::determinerFinSeanceLancement($dateLancement)`.
3. Pour chaque réalisation de projet, extraire l'horodatage exact de mise en cours de la 1ère tâche (`determinerDatePremiereTacheEnCours`).
4. Si la 1ère tâche est passée à `En cours` avant la fin de séance :
   - L'apprenant est considéré comme **présent en séance**.
   - Son délai individuel $T_{\text{amorçage}}(i)$ est calculé et intégré dans le tableau des présents `$delaisPresents[]`.
5. Si la 1ère tâche est passée à `En cours` après la fin de séance :
   - L'apprenant est catégorisé en **"Démarrage avec absence"** (`demarrage_avec_absence`).
   - Son délai est affiché avec la mention *"Hors moyenne (Post-séance)"* mais **strictement exclu de la moyenne collective** du groupe/session.
6. Calculer la moyenne collective sur les présents :
   - Si $\text{count}(\text{présents}) > 0$ : $\overline{T}_{\text{amorçage}} = \text{round}(\sum / \text{count})$, statut comparé au seuil SLA (Optimal $\le 3\text{h}$ vs Alerte $> 3\text{h}$).
   - Si $\text{count}(\text{présents}) == 0$ : $\overline{T}_{\text{amorçage}} = \text{null}$, formaté en `'--'`, statut `'En attente'` (badge neutre `bg-slate-50`).
7. Calculer la médiane globale pour évaluer la dispersion sans distorsion statistique par des valeurs extrêmes.

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
1. **Périmètre strict** : S'applique **exclusivement aux tâches de Prototype / Live Coding de Niveau 2 (N2)** (`isPrototype()` ou `phaseEvaluation.code === 'N2'`). Les tâches d'autres phases (N1, N3) sont exclues de la file et des calculs d'attente.
2. Dès que l'apprenant soumet son prototype N2, la tâche passe à `READY_FOR_LIVE_CODING` et fige `cache_date_entree_file = now()`.
3. L'apprenant voit en temps réel son rang d'attente FIFO (ex: Rang #2, ~40-60 min estimées).
4. Le formateur consulte sa console ordonnée ASC et appelle le suivant, ou active une session mutualisée 4x4 Discord si la file s'accumule (≥ 5 apprenants ou attente > 60 min).
5. Au passage à `IN_LIVE_CODING`, `cache_date_debut_live_coding` est enregistré et `cache_delai_attente_minutes` est calculé (si délai = 0 min, la valeur 0 est conservée).

### Scénario 6 : Détection des Ruptures et Prise en Charge ($T_{\text{intervention}}$)
1. **Condition stricte d'éligibilité par tâche (`is_live_coding = true`)** : Une tâche n'est éligible à la remédiation que si au moins un apprenant a été désigné pour la démonstration collective au tableau (`RealisationTache.is_live_coding = 1`). Si aucune réalisation d'une tâche ne possède `is_live_coding = true`, le système ne doit **JAMAIS** mettre la tâche en remédiation.
2. **Fin du Live Coding collectif au tableau** : L'apprenant démonstrateur (`is_live_coding = true`) passe sa tâche à l'état `A_VALIDER_LIVE_CODING` (ID 12) ou est validé par le formateur (`APPROVED` / `VALIDEE`). Cette transition fige l'horodatage de référence $t_0$. Si le démonstrateur n'a pas encore atteint l'un de ces états, le Live Coding est en cours et aucune remédiation n'est déclenchée.
3. **Fenêtre critique d'autonomie (30 min)** : Les pairs disposent d'un créneau d'autonomie cible de 30 min (`seuil_blocage_minutes = 30` par défaut).
4. **Bascule AUTOMATIQUE en état `EN_REMEDIATION` et Inscription BDD** :
   - Dès que $now > t_0 + 30\,\text{min}$, toute tâche non terminée (`TODO`, `IN_PROGRESS`, `PAUSED`, `REVISION_NECESSAIRE`) des pairs (`is_live_coding = 0`) de cette même tâche est basculée automatiquement vers l'état `EN_REMEDIATION`.
   - **Règle absolue** : Une tâche à l'état **"Non validée" (`NOT_VALIDATED`) ne bascule JAMAIS en remédiation**.
   - Le système insère simultanément une fiche dans la table **`remediations`** avec `est_resolu = false` pour assurer la traçabilité.
5. **Console Formateur & Tableau de Bord Exclusif** :
   - La **Flashbox Formateur** alerte **exclusivement** sur les blocages actifs non résolus (`est_resolu = false` / tâche `EN_REMEDIATION`).
   - La page détaillée affiche deux tables distinctes :
     - **Alertes Actives** : apprenants actuellement bloqués en attente d'aide.
     - **Mémoire Pédagogique (Résolues)** : apprenants passés par la remédiation et désormais validés avec succès par le formateur.
   - Le système ne génère aucune typologie artificielle (*"Trinôme"*, *"Atelier"*, *"Solo"*) et n'affiche aucune modale intrusive au chargement.
6. **Régulation Pédagogique Directe & Résolution (`est_resolu = true`)** :
   - Le formateur travaille directement en classe avec les apprenants bloqués.
   - Une fois l'objectif atteint, le formateur passe la tâche de `EN_REMEDIATION` vers `VALIDEE` (ou `APPROVED`).
   - L'événement déclenche automatiquement la mise à jour de la fiche dans `remediations` (`est_resolu = true`, `updated_at = now()`).
   - L'apprenant quitte la liste active et entre dans l'historique des remédiations résolues.
7. **Calcul Dual des Taux** :
   - **Taux d'Autonomie Initiale** : $\tau_{\text{autonomie}} = \frac{N_{\text{total}} - N_{\text{remédiés}}}{N_{\text{total}}} \times 100$ (pérenne et mémorisé même après validation).
   - **Taux de Résolution des Remédiations** : $\tau_{\text{résolution}} = \frac{N_{\text{résolus}}}{N_{\text{remédiés}}} \times 100$ (atteint 100% quand tous les élèves remédiés sont validés).

### Scénario 7 : Taux de Réalisation Validée par Sprint ($\text{TRVS}$) & Impact du Live Coding
1. **Périmètre & Concept** : Mesurer le taux d'achèvement effectif des projets de sprint arrivés à l'état **`Validé`** (`DONE`), et quantifier empiriquement l'impact didactique de l'instauration des sessions de Live Coding synchrones.
2. **Formule Mathématique Fondamentale** :
   $$\text{TRVS} = \frac{N_{\text{validées}}}{N_{\text{attendues}}} \times 100$$
   - **Règle d'état stricte** : Seuls les projets avec `etats_realisation_projets.code = 'DONE'` sont comptabilisés comme validés ($N_{\text{validées}}$).
   - Les projets en état `TO_APPROVE` (terminés en attente de soutenance/validation) et `IN_PROGRESS` comptent dans $N_{\text{attendues}}$ mais **ne sont pas** validés tant que la soutenance n'a pas validé le livrable.
3. **Mesure d'Impact du Live Coding ($\Delta \text{TRVS}$)** :
   $$\Delta \text{TRVS} = \text{TRVS}_{\text{après}} - \text{TRVS}_{\text{avant}}$$
   - Confrontation entre les sprints de référence avant l'introduction du Live Coding et les sprints déployés avec sessions de Live Coding synchrone.
   - Démontre un gain net mesurable (ex : $+17.5\,\text{pts}$, passage de $62.5\%$ à $80.0\%$).
4. **Évolution Chronologique des Sprints** : Suivi séquentiel sprint par sprint ($S_1 \to S_2 \to S_3\dots$) pour attester de la montée en compétences et de l'accélération de l'achèvement validé.
5. **Vues Dédiées & Ergonomie 100% Inline** :
   - **Formateur** : Carte KPI TRVS (pourcentage et ratio $N_{\text{validées}} / N_{\text{attendues}}$) et tableau des réalisations de sprint avec statuts réels et notes.
   - **Responsable** : Carte KPI macro, comparatif Avant vs Après Live Coding, et matrice chronologique des sprints.
   - **Page Détaillée (`admin.PkgTableauBord.indicateurs.trvs`)** : 4 cartes KPI directes, tableau comparatif méthodologique, frise chronologique des sprints et roster complet des apprenants sous Alpine.js (100% inline, zéro modale).

### Scénario 8 : Couverture & Progression dans le Référentiel National (ICP Temporel)
1. **Périmètre & Concept** : Mesurer la progression réelle de la promotion dans le référentiel de compétences national (`progression_cache`) en la confrontant dynamiquement à la progression idéale attendue (`progression_ideal_cache`).
2. **Éradication des Faux Positifs Temporels** : En début ou cours d'année, un taux brut $< 85\%$ est normal et ne constitue pas une anomalie. Une compétence n'est qualifiée d'**impasse didactique** que si le calendrier a démarré (`progression_ideal_cache >= 25%`) et que le rythme d'acquisition décroche significativement ($\text{rythme} < 60\%$ ou $\text{delta} < -25\,\text{pts}$).
3. **Formule Mathématique du Rythme Temporel** :
   $$\text{Taux Rythme} = \frac{\overline{\text{progression\_cache}}}{\overline{\text{progression\_ideal\_cache}}} \times 100$$
4. **Vues Dédiées & Ergonomie 100% Inline** :
   - **Formateur** : Flashbox des impasses réelles (`impasses-pedagogiques-panel.blade.php`).
   - **Page Détaillée (`admin.PkgTableauBord.indicateurs.couverture`)** : 4 cartes KPI directes, 3 onglets (Compétences Nationales, Micro-Compétences avec état DONE, Grille Individuelle par Apprenant) et benchmark inter-groupes.

### Scénario 9 : Taux de Couverture de Planification Évaluative des UA ($\text{TCP-UA}$) & Alignement des Formations
1. **Périmètre & Concept** : Mesurer la proportion des Unités d'Apprentissage (UA) prévues au programme qui sont couvertes par une planification de formation (`SessionFormation`) comportant les évaluations nécessaires. Indicateur de pilotage préventif en amont pour éviter les lacunes de notes $N2$ au Contrôle Continu en fin d'année.
2. **Formule Mathématique Fondamentale** :
   $$\text{TCP-UA} = \frac{N_{\text{couvertes}}}{N_{\text{attendues}}} \times 100$$
   - **$N_{\text{attendues}}$** : Total des Unités d'Apprentissage obligatoires du programme de formation (rattachées à la filière via `modules` $\to$ `competences` $\to$ `micro_competences` $\to$ `unite_apprentissages`).
   - **$N_{\text{couvertes}}$** : Nombre d'UA distinctes couvertes par au moins une `SessionFormation` via la table `alignement_uas`.
   - **Règle d'Unicité (CRITIQUE)** : Chaque UA est comptabilisée **une seule fois**, même si plusieurs sessions lui sont consacrées (`COUNT(DISTINCT unite_apprentissage_id)`).
   - **$N_{\text{manquantes}}$** : $N_{\text{attendues}} - N_{\text{couvertes}}$ (Unités d'Apprentissage orphelines sans session planifiée).
3. **Multi-Échelles & Ergonomie 100% Inline** :
   - **Apprenant** : Carte KPI affichant la couverture de son programme ($45.3\%$) et le statut de la session active du sprint.
   - **Formateur** : Carte KPI TCP-UA et Panneau d'Alignement alertant sur les UA non planifiées par module pédagogique pour orienter la création des prochaines sessions.
   - **Responsable** : Carte KPI macro établissement ($55.3\%$), benchmark inter-filières (DWB $45.3\%$ vs DMB $78.3\%$) et colonne TCP-UA dans la matrice comparative.
   - **Page Détaillée (`admin.PkgTableauBord.indicateurs.tcp-ua`)** : 4 cartes KPI directes, 4 onglets interactifs (Matrice Complète des UA, Sessions de Formation & UA Mobilisées, Plan d'Action / Alertes par Module, Benchmark Macro Filières) (strictement aucune modale au chargement).


