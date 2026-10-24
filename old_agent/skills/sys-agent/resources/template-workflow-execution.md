---
description: Workflow d'exécution standard (Menu Dynamique)
---

# Workflow : [Nom du Workflow] (`/[slug]`)

**Objectif** : Interface d'exécution pour le skill `[nom-skill]`.
**Protocole** : Suivre le standard `.agent/resources/protocoles-workflow.md`.

## 1. Exécution

### Étape 1 : Lecture & Analyse
1. **Lire** le fichier compétence : `.agent/skills/[nom-skill]/SKILL.md`.
2. **Extraire** la liste des Actions disponibles (Section `⚡ Actions`).
3. **Analyser** la demande utilisateur pour mapper vers une Action existante.

### Étape 2 : Routage Conditionnel

#### Cas 1 : Action Identifiée
**Si** une action correspond à la demande :
- **Confirmer** l'intention :
  ```
  📋 Action Détectée : [Nom de l'Action]
  → [Description issue du SKILL]
  Voulez-vous procéder ?
  ```
- **STOP** : Attendre validation.

#### Cas 2 : Menu Général
**Si** aucune action précise n'est détectée :
- **Afficher** le menu dynamique des actions du Skill :
  > **Menu des Actions ([nom-skill])** :
  >
  > [Générer la liste dynamiquement depuis le fichier SKILL.md]
  > (Ex: A. [Nom Action] - [Description])
  >
  > **Quelle action souhaitez-vous exécuter ?**
- **STOP** : Attendre sélection.

### Étape 3 : Délégation
1. **Exécuter** strictement l'action choisie selon les consignes du SKILL.
2. **Trace** : Ajouter `Action exécutée : [Nom Action] (Skill: [nom-skill])`.
