# Spécification Indicateur 03 : Fréquence Moyenne des Boucles de Révision ($F_{\text{révision}}$)

## 📌 1. Fiche d'Identité de l'Indicateur

- **Code / Symbole** : $F_{\text{révision}}$
- **Nom complet** : Fréquence moyenne des boucles de révision par tâche
- **Axe de Recherche** : Axe 1 — Apprenant (Hypothèse H1 : Réduction de la démarche tâtonnante, clarté des consignes et qualité initiale du livrable)
- **Acteurs Cibles** :
  - **Apprenant** (auto-évaluation de la rigueur de son code avant soumission)
  - **Formateur** (diagnostic de la clarté des consignes et identification des tâches piégeuses)
  - **Responsable** (qualité pédagogique des contenus et harmonisation des critères de validation)
- **Unité de mesure** : Nombre moyen d'itérations / rejets par tâche ($F_{\text{révision}} \ge 0$)
- **Périmètre Didactique** : Ensemble des tâches évaluées d'un module ou d'une Unité d'Apprentissage (UA).

---

## 🧮 2. Modélisation Mathématique & Formules d'Agrégation

### 2.1. Formule Unitaire (Niveau Tâche $k$)
Pour chaque tâche réalisée $k$, $C_{\text{rejets}}(k)$ comptabilise le nombre total de renvois vers le statut *Révision nécessaire* (`REVISION_NECESSAIRE`) prononcés par le formateur avant la validation finale :

$$C_{\text{rejets}}(k) \in \mathbb{N}$$

- $C_{\text{rejets}}(k) = 0$ : Tâche acceptée dès la première soumission.
- $C_{\text{rejets}}(k) \ge 1$ : Tâche ayant nécessité un ou plusieurs cycles de reprise.

### 2.2. Formule Individuelle (Niveau Micro — Apprenant $i$)
Moyenne des itérations de reprise pour un apprenant $i$ sur l'ensemble de ses $K_i$ tâches soumises :

$$F_{\text{révision}}(i) = \frac{1}{K_i} \sum_{k=1}^{K_i} C_{\text{rejets}}(k, i)$$

### 2.3. Formule Collective (Niveau Méso — Groupe / Promotion)
Moyenne arithmétique de la cohorte sur un module comportant $K$ tâches évaluées au total :

$$F_{\text{révision}} = \frac{1}{K} \sum_{k=1}^{K} C_{\text{rejets}}(k)$$

- $K$ : Nombre total de tâches ayant fait l'objet d'au moins une évaluation par le formateur.
- Taux de soumission conforme du 1er coup : $\tau_{\text{conforme\_1er\_jet}} = \frac{K_{(C_{\text{rejets}}=0)}}{K} \times 100$.

### 2.4. Formule Établissement (Niveau Macro — Gouvernance)
Moyenne des itérations par module ou filière, permettant de cartographier les modules sources de surcharge cognitive :

$$F_{\text{révision\_macro}} = \frac{\sum_{m=1}^{M} \text{Total Rejets}(m)}{\sum_{m=1}^{M} \text{Total Tâches Évaluées}(m)}$$

---

## 🔍 3. Audit de l'Existant dans Solicode LMS (Skills Métier)

L'audit croisé mené via `pkg-realisation-tache` et `app-db-savoir` établit les points suivants :

### 3.1. Ce qui existe déjà dans SoliLMS :
- **Statut dédié dans le Workflow** :
  - La table `workflow_taches` possède l'état officiel `REVISION_NECESSAIRE` (titre *"Révision nécessaire"*, couleur *info*, ordre $4$).
  - La méthode `WorkflowTacheService::getOrCreateWorkflowRevision()` garantit l'existence de ce workflow pour chaque formateur.
- **Journalisation dans l'Historique** :
  - La table `historique_realisation_taches` stocke chaque transition d'état avec `realisation_tache_id`, `changement`, `dateModification` et `user_id`.

### 3.2. Écarts constatés (Ce qui manque pour calculer l'indicateur) :
1. **Absence de compteur direct de rejets sur `realisation_taches`** :
   - Pour connaître $C_{\text{rejets}}(k)$, il faut actuellement analyser les chaînes de caractères de la table `historique_realisation_taches` (`WHERE changement LIKE '%REVISION_NECESSAIRE%'`).
   - Cette requête textuelle est trop lourde pour être exécutée dynamiquement sur un tableau de bord affichant des dizaines d'apprenants et des centaines de tâches.
