# Spécification Indicateur 05 : Délai Moyen de Traitement de la File d'Attente ($T_{\text{file}}$)

## 📌 1. Fiche d'Identité de l'Indicateur

- **Code / Symbole** : $T_{\text{file}}$
- **Nom complet** : Délai moyen d'attente avant prise en charge en Live Coding Prototype
- **Axe de Recherche** : Axe 2 — Formateur (Hypothèse H2 : Régulation équitable du flux d'évaluation, suppression des interruptions et élimination des temps morts)
- **Acteurs Cibles** :
  - **Apprenant** (visibilité sur son rang d'attente dans la file FIFO et estimation du passage)
  - **Formateur** (gestion de la cadence d'évaluation, arbitrage entre passage individuel et session groupée)
  - **Responsable** (dimensionnement des créneaux d'évaluation et prévention des goulets d'étranglement)
- **Unité de mesure** : Minutes ($\text{min}$)
- **Périmètre Didactique** : Épreuve synchrone bloquante de Live Coding sur Prototype (Niveau 2 — *Adapter*). S'applique exclusivement au passage au statut *Prêt pour Live Coding* (`READY_FOR_LIVE_CODING`).

---

## 🧮 2. Modélisation Mathématique & Formules d'Agrégation

### 2.1. Formule Unitaire (Niveau Apprenant / Passage $j$)
Temps d'attente individuel en minutes entre l'arrivée du prototype dans la file d'attente et le démarrage effectif de la session de Live Coding par le formateur :

$$T_{\text{file}}(j) = \frac{t_{\text{début\_évaluation\_live\_coding}}(j) - t_{\text{soumission\_prototype}}(j)}{60}$$

- $t_{\text{soumission\_prototype}}(j)$ : Horodatage d'arrivée de la tâche au statut `READY_FOR_LIVE_CODING`.
- $t_{\text{début\_évaluation\_live\_coding}}(j)$ : Horodatage de transition vers le statut `IN_LIVE_CODING`.

### 2.2. Formule Collective (Niveau Méso — Promotion / Sprint)
Moyenne arithmétique pour l'ensemble des $M$ sessions de Live Coding conduites sur le prototype :

$$T_{\text{file}} = \frac{1}{M} \sum_{j=1}^{M} \left( t_{\text{début\_évaluation\_live\_coding}}(j) - t_{\text{soumission\_prototype}}(j) \right)$$

- File active instantanée : $L_{\text{file}} = \text{COUNT}(\text{tâches au statut } \text{READY\_FOR\_LIVE\_CODING})$.
- Temps d'attente estimé pour le dernier inscrit : $T_{\text{estime}} = L_{\text{file}} \times \Delta t_{\text{moyen\_session}}$ (avec $\Delta t \approx 40\,\text{min}$ en solo ou $12\,\text{min}$ en groupe de 4).

### 2.3. Formule Établissement (Niveau Macro — Gouvernance)
Moyenne générale des délais d'attente par groupe pour détecter les formateurs en situation de surcharge ponctuelle.

---

## 🔍 3. Audit de l'Existant dans Solicode LMS (Skills Métier)

L'audit croisé mené via `pkg-realisation-tache` et `app-db-savoir` établit les points suivants :

### 3.1. Ce qui existe déjà dans SoliLMS :
- **États dédiés au Live Coding dans `workflow_taches`** :
  - `READY_FOR_LIVE_CODING` (ordre 5, couleur *primary*, titre *"Prêt pour Live Coding"*).
  - `IN_LIVE_CODING` (ordre 6, couleur *indigo*, titre *"En Live Coding"*).
- **Règles d'attribution automatique** :
  - `TacheAffectationService` contient déjà la logique de détection automatique des tâches éligibles au Live Coding.

### 3.2. Écarts constatés (Ce qui manque pour calculer l'indicateur) :
1. **Absence d'horodatages spécifiques d'entrée et de prise en charge** :
   - Actuellement, la table `realisation_taches` ne stocke pas `date_entree_file` ni `date_debut_live_coding`.
   - L'historique enregistre les transitions, mais le calcul du temps d'attente en temps réel pour animer un composant dynamique (file FIFO) nécessite des colonnes directes indexées.
2. **Absence de traçabilité du mode de passage (Solo vs Groupe de 4)** :
   - Pour ajuster le calcul de la cadence, il est nécessaire de savoir si un passage a été conduit en tête-à-tête (40 min) ou mutualisé (session de 4 apprenants simultanés).

---

## 🛠️ 4. Modifications à Apporter à Solicode LMS

### 4.1. Modifications Base de Données (Incluses dans le Sprint 1)

1. **Table `realisation_taches`** :
   - Ajouter `date_entree_file` (`timestamp NULL`, indexé) :
     - Renseigné automatiquement dès que la tâche passe au statut `READY_FOR_LIVE_CODING`.
     - Sert de clé de tri pour l'ordre FIFO strict : `ORDER BY date_entree_file ASC`.
   - Ajouter `date_debut_live_coding` (`timestamp NULL`) :
     - Renseigné dès que le formateur clique sur *"Démarrer Live Coding"* (passage à `IN_LIVE_CODING`).
   - Ajouter `delai_attente_minutes` (`int unsigned NULL`) :
     - Différence en minutes calculée et stockée lors du passage à `IN_LIVE_CODING`.
   - Ajouter `session_live_coding_id` (`bigint unsigned NULL`) :
     - Permet de regrouper jusqu'à 4 apprenants dans la même session d'évaluation collective.

### 4.2. Modifications Applicatives (Services & Contrôleurs)

1. **Hook dans `RealisationTacheCrudTrait` (Module `PkgRealisationTache`)** :
   - Lors de la transition vers `READY_FOR_LIVE_CODING` :
     ```php
     if ($nouveauCodeWorkflow === 'READY_FOR_LIVE_CODING') {
         $data['date_entree_file'] = now();
     }
     ```
   - Lors de la transition vers `IN_LIVE_CODING` :
     ```php
     if ($nouveauCodeWorkflow === 'IN_LIVE_CODING') {
         $data['date_debut_live_coding'] = now();
         if ($realisation && $realisation->date_entree_file) {
             $data['delai_attente_minutes'] = Carbon::parse($realisation->date_entree_file)->diffInMinutes(now());
         }
     }
     ```

2. **Actions de Pilotage dans `TableauBordService` (Module `PkgTableauBord`)** :
   - `demarrerLiveCodingSolo(int $realisationTacheId)` : Passe la tâche à `IN_LIVE_CODING`.
   - `demarrerLiveCodingGroupe(array $realisationTacheIds)` : Associe jusqu'à 4 tâches à un même `session_live_coding_id` et les passe simultanément à `IN_LIVE_CODING`.

---

## 🚦 5. Seuils d'Alerte & Actionnabilité Didactique

| Zone | Seuil $T_{\text{file}}$ | Diagnostic Didactique | Action Dashboard Immédiate |
| :--- | :---: | :--- | :--- |
| 🟢 **Fluide** | $\le 30\,\text{min}$ | Cadence d'évaluation optimale, temps d'attente minime. | Badge vert *"File fluide"*. |
| 🟡 **Vigilance** | $30\,\text{min} < T \le 60\,\text{min}$ | File en cours d'accumulation. Les créneaux individuels peinent à absorber le flux. | Badge orange. Notification de suggestion de passage groupé. |
| 🔴 **Saturation** | $> 60\,\text{min}$ ou $\ge 5$ en attente | **Goulet d'étranglement critique** : risque d'inactivité et de découragement des apprenants en fin de file. | **Action Formateur Déclenchée** : Bouton d'urgence *"Activer session mutualisée (4 apprenants simultanés)"*. |

---

## 📊 6. Rendu UI Prévu dans les Maquettes

- **Vue Apprenant (`dashboard-apprenant.html`)** :
  - Composant d'attente FIFO :
    - *"Vous êtes 2ème dans la file d'attente Live Coding"*.
    - Timer dynamique : *"En attente depuis 18 minutes — Passage estimé à 14h45"*.
- **Vue Formateur (`dashboard-formateur.html`)** :
  - Carte KPI : *"Délai Moyen File : 28 min"* | *"4 apprenants en attente"*.
  - Console FIFO interactive :
    - Liste ordonnée par `date_entree_file ASC`.
    - Bouton principal : *"Appeler [Nom de l'Apprenant]"* (Mode 1-à-1).
    - Bouton alternatif : *"Sélectionner les 4 premiers et lancer session mutualisée"* (Mode groupé).
- **Vue Responsable (`dashboard-responsable.html`)** :
  - Benchmark des délais d'attente par groupe pour réajuster le calendrier des évaluations des modules.
