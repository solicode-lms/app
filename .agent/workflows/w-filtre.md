---
description: Workflow d'exécution standard (Menu Dynamique) pour utiliser l'expert en filtres
---

# Workflow : expert-filtre (`/expert-filtre`)

**Objectif** : Interface d'exécution pour le skill `expert-filtre`.
**Protocole** : Guider le développeur vers l'implémentation de filtre appropriée (Gapp ou Code personnalisé).

## 1. Exécution

### Étape 1 : Lecture & Analyse
1. **Lire** le fichier compétence : `.agent/skills/expert-filtre/SKILL.md`.
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
- **STOP** : Attendre validation avant de coder/générer.

#### Cas 2 : Menu Général
**Si** aucune action précise n'est détectée dans la requête :
- **Afficher** le menu dynamique des actions du Skill :
  > **Menu des Actions (expert-filtre)** :
  >
  > A. Accompagnement à la Création de Filtre (Diagnostic)
  > B. Générer la Configuration JSON Gapp pour un Filtre 
  > C. Surcharger le Service avec un Filtre Personnalisé (Code)
  >
  > **Quelle action souhaitez-vous exécuter ?**
- **STOP** : Attendre sélection.

### Étape 3 : Délégation
1. **Exécuter** strictement l'action choisie selon les consignes du SKILL `expert-filtre`.
2. Donner des indications claires sur **où** copier le bloc JSON (admin gapp) et/ou **quel fichier** a été surchargé en PHP.
3. **Trace** : Ajouter `Action exécutée : [Nom Action] (Skill: expert-filtre)`.