2. **Absence de distinction entre itération corrective et simple modification d'édition** :
   - Seule une transition formelle de l'état vers `REVISION_NECESSAIRE` doit incrémenter le compteur.

---

## 🛠️ 4. Modifications à Apporter à Solicode LMS

### 4.1. Modifications Base de Données (Incluses dans le Sprint 1)

1. **Table `realisation_taches`** :
   - Ajouter la colonne `nombre_revisions` (`int unsigned DEFAULT 0`) :
     - Compteur direct, incrémenté automatiquement à chaque transition vers l'état `REVISION_NECESSAIRE`.
     - Permet une lecture $O(1)$ par tâche et une agrégation SQL `AVG(nombre_revisions)` ultra-rapide.

### 4.2. Modifications Applicatives (Services & Hooks)

1. **Hook dans `RealisationTacheCrudTrait` (Module `PkgRealisationTache`)** :
   - Dans `beforeUpdateRules(&$data, $id)` :
     ```php
     if (isset($data['etat_realisation_tache_id'])) {
         $nouvelEtat = EtatRealisationTache::find($data['etat_realisation_tache_id']);
         if ($nouvelEtat?->workflowTache?->code === 'REVISION_NECESSAIRE') {
             $realisation = RealisationTache::find($id);
             // On incrémente si l'état précédent n'était pas déjà REVISION_NECESSAIRE
             if ($realisation && $realisation->etatRealisationTache?->workflowTache?->code !== 'REVISION_NECESSAIRE') {
                 $data['nombre_revisions'] = ($realisation->nombre_revisions ?? 0) + 1;
             }
         }
     }
     ```

2. **Service de Calcul dans `PkgTableauBord` (`DashboardCalculTrait`)** :
   - Méthode `calculerFrequenceRevision(int $affectationProjetId, ?int $tacheId = null): array` :
     ```php
     $query = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectationProjetId))
         ->whereHas('etatRealisationTache.workflowTache', fn($q) => 
             $q->whereIn('code', ['APPROVED', 'NOT_VALIDATED', 'REVISION_NECESSAIRE'])
         );

     if ($tacheId) {
         $query->where('tache_id', $tacheId);
     }

     $totalTaches = $query->count();
     if ($totalTaches === 0) {
         return ['frequence' => 0.0, 'total_rejets' => 0, 'taux_premier_jet' => 100.0];
     }

     $totalRejets = $query->sum('nombre_revisions');
     $sansRevision = $query->where('nombre_revisions', 0)->count();

     return [
         'frequence' => round($totalRejets / $totalTaches, 2),
         'total_rejets' => $totalRejets,
         'taux_premier_jet' => round(($sansRevision / $totalTaches) * 100, 1),
     ];
     ```

---

## 🚦 5. Seuils d'Alerte & Actionnabilité Didactique

| Zone | Seuil $F_{\text{révision}}$ | Diagnostic Didactique | Action Dashboard Immédiate |
| :--- | :---: | :--- | :--- |
| 🟢 **Optimal** | $< 0{,}5$ itération | Consignes limpides, critères d'acceptation maîtrisés dès la 1ère soumission. | Badge vert *"Qualité initiale élevée"*. |
| 🟡 **Vigilance** | $0{,}5 \le F \le 1{,}2$ itérations | Ajustements ordinaires du code, démarche d'apprentissage saine. | Badge bleu/neutre. |
| 🔴 **Alerte Tâtonnement** | $> 1{,}2$ itérations | **Surcharge extrinsèque ou flou des critères** : l'apprenant procède par essais-erreurs ou soumet du code inachevé. | **Action Formateur** : Repérer la tâche avec le plus fort taux de rejet et lancer un point de clarification collectif. |

---

## 📊 6. Rendu UI Prévu dans les Maquettes

- **Vue Apprenant (`dashboard-apprenant.html`)** :
  - Sur chaque carte tâche : Badge discret *"Révisions : 0"* (Vert) ou *"Révision 2/2"* (Orange).
  - Indicateur personnel : *"Taux de validation au 1er jet : 85%"*.
- **Vue Formateur (`dashboard-formateur.html`)** :
  - Carte KPI : *"Fréquence de Révision : 0.8 itér./tâche"* avec sous-texte *"68% des tâches validées sans reprise"*.
  - Alerte "Top Tâches Révélateuses" : liste des 3 tâches du sprint concentrant le plus grand nombre de rejets formateurs.
- **Vue Responsable (`dashboard-responsable.html`)** :
  - Tableau croisé des modules montrant les coefficients de révision moyens afin d'identifier les cours nécessitant une réécriture des consignes pédagogiques.
