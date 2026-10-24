# Règle de Gestion : Sélection Live Coding à 50%

Cette règle documente le processus automatisé de sélection d'un apprenant pour un live coding lorsque le taux de réalisation d'une tâche de groupe atteint 50%.

## 1. Description du Processus
La méthode `lancerLiveCodingSiEligible(TacheAffectation $tacheAffectation)` du `TacheAffectationService` orchestre cette logique métier. Elle est généralement appelée lorsque la progression d'une affectation de tâche est mise à jour.

## 2. Conditions d'Éligibilité
Le live coding ne se déclenche que si toutes les conditions suivantes sont remplies :
- La tâche d'origine est paramétrée pour nécessiter un live coding (`$tache->is_live_coding_task == true`).
- Aucun live coding n'est déjà en cours ou planifié pour cette affectation de tâche (`is_live_coding == false` pour toutes les `RealisationTache` de l'affectation).
- La progression globale de l'affectation de tâche (`pourcentage_realisation_cache`) est supérieure ou égale à **50%**.

### Détail du calcul de la progression (50%)
Le pourcentage est calculé par la méthode `mettreAjourTacheProgression` sur la base des règles suivantes :
- **Tâches exclues** : Les apprenants dont la tâche est en pause (`PAUSED`) ou non validée (`NOT_VALIDATED`) ne font pas partie du calcul.
- **Base de calcul (dénominateur)** : Total des apprenants actifs (tâches en cours ou terminées, hors `PAUSED` et `NOT_VALIDATED`).
- **Tâches considérées comme réalisées (numérateur)** : Sont comptées comme réalisées les tâches dans les états : En attente de validation (`TO_APPROVE`), Validées (`APPROVED`), Prêtes pour le Live Coding (`READY_FOR_LIVE_CODING`) et En Live Coding (`IN_LIVE_CODING`).
- **Formule** : `(Tâches réalisées / Tâches actives) * 100`.

## 3. Algorithme de Sélection de l'Apprenant
L'application cherche les apprenants éligibles parmi ceux dont la tâche est en état `"TO_APPROVE"` (En attente de validation).
Parmi ces apprenants éligibles, le système sélectionne celui qui a fait le moins de live codings durant l'année scolaire en cours.
- Calcul de l'année scolaire via `AnneeFormationService::getCurrentAnneeFormation()`.
- Tri ascendant (`sortBy`) basé sur le décompte des `RealisationTache` de l'apprenant ayant `is_live_coding = true` durant l'année.

## 4. Conséquences de la Sélection
Une fois l'apprenant sélectionné, le système met à jour automatiquement :
- **Sur la `RealisationTache` de l'apprenant sélectionné** : 
  - `is_live_coding` est mis à `true`.
  - L'état bascule vers `READY_FOR_LIVE_CODING` (Prêt pour le Live Coding).
- **Sur la `TacheAffectation`** :
  - Le champ `apprenant_live_coding_cache` est renseigné avec un objet JSON contenant les infos de l'apprenant sélectionné, l'ID de sa réalisation et la date.
