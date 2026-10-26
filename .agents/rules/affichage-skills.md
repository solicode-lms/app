---
trigger: always_on
priority: 1
---

# Affichage Systématique des Skills Mobilisés

1. **Au début de chaque réponse ou intervention :** L'agent doit afficher immédiatement les skills mobilisés pour confirmer sa bonne compréhension de la tâche avant d'exécuter quoi que ce soit :
   - Format : `> 🛠️ **Skills mobilisés :** [nom-du-skill](file:///c:/AppServer/solicode-lms/.agents/skills/[nom-du-skill]/SKILL.md)`
   - Si aucun skill n'est requis : `> 🛠️ **Skills mobilisés :** Aucun`

2. **À la fin de l'intervention :** L'agent confirme la liste compacte des skills utilisés :
   - Format : `> 🛠️ **Skills utilisés :** [nom-du-skill](file:///c:/AppServer/solicode-lms/.agents/skills/[nom-du-skill]/SKILL.md)`
