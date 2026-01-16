# Spécification Technique : Cas d'Utilisation "Création Projet"

**Référence Diagramme** : [`creation_projet_workflow.mmd`](./creation_projet_workflow.mmd)
**Module Responsable** : `PkgCreationProjet`

---

## 1. Contexte
Ce processus décrit la naissance d'un projet pédagogique. Il peut être créé de zéro ou potentiellement cloné (hors périmètre initial).

## 2. Règles Métier
- **Avec Session** : Si une session est spécifiée, le projet est automatiquement rattaché à l'Année de Formation en cours.
- **Sans Session** : Le projet est créé comme un modèle générique, utilisable plus tard.
- **Impact SRP** : `ProjetService` ne fait QUE créer l'entrée dans la table `projets`. Il n'appelle personne d'autre car un projet vide n'a pas d'impact.
