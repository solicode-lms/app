# Solicode LMS

## Installation 

````bash
npm install
composer install
php artisan migrate
php artisan db:seed
php artisan key:generate
php artisan serve
````

## Comptes 

### Admin 

- login : admin@solicode.co
- password : admin

---

## 🤖 Assistant IA & Compétences (Skills)

Le projet intègre un écosystème d'Agent IA normalisé selon les spécifications d'Anthropic et doté de **7 compétences spécialisées** adaptées au profil du projet :

- **Conception & Dév :** [`feature-dev`](.agents/skills/feature-dev/SKILL.md), [`domain-scaffolder`](.agents/skills/domain-scaffolder/SKILL.md), [`planner-dev`](.agents/skills/planner-dev/SKILL.md), [`bug-fixer`](.agents/skills/bug-fixer/SKILL.md).
- **Gouvernance & Contexte :** [`context-generator`](.agents/skills/context-generator/SKILL.md), [`project-setup`](.agents/skills/project-setup/SKILL.md), [`plugin-dev`](.agents/skills/plugin-dev/SKILL.md).
*(Les compétences pédagogiques `tutorial-writer` et `workshop-builder` du Hub sont exclues sur ce projet applicatif).*

👉 **Documentation pratique :**
- [Guide de démarrage rapide de l'Agent (`.agents/README.md`)](.agents/README.md)
- [Guide officiel d'utilisation des Skills (`guide-utilisation-skills.md`)](.agents/references/usage/guide-utilisation-skills.md)
