# Capacité : Feedback Loop (Mise à jour post-correction)

## 🎯 Objectif
Cette capacité définit la méthodologie stricte pour analyser les retours, corrections et remarques du développeur dans la conversation courante, et les intégrer de manière pérenne dans le système cognitif de l'agent (Skills ou Rules).

## ⚙️ Méthodologie (Étapes à suivre)

### 1. Analyse du Contexte
- **Identifier l'outil / composant ciblé** : Quel est le composant que le développeur vient de corriger ? (ex: Vue Blade, Contrôleur, Service).
- **Identifier le Skill correspondant** : Quel est le skill responsable de ce composant ? (ex: `app-blade`, `app-service`). S'il n'est pas explicite, déduisez-le du contexte récent de la conversation.

### 2. Extraction de la Règle (Généralisation)
- Ne pas se contenter de retenir la modification pour le cas spécifique.
- Transformer la remarque du développeur en une **Règle Universelle**.
  - *Exemple de remarque : "N'oublie pas le scope dans linksAction pour le projet_id"*
  - *Règle généralisée : "Lors de l'utilisation de linksAction, il est CRITIQUE de toujours inclure les paramètres contextKey et scope.*

### 3. Choix du Fichier à Mettre à Jour
- **Dans le fichier principal `SKILL.md`** : S'il s'agit d'une consigne globale d'orchestration ou d'une interdiction critique.
- **Dans une Capacité (dossier `capacités/`)** : S'il s'agit d'une spécification technique, d'un format JSON, ou d'une règle de syntaxe.
- **Dans une Règle de Gestion (dossier `règles-gestion/`)** : S'il s'agit d'une logique métier liée à un module (ex: `pkg-apprentissage`).

### 4. Application de la Mise à Jour
- Utiliser l'outil d'édition de fichier pour injecter la nouvelle règle.
- Utiliser un langage ferme et strict (ex: "CRITIQUE", "OBLIGATOIRE", "NE JAMAIS").
- Mettre en évidence la modification (gras, blocs d'avertissement) pour que l'agent ne puisse plus l'ignorer lors des prochaines exécutions.

## 💡 Résultat Attendu
L'agent doit confirmer à l'utilisateur que :
1. Le bon skill a été identifié.
2. Le fichier ciblé (ex: `capacite-linkAction.md` de `sys-gapp`) a été mis à jour.
3. L'erreur ne se reproduira plus grâce à cette nouvelle mémoire.
