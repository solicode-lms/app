---
trigger: always_on
priority: 1
---

# Affichage Systématique des Skills Mobilisés & Contextes Packages

1. **Au début de chaque réponse ou intervention :** L'agent doit afficher immédiatement les skills mobilisés pour confirmer sa bonne compréhension de la tâche avant d'exécuter quoi que ce soit :
   - Format : `> 🛠️ **Skills mobilisés :** [nom-du-skill](file:///c:/AppServer/solicode-lms/.agents/skills/[nom-du-skill]/SKILL.md)`
   - Si aucun skill n'est requis : `> 🛠️ **Skills mobilisés :** Aucun`
   - Si un ou plusieurs packages métiers sont ciblés : l'agent doit impérativement charger le fichier `[NomPkg]_IA_CONTEXT.md` correspondant avant toute analyse ou modification et afficher :
     `> 📦 **Contexte package chargé :** [NomPkg](file:///c:/AppServer/solicode-lms/modules/[NomPkg]/[NomPkg]_IA_CONTEXT.md)`

2. **À la fin de l'intervention :** L'agent confirme la liste compacte des skills utilisés et contextes packages :
   - Format :
     `> 🛠️ **Skills utilisés :** [nom-du-skill](file:///c:/AppServer/solicode-lms/.agents/skills/[nom-du-skill]/SKILL.md)`
     `> 📦 **Contextes packages chargés :** [NomPkg](file:///c:/AppServer/solicode-lms/modules/[NomPkg]/[NomPkg]_IA_CONTEXT.md)`
