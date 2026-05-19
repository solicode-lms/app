---
description: Workflow d'exécution standard (Menu Dynamique) pour utiliser l'expert en ViewState
---

# Workflow : Gérer les ViewState (`/w-view-state`)

**Objectif** : Interface d'exécution pour le skill `expert-view-state`.
**Protocole** : Suivre le standard `.agent/resources/protocoles-workflow.md`.

## 1. Exécution

### Étape 1 : Lecture & Analyse
1. **Lire** le fichier compétence : `.agent/skills/expert-view-state/SKILL.md` et sa capacité `.agent/skills/expert-view-state/capacites/capacite-manipulation-viewstate.md`.
2. **Extraire** la liste des Actions disponibles (Section `⚡ Actions`).
3. **Analyser** la demande utilisateur pour mapper vers une Action existante.

### Étape 2 : Routage Conditionnel

#### Cas 1 : Action Identifiée
**Si** une action correspond à la demande (ex: Ajouter un filtre ViewState) :
- **Confirmer** l'intention :
  ```
  📋 Action Détectée : Configurer un Filtre ViewState (Controller/Service)
  → Ajouter ou modifier un paramètre de filtrage dynamique (where/scope).
  Voulez-vous procéder ?
  ```
- **STOP** : Attendre validation.

#### Cas 2 : Menu Général
**Si** aucune action précise n'est détectée :
- **Afficher** le menu dynamique des actions du Skill :
  > **Menu des Actions (expert-view-state)** :
  >
  > A. Configurer un Filtre ViewState (Controller/Service) - Ajouter ou modifier un paramètre de filtrage via ViewState pour un module spécifique.
  >
  > **Quelle action souhaitez-vous exécuter ?**
- **STOP** : Attendre sélection.

### Étape 3 : Délégation
1. **Exécuter** strictement l'action choisie selon les consignes du SKILL `expert-view-state`.
2. **Trace** : Ajouter `Action exécutée : [Nom Action] (Skill: expert-view-state)`.
