---
name: raffinement-agent
description: Workflow unifié pour la maintenance, l'évolution et l'amélioration continue de l'agent.
---

# Skill : Méta-Agent d'Apprentissage

## 🎯 Périmètre Global
**Mission** : Assurer l'amélioration continue du système cognitif de l'agent. À la fin d'une tâche complexe ou après la résolution d'une issue difficile, ce skill permet d'analyser le déroulement de la conversation pour en extraire de nouvelles règles, de nouvelles capacités ou même de proposer la création de nouveaux experts (Skills).

## ⚡ Actions (Orchestration)

### Action A : Analyser et Proposer un Plan d'Apprentissage (Sans Ordre Explicite)
> **Description** : Invoqué lorsque le développeur appelle le skill sans ordre précis ou pour une analyse rétrospective ouverte. Scanne l'historique récent de la conversation, identifie les erreurs rencontrées, les concepts appris, et génère un artefact de plan proposant les modifications du système.
- **Entrées** : L'historique de la conversation (implicite).
- **Sorties** : Un artefact `plan_apprentissage.md` contenant la proposition stratégique (avec l'option `RequestFeedback` activée).
- **📝 Instructions** :
  1. **Rétrospective** : Identifier le problème principal qui vient d'être résolu et la solution technique qui a fonctionné.
  2. **Conceptualisation** : Transformer cette solution spécifique en une règle technique générale et réutilisable.
  3. **Planification** : Proposer un plan structuré dans un Artefact (avec l'option `RequestFeedback` activée) et attendre le bouton `Proceed`.

### Action B : Exécution Directe des Modifications (Avec Ordre Explicite)
> **Description** : Lorsque l'utilisateur formule une demande de modification explicite (consignes claires de ce qu'il faut modifier, ajouter ou corriger dans les skills/rules), **ce skill applique immédiatement les modifications sans proposer de plan préalable**.
- **Entrées** : Instructions explicites fournies par le développeur.
- **Sorties** : Mise à jour immédiate des fichiers `.agent/` correspondants.
- **📝 Instructions** :
  1. Appliquer directement les modifications dans les fichiers `.agent/` (skills, rules, workflows) ciblés via `replace_file_content` ou `write_to_file`.
  2. Respecter scrupuleusement la règle des chemins relatifs dans `.agent/`.
  3. Rendre compte de l'application de manière concise et factuelle.

---

## 🔄 Scénarios d'Exécution

### Scénario 1 : Demande de modification explicite (RÈGLE STRICTE : Sans Plan)
*Déclencheur : L'utilisateur formule des consignes précises (ex: "modifie le skill X pour...", "ajoute la règle Y...", "quand je demande...")*
1. L'agent détecte des consignes explicites de modification.
2. Il exécute immédiatement l'**Action B** : modification directe des skills, rules ou workflows concernés.
3. Il confirme immédiatement l'application sans étape intermédiaire ni proposition d'artefact de plan.

### Scénario 2 : Invocation sans ordre / sans consignes (RÈGLE STRICTE : Avec Plan)
*Déclencheur : `/raffinement-agent` est invoqué seul ou l'utilisateur demande une analyse globale sans préciser les changements à opérer.*
1. L'agent exécute l'**Action A** : analyse rétrospective et génération d'un artefact de plan.
2. **STOP** : L'agent attend la validation du développeur via le bouton `Proceed`.
3. Une fois validé, il applique les modifications et confirme.

