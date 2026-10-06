---
name: raffinement-agent
description: Workflow unifié pour la maintenance, l'évolution et l'amélioration continue de l'agent.
---

# Skill : Méta-Agent d'Apprentissage

## 🎯 Périmètre Global
**Mission** : Assurer l'amélioration continue du système cognitif de l'agent. À la fin d'une tâche complexe ou après la résolution d'une issue difficile, ce skill permet d'analyser le déroulement de la conversation pour en extraire de nouvelles règles, de nouvelles capacités ou même de proposer la création de nouveaux experts (Skills).

## ⚡ Actions (Orchestration)

### Action A : Analyser et Proposer un Plan d'Apprentissage
> **Description** : Scanne l'historique récent de la conversation, identifie les erreurs rencontrées, les concepts appris, et génère un artefact de plan proposant les modifications du système.
- **Entrées** : L'historique de la conversation (implicite).
- **Sorties** : Un artefact `plan_apprentissage.md` contenant la proposition stratégique (mise à jour de capacités, création de capacités, création de skill).
- **📝 Instructions** :
  1. **Rétrospective** : Identifier le problème principal qui vient d'être résolu et la solution technique qui a fonctionné.
  2. **Conceptualisation** : Transformer cette solution spécifique en une règle technique générale et réutilisable.
  3. **Planification** : Proposer un plan structuré dans un Artefact (avec l'option `RequestFeedback` activée).

### Action B : Exécuter le Plan d'Apprentissage
> **Description** : Une fois le plan validé par l'utilisateur, ce skill exécute les modifications demandées sur l'architecture `.agent/`.
- **📝 Instructions** :
  1. Si une capacité existante doit être modifiée, utiliser l'outil `replace_file_content`.
  2. Si de nouvelles capacités doivent être créées, utiliser l'outil `write_to_file`.
  3. Si un nouveau skill doit être créé, s'assurer de respecter les standards de l'agent (création du dossier, du `SKILL.md`, des dossiers annexes si besoin).

---

## 🔄 Scénarios d'Exécution

### Scénario 1 : Invocation à la fin d'une tâche (Action par Défaut)
1. L'utilisateur invoque le skill en tapant `/raffinement-agent`.
2. L'agent exécute l'**Action A** et s'arrête en attente de la validation de l'artefact de plan généré.
3. Une fois l'artefact validé (`Proceed`), l'agent exécute l'**Action B** et confirme la mise à jour du système cognitif à l'utilisateur.
