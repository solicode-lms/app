---
description: Workflow unifié pour la maintenance, l'évolution et l'amélioration continue de l'agent.
---

# Workflow : Maintenance Agent (`/raffinement-agent`)

## 1. Contexte & Flux Global
**Objectif** : Garantir l'intégrité et l'évolution contrôlée de la structure de l'agent (Skills, Rules, Workflows).
**Flux Type** : `[Analyse de la Demande]` → `[Confirmation ou Menu]` → `[Exécution]`

## 2. Exécution

### Étape 1 : Analyse de la Demande

**Analyser le message de l'utilisateur** pour détecter l'action appropriée.

**Logique de Détection** :
- Mots-clés **"skill"**, **"compétence"**, **"expert"**, **"créer skill"**, **"modifier skill"** → Détecter **Action A**
- Mots-clés **"rule"**, **"règle"**, **"mémoire"**, **"contrainte"**, **"ajouter règle"** → Détecter **Action B**
- Mots-clés **"workflow"**, **"processus"**, **"slash"**, **"commande"**, **"modifier workflow"** → Détecter **Action C**

---

### Étape 2 : Routage Conditionnel

#### Cas 1 : Action Détectée avec Confiance

**Si une action a été clairement identifiée à l'Étape 1**, afficher directement la confirmation :

**Format de Confirmation** :
```
📋 Demande Identifiée

Vous souhaitez [Description de l'action détectée].

Action détectée : Action [X] - [Nom de l'action]
→ [Description courte]

Voulez-vous procéder avec cette action ? (Tapez 'oui' pour continuer)
```

**STOP** : Attendre la confirmation du développeur.

#### Cas 2 : Aucune Action Détectée ou Commande Sans Message

**Si aucune action claire n'est détectée** (commande invoquée seule ou message ambigu), afficher le menu complet :

> **Actions disponibles (Skill : expert-agent)** :
>
> **A.** Gérer un Skill (Compétence)  
> → Créer ou mettre à jour un skill dans `.agent/skills/`
>
> **B.** Gérer une Rule (Règle/Mémoire)  
> → Créer ou mettre à jour une règle dans `.agent/rules/`
>
> **C.** Gérer un Workflow (Processus)  
> → Créer ou mettre à jour un workflow dans `.agent/workflows/`
>
> **Quelle action souhaitez-vous exécuter ?** (Tapez A, B ou C)

**STOP** : Attendre la sélection du développeur.

---

### Étape 3 : Exécution de l'Action Choisie

**⚠️ Règle de Sécurité (Validation Bloquante)**
Avant de créer ou modifier un fichier (Action d'écriture), l'agent **DOIT IMPÉRATIVEMENT** :
1. **Générer** le contenu proposé (Fichier complet ou Diff).
2. **Afficher** ce contenu dans un bloc de code.
3. **Demander** : "Validez-vous ce contenu ?"
4. **STOP** : Attendre la validation explicite du développeur.

Une fois validé, exécuter l'action correspondante du skill `expert-agent`.

#### Si Action A sélectionnée (Gérer Skill)
- **Skill Cible** : `expert-agent`
- **Action** : `Action A : Manage Skill (Gérer Compétence)`
- **Inputs Fournis** :
  - Demander au développeur : "Nom du skill ?"
  - Demander au développeur : "Mode ? (Create/Update)"
  - Demander au développeur : "Besoin/Description ?"
- **STOP** : Vérifier que le fichier `SKILL.md` respecte `capacités/capacités-skill.md`

#### Si Action B sélectionnée (Gérer Rule)
- **Skill Cible** : `expert-agent`
- **Action** : `Action B : Manage Rule (Gérer Règle)`
- **Inputs Fournis** :
  - Demander au développeur : "Nom de la règle ?"
  - Demander au développeur : "Mode ? (Create/Update)"
  - Demander au développeur : "Contenu de la règle ?"
- **STOP** : Vérifier que le fichier respecte `capacités/capacités-rule.md`

#### Si Action C sélectionnée (Gérer Workflow)
- **Skill Cible** : `expert-agent`
- **Action** : `Action C : Manage Workflow (Gérer Processus)`
- **Inputs Fournis** :
  - Demander au développeur : "Nom du workflow ?"
  - Demander au développeur : "Mode ? (Create/Update)"
  - Demander au développeur : "Étapes du processus ?"
- **STOP** : Vérifier que le fichier respecte `capacités/capacités-workflow.md`

---

## 3. Critères de Qualité
- **Découvrabilité** : Le développeur voit toutes les capacités de maintenance
- **Unicité** : Pas de doublons fonctionnels
- **Conformité** : Respect strict des templates et standards (`capacités/`)
- **Isolation** : Seuls les fichiers de configuration de l'agent sont touchés
