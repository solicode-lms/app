# Spécification Indicateur 01 : Temps d'Amorçage Moyen ($T_{\text{amorçage}}$)

## 📌 1. Fiche d'Identité de l'Indicateur

- **Code / Symbole** : $T_{\text{amorçage}}$
- **Nom complet** : Temps d'amorçage moyen d'entrée en production
- **Axe de Recherche** : Axe 1 — Apprenant (Hypothèse H1 : Allègement mental, réduction du syndrome de la page blanche et mise en action rapide)
- **Acteurs Cibles** : 
  - **Apprenant** (auto-régulation, positionnement vs rythme de classe)
  - **Formateur** (détection immédiate des apprenants bloqués à l'amorçage)
  - **Responsable** (évaluation de la fluidité pédagogique inter-groupes)
- **Unité de mesure** : Heures décimales ($h$)
- **Périmètre Didactique** : Premier tutoriel du sprint / de l'Unité d'Apprentissage (Niveau 1 — *Imiter*).

---

## 🧮 2. Modélisation Mathématique & Formules d'Agrégation

### 2.1. Formule Individuelle (Niveau Micro — Apprenant $i$)
Le temps d'amorçage d'un apprenant $i$ est le délai écoulé entre l'horodatage d'ouverture du sprint ($t_{\text{ouverture\_sprint}}$) et le moment précis où l'apprenant soumet son premier livrable de tutoriel ($t_{\text{première\_soumission}}(i)$) vers la colonne *À valider* (`TO_APPROVE`) :

$$T_{\text{amorçage}}(i) = \frac{t_{\text{première\_soumission}}(i) - t_{\text{ouverture\_sprint}}}{3600}$$

*(Résultat converti en heures décimales)*

### 2.2. Formule Collective (Niveau Méso — Groupe / Promotion)
Moyenne arithmétique pour la cohorte des $N$ apprenants ayant démarré le sprint :

$$\overline{T_{\text{amorçage}}} = \frac{1}{N} \sum_{i=1}^{N} \left( t_{\text{première\_soumission}}(i) - t_{\text{ouverture\_sprint}} \right)$$

- $N$ : Nombre total d'apprenants de la cohorte ayant soumis leur première tâche.
- Taux d'amorçage de la classe : $\tau_{\text{amorce}} = \frac{N_{\text{amorcés}}}{N_{\text{total\_inscrits}}} \times 100$.

### 2.3. Formule Établissement (Niveau Macro — Gouvernance)
Moyenne pondérée des temps d'amorçage sur l'ensemble des groupes d'une filière ou d'un centre :

$$T_{\text{amorçage\_macro}} = \frac{1}{\sum_{g=1}^{G} N_g} \sum_{g=1}^{G} \left( N_g \times \overline{T_{\text{amorçage}}}(g) \right)$$

---

## 🔍 3. Audit de l'Existant dans Solicode LMS (Skills Métier)

L'audit croisé réalisé via `pkg-realisation-tache`, `pkg-creation-projet` et `app-db-savoir` établit l'état des lieux suivant :

### 3.1. Ce qui existe déjà dans SoliLMS :
- **Identification des tâches de tutoriel (Niveau 1)** :
  - Dans `PkgCreationProjet`, la mobilisation d'une UA génère des tâches d'imitation (code préfixé par `TUTO-` avec le flag `is_imitation_ua = true` dans `chapitres`, ou ordre de tâche $1$ dans la phase d'apprentissage).
- **Statut de soumission de la tâche** :
  - La table `workflow_taches` possède l'état `TO_APPROVE` (titre *"À valider"*, ordre $7$, couleur *warning*), correspondant à la colonne Kanban *"En validation"*.
- **Traçabilité des affectations** :
  - La table `affectation_projets` lie le projet au groupe (`groupe_id`, `projet_id`).
  - La table `realisation_taches` relie la tâche à l'apprenant (`realisation_projet_id` $\rightarrow$ `apprenant_id`).

### 3.2. Écarts constatés (Ce qui manque pour calculer l'indicateur) :
1. **Horodatage d'activation du sprint imprécis** :
   - `affectation_projets.date_debut` est actuellement un champ de type `date` (sans heure/minute/seconde) ou une date planifiée à l'avance, et non l'instant précis où le formateur ouvre effectivement le sprint en classe.
2. **Absence d'horodatage direct de soumission sur `realisation_taches`** :
   - `realisation_taches.dateFin` est renseignée lors de la clôture/validation finale par le formateur (`APPROVED`), et non au moment du dépôt par l'apprenant vers `TO_APPROVE`.
   - Seul l'`historique_realisation_taches` contient un log d'événement, mais son extraction pour des tableaux de bord en temps réel nécessiterait des requêtes SQL lourdes et lentes.

---

## 🛠️ 4. Modifications à Apporter à Solicode LMS

Pour rendre le calcul de $T_{\text{amorçage}}$ performant, fiable et instantané dans le tableau de bord, les adaptations suivantes sont requises :

### 4.1. Modifications Base de Données (Incluses dans le Sprint 1)

1. **Table `affectation_projets`** :
   - Ajouter la colonne `date_lancement` (`timestamp NULL`, indexé) :
     - Horodatage exact de l'activation en direct du sprint par le formateur.
     - Si non renseigné, fallback sur `created_at` de l'affectation.

2. **Table `realisation_taches`** :
   - Ajouter la colonne `date_premiere_soumission` (`timestamp NULL`) :
     - Enregistre automatiquement la date et l'heure de la toute première transition vers le statut `TO_APPROVE`.
     - Ce champ reste figé une fois écrit (il n'est pas écrasé lors des boucles de révision ultérieures).

### 4.2. Modifications Applicatives (Services & Hooks)

1. **Hook dans `RealisationTacheCrudTrait` (Module `PkgRealisationTache`)** :
   - Dans `beforeUpdateRules(&$data, $id)` :
     ```php
     // Détection du premier passage à l'état TO_APPROVE
     if (isset($data['etat_realisation_tache_id'])) {
         $nouvelEtat = EtatRealisationTache::find($data['etat_realisation_tache_id']);
         if ($nouvelEtat?->workflowTache?->code === 'TO_APPROVE') {
             $realisation = RealisationTache::find($id);
             if (is_null($realisation->date_premiere_soumission)) {
                 $data['date_premiere_soumission'] = now();
             }
         }
     }
     ```

2. **Action Formateur dans `AffectationProjetService` (Module `PkgCreationProjet`)** :
   - Ajouter une méthode `lancerSprint(int $affectationProjetId)` qui passe `date_lancement = now()`.

3. **Service de Calcul dans `PkgTableauBord` (`DashboardCalculTrait`)** :
   - Méthode `calculerTempsAmorcageMoyen(int $affectationProjetId): float` :
     ```php
     $affectation = AffectationProjet::findOrFail($affectationProjetId);
     $tDebut = Carbon::parse($affectation->date_lancement ?? $affectation->date_debut);
     
     // Récupération de la première tâche de tutoriel (Niveau 1)
     $premiereTacheId = $affectation->projet->taches()
         ->whereHas('chapitre', fn($q) => $q->where('is_imitation_ua', true))
         ->orderBy('ordre', 'asc')
         ->value('id');

     $soumissions = RealisationTache::where('tache_id', $premiereTacheId)
         ->whereNotNull('date_premiere_soumission')
         ->whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectationProjetId))
         ->pluck('date_premiere_soumission');

     if ($soumissions->isEmpty()) {
         return 0.0;
     }

     $totalHeures = $soumissions->sum(fn($date) => Carbon::parse($date)->diffInMinutes($tDebut) / 60.0);
     return round($totalHeures / $soumissions->count(), 2);
     ```

---

## 🚦 5. Seuils d'Alerte & Actionnabilité Didactique

| Zone | Seuil $T_{\text{amorçage}}$ | Interprétation Didactique | Action Dashboard Immédiate |
| :--- | :---: | :--- | :--- |
| 🟢 **Optimal** | $\le 1{,}5\,h$ | Démarrage fluide, appropriation immédiate du tutoriel. | Badge vert *"Amorçage rapide"*. |
| 🟡 **Vigilance** | $1{,}5\,h < T \le 3{,}0\,h$ | Léger ralentissement (configuration d'environnement, hésitation). | Badge orange, recommandation de monitoring discret. |
| 🔴 **Alerte Décrochage** | $> 3{,}0\,h$ ou non soumis | Syndrome de la page blanche ou blocage technique initial. | **Action Formateur** : Alerte visuelle + bouton *"Déclencher assistance"* pour assigner un tuteur/pair. |

---

## 📊 6. Rendu UI Prévu dans les Maquettes

- **Vue Apprenant** :
  - Carte KPI : *"Mon Temps d'Amorçage : 1h15"* avec sous-texte *"Moyenne promotion : 2h10 (Vous êtes dans les temps)"*.
- **Vue Formateur** :
  - Carte KPI principale : *"Temps d'Amorçage Moyen : 1h45"*.
  - Compteur d'amorce : *"24/28 apprenants ont amorcé la production"*.
  - Liste d'alerte : 4 apprenants non amorcés avec bouton d'appel direct.
- **Vue Responsable** :
  - Graphique comparatif inter-groupes du délai moyen d'amorçage pour évaluer l'efficacité des briefs de démarrage.
