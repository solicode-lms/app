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
- **Commande invoquée seule** (sans argument) ou mots-clés d'**apprentissage/analyse** → Détecter **Action D (Action par défaut)**

---

### Étape 2 : Routage Conditionnel

#### Cas 1 : Demande de modification explicite (RÈGLE STRICTE : Exécution Directe Sans Plan)
**Si le message de l'utilisateur contient un ordre clair, une consigne ou précise quoi modifier/ajouter/corriger** (ex: "modifie le skill X...", "ajoute la règle Y...", "quand je demande...") :
- **Ne PAS proposer de plan préalable**.
- **Ne PAS bloquer en demandant confirmation**.
- Passer directement à l'**Étape 3 (Exécution Directe)** pour appliquer immédiatement les modifications demandées.

#### Cas 2 : Commande invoquée seule ou sans ordre précis (RÈGLE STRICTE : Avec Plan)
**Si la commande `/raffinement-agent` est invoquée seule** (sans argument) ou si le développeur demande une analyse globale / rétrospective sans consignes de modification précises :
- Basculer sur l'**Action D (Apprentissage Autonome & Rétrospectif)**.
- **Générer un artefact "Plan de Raffinement"** structuré avec l'option `RequestFeedback: true`.
- **STOP** : Attendre la validation explicite du développeur (via le bouton `Proceed`) avant toute modification.

---

### Étape 3 : Exécution de l'Action Choisie

#### En cas d'Ordre Explicite (Cas 1) :
L'agent applique immédiatement les modifications demandées sur les fichiers ciblés (`SKILL.md`, rules, workflows) via `replace_file_content` ou `write_to_file`. Il s'assure de respecter l'interdiction stricte des chemins absolus dans `.agent/`.

#### En cas d'Appel Sans Ordre (Cas 2 - Action D validée) :
Une fois le plan validé par le développeur, appliquer les évolutions validées.


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

#### Si Action D sélectionnée ou par défaut (Apprentissage Autonome)
- **Action** : `Action D : Apprentissage et Raffinement Global`
- **Instructions** :
  1. **Analyse Rétrospective :** Scanner l'historique de la conversation récente pour identifier les problèmes résolus, les erreurs corrigées, et les nouvelles notions techniques apprises.
  2. **Décision :** Déterminer s'il faut mettre à jour une capacité existante, créer une nouvelle capacité, ou créer un nouveau skill expert.
  3. **Planification :** Générer un artefact "Plan de Raffinement" décrivant les modifications proposées sur le système de l'agent.
  4. **STOP :** Demander explicitement la validation ("Validez-vous ce plan avec Proceed ?").
  5. **Exécution :** Une fois le plan validé, appliquer les changements dans `.agent/` (via `replace_file_content` ou `write_to_file`).

---

## 3. Critères de Qualité
- **Découvrabilité** : Le développeur voit toutes les capacités de maintenance
- **Unicité** : Pas de doublons fonctionnels
- **Conformité** : Respect strict des templates et standards (`capacités/`)
- **Isolation** : Seuls les fichiers de configuration de l'agent sont touchés
