# Contexte IA du Module : PkgRealisationTache

## 1. Responsabilité Métier
Le package **PkgRealisationTache** gère l'exécution opérationnelle, le suivi d'avancement et la validation des tâches par les apprenants :
1. **Suivi d'Exécution :** État d'avancement des tâches (`RealisationTache`), transitions d'états (À faire ➔ En cours ➔ En validation ➔ Terminé).
2. **Workflow & États :** Modélisation des statuts (`EtatRealisationTache`), règles de transition (`WorkflowTache`).
3. **Traçabilité & Échanges :** Historique chronologique des changements d'états (`HistoriqueRealisationTache`) et échanges formateurs-apprenants (`CommentaireRealisationTache`).
4. **Dispositifs Pédagogiques Spécifiques :** Suivi des sessions de codage en direct (`LiveCoding`) et des cycles de révision technique (`RevisionTache`).

---

## 2. Modèles & Relations Principales

### `RealisationTache` (Table : `realisation_taches`)
- **Hérite de :** `BaseRealisationTache`
- **Champs clés :** `tache_id`, `realisation_projet_id`, `etat_realisation_tache_id`, `date_debut`, `date_fin`, `is_live_coding`, `reference`.
- **Eager Loading par défaut :** `tache.livrables`, `realisationProjet.apprenant`, `etatRealisationTache`, `livrablesRealisations`.
- **Attribut Dynamique :** `formateur_name` (requête SQL dynamique liant la réalisation au formateur responsable du projet).
- **Scope Global :** `apprenant_actif` restreint la sélection aux apprenants en statut actif.
- **Relations directes :**
  - `tache` : `belongsTo(Tache::class, 'tache_id')` *(PkgCreationTache)*.
  - `realisationProjet` : `belongsTo(RealisationProjet::class, 'realisation_projet_id')` *(PkgRealisationProjets)*.
  - `etatRealisationTache` : `belongsTo(EtatRealisationTache::class)`.
  - `commentaires` : `hasMany(CommentaireRealisationTache::class)`.
  - `historiques` : `hasMany(HistoriqueRealisationTache::class)`.
  - `revisions` : `hasMany(RevisionTache::class)`.
  - `liveCodings` : `hasMany(LiveCoding::class)`.

### `EtatRealisationTache` (Table : `etat_realisation_taches`)
- Statuts de progression configurables par formateur et par défaut institutionnel.

---

## 3. Services & Logique Métier

### `RealisationTacheService` (`Modules\PkgRealisationTache\Services\RealisationTacheService`)
- **Gestion des Transitions :** Validation des prérequis avant passage à l'état suivant (ex: vérification des livrables déposés).
- **Audit Automatique :** Enregistrement systématique dans `HistoriqueRealisationTache` à chaque transition d'état.
- **Règles d'Intégrité BLL :** Déclenchement de `BlException` en cas de violation de flux (ex: validation sans livrable obligatoire).

### `EtatRealisationTacheService` & `WorkflowTacheService`
- Configuration dynamique des étapes et autorisations de transition par rôle (apprenant vs formateur).

---

## 4. Particularités UI & Filtres
- **Filtres TableUI :** Filtrage ManyToOne par Apprenant, Projet, Tâche et État de réalisation.
- **Composants d'Interaction :** Modales de commentaires (`CommentaireRealisationTache`), boutons d'actions d'avancement rapide et badge d'état stylisé.
