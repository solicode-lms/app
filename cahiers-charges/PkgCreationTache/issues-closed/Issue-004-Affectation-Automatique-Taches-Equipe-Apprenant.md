# Issue : Affectation automatique des tâches d'équipe lors de l'ajout d'un apprenant

**Titre** : Initialiser automatiquement les réalisations de tâches spécifiques d'une équipe lorsqu'un apprenant y est rattaché

---

## 📝 Contexte & Description du Problème

Dans le cadre des projets collaboratifs gérés via les modules **PkgCreationProjet** et **PkgRealisationTache**, un projet peut comporter des équipes (`EquipeProjet`). Des tâches spécifiques peuvent être directement assignées à une équipe donnée (`taches.equipe_projet_id`).

Lors de la création initiale d'une tâche d'équipe, le système instancie les réalisations (`RealisationTache`) uniquement pour les apprenants déjà rattachés à cette équipe (`TacheRelationsTrait::creerRealisationsTache`).

Cependant, **lorsqu'un apprenant est ajouté à une équipe ultérieurement** (ex: ajout d'un membre en cours de projet ou constitution tardive des équipes) :
- Les réalisations des tâches spécifiques de l'équipe ne sont pas créées pour ce nouvel apprenant.
- L'apprenant ne voit pas ces tâches dans son tableau de bord, son kanban ou sa liste des tâches à réaliser.
- Le formateur ne peut ni suivre ni évaluer le travail de cet apprenant sur les tâches d'équipe.

---

## 💡 Proposition de Solution & Règles Métier

### 1. Déclencheur & Workflow Métier

Lorsqu'un ou plusieurs apprenants sont affectés à une équipe (`EquipeProjet`) :
1. **Détection** : Intercepter l'ajout d'apprenants à l'équipe (hook `afterUpdateRules` ou synchronisation ManyToMany dans `EquipeProjetService`).
2. **Vérification** :
   - Vérifier si l'équipe dispose de tâches spécifiques (`$equipeProjet->taches`).
   - Pour chaque nouvel apprenant rattaché, identifier sa `RealisationProjet` correspondant au projet de l'équipe (via son `AffectationProjet`).
3. **Affectation automatique (Idempotente)** :
   - Pour chaque tâche spécifique de l'équipe :
     - Vérifier si une `RealisationTache` existe déjà pour ce couple (tâche, réalisationProjet).
     - Si elle n'existe pas, appeler `RealisationTacheService::initialiserRealisationTache($tache, $realisationProjet, $etatInitial)` pour instancier la réalisation et les évaluations éventuelles.

---

### 2. Logique Technique Proposée

Dans `EquipeProjetService` (ou un trait dédié `EquipeProjetActionsTrait`) :

```php
/**
 * Synchronise les tâches spécifiques de l'équipe pour les nouveaux apprenants membres.
 *
 * @param EquipeProjet $equipeProjet
 * @param array $nouvelApprenantIds
 * @return void
 */
public function affecterTachesEquipeAuxApprenants(EquipeProjet $equipeProjet, array $nouvelApprenantIds): void
{
    $tachesSpecifiques = $equipeProjet->taches;
    if ($tachesSpecifiques->isEmpty()) {
        return;
    }

    $projetId = $equipeProjet->projet_id;
    $realisationTacheService = app(RealisationTacheService::class);
    $etatService = app(EtatRealisationTacheService::class);
    $formateurId = $equipeProjet->projet?->formateur_id;
    $etatInitial = $formateurId ? $etatService->getDefaultEtatByFormateurId($formateurId) : null;

    foreach ($nouvelApprenantIds as $apprenantId) {
        // Retrouver la réalisation de projet de l'apprenant pour ce projet
        $realisationProjet = RealisationProjet::where('apprenant_id', $apprenantId)
            ->whereHas('affectationProjet', function ($q) use ($projetId) {
                $q->where('projet_id', $projetId);
            })
            ->first();

        if (!$realisationProjet) {
            continue;
        }

        foreach ($tachesSpecifiques as $tache) {
            $realisationTacheService->initialiserRealisationTache($tache, $realisationProjet, $etatInitial);
        }
    }
}
```

---

## 🔍 Analyse d'Impact par Composant

| Composant | Impact | Détails |
| :--- | :--- | :--- |
| **Base de Données** | Aucun impact | Pas de modification de structure. Utilise la table pivot existante `apprenant_equipe_projet` et la table `realisation_taches`. |
| **Modèles Eloquent** | Aucun impact | Utilisation des relations existantes : `EquipeProjet::taches()`, `EquipeProjet::apprenants()`, `RealisationProjet::affectationProjet()`. |
| **Couche Service** | **Impact Principal** | - Implémentation du hook post-sauvegarde dans `EquipeProjetService` pour détecter les nouveaux apprenants.<br>- Appel de `RealisationTacheService::initialiserRealisationTache()`. |
| **Contrôleur** | Aucun impact | La synchronisation reste purement gérée par la couche Service (conformité "Fat Service, Skinny Controller"). |
| **Vues Blade / UI** | Aucun impact | L'apprenant verra automatiquement apparaître les tâches d'équipe dans ses listes existantes sans modification de vue. |
| **Générateur (Gapp)** | Aucun impact | Modification encapsulée dans les classes enfants du Service sans altérer les stubs `Base/`. |

---

## 🛠️ Skills Requis pour Implémentation

- `pkg-creation-projet` : Gestion des équipes et de la synchronisation de leurs membres (`EquipeProjetService`).
- `pkg-realisation-tache` : Initialisation et cycle de vie des tâches (`RealisationTacheService`).
- `app-service` : Respect de l'architecture des Services et des hooks de persistance CRUD.
