# Spécification Technique : Workflow Définition Projet & Tâches

**Référence Diagramme** : [`definition_projet_tache_workflow.mmd`](./definition_projet_tache_workflow.mmd)
**Module Responsable** : `PkgCreationProjet` & `PkgCreationTache`

---

## 1. Contexte et Objectifs
Ce workflow décrit comment le Formateur définit la structure pédagogique d'un projet. 
Contrairement à une simple saisie de données, ces actions ont des répercussions immédiates sur les entités opérationnelles (Réalisations) si le projet est déjà affecté à des apprenants.

**Enjeu Majeur (SRP)** : 
- `ProjetService` et `TacheService` doivent gérer la *définition*.
- Ils doivent déléguer la *répercussion* de ces changements à `RealisationProjetService` et `RealisationTacheService`.

---

## 2. Scénarios Métier

### A. Création d'un Projet (Avec/Sans Session)
**Déclencheur** : `ProjetService::create()`
- **Entrée** : Données du projet (Titre, Desc, SessionID optionnel).
- **Règles Métier** :
    - Si une Session est fournie, le projet doit être techniquement rattaché à l'Année de Formation correspondante.
    - Aucune répercussion sur les réalisations à ce stade (car le projet vient de naître).

### B. Ajout d'une Tâche (Propagation)
**Déclencheur** : `TacheService::create()`
- **Contexte** : Le Formateur ajoute une tâche à un projet existant.
- **Impact** : Si ce projet est déjà affecté (des `RealisationProjet` existent), on ne peut pas laisser les apprenants avec une tâche manquante.
- **Action SRP** : 
    1. `TacheService` insère la tâche.
    2. `TacheService` *demande* à `RealisationProjetService` la liste des réalisations en cours.
    3. Pour chaque réalisation, `TacheService` *commande* à `RealisationTacheService` de créer l'entrée correspondante.

### C. Modification d'une Tâche (Synchronisation)
**Déclencheur** : `TacheService::update()`
- **Impact** : Mise à jour des libellés ou dates.
- **Action SRP** : 
    - Si la modification est purement textuelle (Titre), pas d'impact majeur.
    - Si modification structurelle (Livrable, Date Limite), `TacheService` notifie `RealisationTacheService` pour mettre à jour les métadonnées des réalisations existantes.

### D. Suppression d'une Tâche (Nettoyage)
**Déclencheur** : `TacheService::delete()`
- **Risque** : Intégrité référentielle (Clés étrangères dans `realisation_taches`).
- **Action SRP** :
    1. AVANT de supprimer la Tâche.
    2. `TacheService` ordonne à `RealisationTacheService` de supprimer toutes les réalisations liées à cette tâche spécifique.
    3. Suppression effective de la Tâche.
