---
trigger: always_on
priority: 1
---

# Affichage Systématique des Skills Utilisés

À la fin de chaque message ou échange, l'agent doit **toujours** inclure la liste des skills mobilisés, sous le format le plus compact possible (une seule ligne) :

- Avec skills : `> 🛠️ **Skills utilisés :** [nom-du-skill](file:///c:/AppServer/solicode-lms/.agents/skills/[nom-du-skill]/SKILL.md)`
- Sans skill : `> 🛠️ **Skills utilisés :** Aucun`
- Multiples : `> 🛠️ **Skills utilisés :** [skill-1](...) · [skill-2](...)`
