# Règle de Gestion : Modèle de données PkgRealisationTache

## 1. Entités Principales et Relations

Le module `PkgRealisationTache` gère le suivi de la réalisation des tâches affectées aux apprenants.

### A. `RealisationTache`
C'est l'entité centrale du module. Elle relie une tâche spécifique à la réalisation globale d'un projet.
- **Relations `BelongsTo`** :
  - `tache` (`PkgCreationTache\Tache`) : La tâche d'origine à réaliser.
  - `realisationProjet` (`PkgRealisationProjets\RealisationProjet`) : Le contexte global de réalisation du projet pour l'apprenant.
  - `etatRealisationTache` (`EtatRealisationTache`) : L'état actuel d'avancement.
  - `tacheAffectation` (`TacheAffectation`) : Lien vers l'affectation de la tâche.
- **Relations `HasMany`** :
  - `evaluationRealisationTaches` : Les évaluations associées.
  - `historiqueRealisationTaches` : Trace des changements d'état ou modifications.
  - `commentaireRealisationTaches` : Discussions et commentaires.
- **Relations `BelongsToMany`** :
  - `labelProjets` : Étiquettes liées à la tâche.

### B. `EtatRealisationTache`
Gère le workflow et le statut (ex: À faire, En cours, Terminé).
- **Champs clés** : `nom`, `description`, `is_editable_only_by_formateur` (pour restreindre le changement de certains statuts aux formateurs).
- **Workflow** : Les états suivent un ordre (géré via `workflowTache.ordre`).

### C. `HistoriqueRealisationTache`
- Assure la traçabilité des modifications effectuées sur une `RealisationTache`.
- Stocke les détails du changement et l'indicateur de feedback formateur (`isFeedback`).

### D. `CommentaireRealisationTache`
- Permet l'échange entre l'apprenant et le formateur sur une tâche spécifique.

### E. `TacheAffectation`
- Gère l'affectation spécifique d'une tâche (si elle diffère du contexte global du projet).

## 2. Attributs Dynamiques

Le modèle `RealisationTache` embarque des champs calculés (via des jointures) :
- `projet_title` : Titre du projet.
- `nom_prenom_apprenant` : Identité de l'apprenant.
- `nombre_livrables` : Nombre de livrables liés à la tâche.
- `travail_a_faire` : Description de la tâche.
- `deadline` : Date de fin définie au niveau de la tâche.

## 3. Règles de Gestion et Cycle de Vie
- **Mise à jour d'état** : La mise à jour de l'état (ex: passage à "Terminé") doit vérifier `is_editable_only_by_formateur`.
- **Validation** : Les attributs comme `is_live_coding`, `note`, `remarques_formateur` sont souvent renseignés par l'évaluateur lors de la validation finale.
