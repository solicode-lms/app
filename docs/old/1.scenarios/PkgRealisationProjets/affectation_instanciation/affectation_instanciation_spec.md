# Spécification Technique : Workflow Affectation & Instanciation

**Référence Diagramme** : [`affectation_instanciation_workflow.mmd`](./affectation_instanciation_workflow.mmd)
**Module Responsable** : `PkgRealisationProjets`

---

## 1. Contexte et Objectifs
Ce workflow représente le moment critique ("Le Big Bang") où un projet théorique devient une réalité opérationnelle pour un groupe d'apprenants.
L'objectif est d'instancier toutes les entités de suivi (`RealisationProjet`, `RealisationTache`) en une seule transaction logique, tout en respectant strictement l'isolation des services.

---

## 2. Scénarios Métier

### A. Affectation Initiale (Projet -> Groupe)
**Déclencheur** : `AffectationProjetService::create(projet_id, groupe_id)`

**Logique d'Orchestration (SRP)** :
1.  **Création du Lien** : `AffectationProjetService` crée l'enregistrement `affectation_projets`. C'est sa seule responsabilité directe en BDD.
2.  **Délégation** : Une fois le lien créé (via Hook `afterCreate`), il délègue immédiatement la suite à `RealisationProjetService`.
    > *L'Affectation ne sait pas COMMENT on crée une réalisation, elle sait juste QU'IL FAUT le faire.*

### B. Génération en Cascade (La boucle Apprenants)
**Responsable** : `RealisationProjetService::generateRealisations()`

**Processus par Apprenant** :
1.  **Instanciation Projet** : Création d'une ligne `realisation_projets`.
2.  **Récupération de la Structure** : `RealisationProjetService` demande à `TacheService` ("Quelles sont les tâches de ce projet ?").
3.  **Instanciation Tâches** :
    - Pour chaque modèle de Tâche reçu, `RealisationProjetService` ne l'insère pas lui-même.
    - Il demande à `RealisationTacheService` : "Crée-moi une réalisation pour cette tâche précise, ratachée à ce projet d'apprenant".

### C. Gestion des États Initiaux
- Les `RealisationProjet` naissent avec un état "En cours" (ou "Démarré").
- Les `RealisationTache` naissent avec un état "À faire" (TODO).

---
## 3. Points d'Attention SRP
- **AffectationProjetService** : Ne doit JAMAIS scanner la table `apprenants` directement pour créer des lignes. Il délègue.
- **TacheService** : Est consulté uniquement en lecture (`getTasksForProject`) pour fournir le "Plan". Il n'écrit rien ici.
- **RealisationTacheService** : Est le seul autorisé à faire un `INSERT INTO realisation_taches`.
