# Directives & Règles Agent Solicode LMS

## 1. Affichage Systématique des Skills Utilisés (Règle d'or UI/Communication)
À la fin de chaque intervention ou réponse, l'agent doit **toujours** afficher la liste compacte des skills mobilisés lors du tour de parole :

- **Format obligatoire (en une seule ligne compacte tout à la fin) :**
  `> 🛠️ **Skills utilisés :** [nom-du-skill](file:///c:/AppServer/solicode-lms/.agents/skills/[nom-du-skill]/SKILL.md)`
- Si aucun skill n'a été exécuté pour une simple question d'ordre général :
  `> 🛠️ **Skills utilisés :** Aucun`
- Si plusieurs skills sont intervenus :
  `> 🛠️ **Skills utilisés :** [skill-1](...) · [skill-2](...)`

## 2. Intégrité Gapp & Architecture 3-Tiers
- Ne jamais toucher aux classes dans `Base/`.
- Respecter les verrous `ESSARRAJ Fouad` (supprimer dans classes enfants PHP pour étendre, ne jamais toucher dans Blade et utiliser `custom/`).
- Ne jamais exécuter de commandes destructives ou `php artisan migrate` en aveugle.
- Ne jamais lire ni modifier dans le dossier `db_schemas/` (réservé exclusivement au générateur Gapp).

## 3. Gestion Git & Responsabilité des Commits (LIGNE ROUGE)
- **L'AGENT A L'INTERDICTION FORMELLE D'EXÉCUTER `git commit`, `git push` OU TOUTE COMMANDE DE VALIDATION DE VERSION.**
- L'agent prépare le code, applique les modifications demandées et valide statiquement (tests, lint, syntaxe).
- La revue des diffs, le staging (`git add`) et la création des commits (`git commit`) relèvent exclusivement du développeur et du concepteur humain.

