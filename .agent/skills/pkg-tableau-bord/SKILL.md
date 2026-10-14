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

### Action H : Traiter l'Alerte Critique et Calculer l'Indicateur 06 ($T_{\text{intervention}}$)
> **Description** : Expliquer le mécanisme d'alerte post-Live Coding (+10 min configurable via `seuil_blocage_minutes`), le calcul du taux de remédiation ($88\%$), l'étayage didactique (individuelle, trinôme d'entraide, atelier de soutien) et la Flashbox Alerte Rouge.
- **Ressources Utilisées** :
  - `cahiers-charges/PkgTableauBord/plan-sprint6.md`
- **Sorties** :
  - Colonnes `affectation_projets` : `date_fin_live_coding_collectif`, `seuil_blocage_minutes`
  - Table : `remediations`
  - Modèle : `Modules\PkgTableauBord\Models\Remediation`
  - Service : `DashboardIndicateur06Trait`
  - UI V2 : `kpi-intervention.blade.php`, `flashbox-alerte-blocages.blade.php`, bandeau bienveillant apprenant

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
  - Service : `DashboardIndicateur08Trait`
  - UI V2 : `kpi-couverture-pedagogique.blade.php`, `impasses-pedagogiques-panel.blade.php`
  - Artisan : `dashboard:simuler-couverture-pedagogique`

### Action K : Appliquer la Règle Docimologique 65/35 & Sceller les Bordereaux Officiels
> **Description** : Expliquer l'algorithme officiel de calcul de la note CC (sur 20) et de la note EFM (sur 40) selon la pondération 65% Prototype + 35% Projet, le contrôle de complétude 100%, le scellement officiel par empreinte SHA-256 et la génération des bordereaux officiels.
- **Ressources Utilisées** :
  - `cahiers-charges/PkgTableauBord/indicateurs/ind-09-conformite-conversion-65-35.md`
  - `cahiers-charges/PkgTableauBord/plan-sprint9.md`
- **Sorties** :
  - Colonnes BDD : `note_cc_officielle`, `note_efm_officielle`, `statut_docimologique` sur `realisation_modules`
  - Table : `bordereaux_officiels`
  - Modèle : `Modules\PkgTableauBord\Models\BordereauxOfficiel`
  - Service : `DashboardIndicateur09Trait`
  - UI V2 : `kpi-docimologie.blade.php`, `bordereau-officiel-panel.blade.php`
  - Artisan : `dashboard:simuler-docimologie`

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

### Scénario 6 : Détection des Ruptures et Prise en Charge ($T_{\text{intervention}}$)
1. Le formateur clôture le Live Coding collectif (`date_fin_live_coding_collectif = now()`).
2. Dès que le temps écoulé dépasse le seuil paramétré de l'affectation (`seuil_blocage_minutes`, défaut 10 min), les apprenants n'ayant pas encore soumis leur livrable de tutoriel sont flagués en rupture.
3. Le formateur voit apparaître la **Flashbox Alerte Rouge** avec les apprenants bloqués (`+14 min`, `+11 min`) et déclenche un étayage (Trinôme d'entraide ou Atelier de soutien).
4. Dès l'intervention enregistrée dans `remediations`, le taux $T_{\text{intervention}}$ augmente ($\ge 85\%$ = Excellence).
5. L'apprenant concerné voit un message bienveillant d'accompagnement l'invitant à solliciter son formateur et ses pairs.

### Scénario 7 : Audit de Concordance Docimologique & Intégrité IA ($r$)
1. Les notes de prototype ($N2$) et de projet libre ($N3$) sont saisies sur 20.
2. `DashboardIndicateur07Trait` calcule le coefficient de Pearson $r$ sur la promotion et évalue la zone ($\ge 0.70$ = Alignement Robuste).
3. Pour chaque apprenant, l'écart $\Delta_{\text{IA}} = N3 - N2$ est calculé.
4. Si $\Delta_{\text{IA}} > 5.0\text{ pts}$, une alerte rouge s'affiche dans le tableau des soutenances du formateur (*"Vérifier maîtrise du code en soutenance"*).
5. La vue Responsable trace le nuage de points interactif SVG (Scatter Plot) avec polygone d'anomalie rouge et bouton de convocation de jury.

### Scénario 8 : Couverture Pédagogique du Référentiel National ($\text{ICP}$)
1. Le calcul agrège l'ensemble des validations des micro-compétences nationales sur la table optimisée `realisation_micro_competences`.
2. L'apprenant suit sa progression personnelle : $C_{\text{validées}} / C_{\text{prescrites}}$ et sa jauge d'assimilation (ex: $85\%$).
3. Le formateur analyse l'$\text{ICP}$ collectif du groupe ($\text{ICP} = \frac{\sum C_{\text{validées}}}{N \times C_{\text{référentiel}}} \times 100$) et consulte le **Diagnostic des Impasses Didactiques** identifiant toute compétence à $< 50\%$ de validation pour planifier des ateliers de remédiation ciblés.
4. Le responsable supervise le benchmark macroscopique inter-promotions comparant la conformité face au seuil national ($\ge 85\%$) dans la matrice comparative.

### Scénario 9 : Règle Docimologique 65/35 & Scellement des Bordereaux
1. Les notes des prototypes ($N2$) et du projet libre ($N3$) sont combinées pour calculer :
   - $\text{Note CC} = \overline{N2} \in [0, 20]$ (Contrôle Continu).
   - $\text{Note EFM} = 2 \times (0{,}65 \times \text{Note CC} + 0{,}35 \times N3) \in [0, 40]$ (Examen Fin de Module).
2. L'apprenant consulte sa moyenne provisoire de CC et sa note sommative estimée dans un cadre de transparence totale.
3. Le formateur vérifie la complétude de sa classe ($100\%$ requis avant scellement). Dès que tous les apprenants sont notés, il actionne le scellement officiel qui horodate et génère l'empreinte cryptographique SHA-256 dans `bordereaux_officiels`.
4. Le responsable de formation s'assure que le taux de conformité atteint $100\%$ ($\tau_{\text{conformité}} = 100\%$, zéro divergence arithmétique) et télécharge les bordereaux institutionnels certifiés pour le jury et l'administration.


