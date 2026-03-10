# Capacité : Gestion des Règles (Rules)

## 1. Structure Obligatoire

Les Règles doivent être regroupées par **Catégorie** dans des fichiers uniques.
- **Principe** : Une Catégorie = Un Fichier Markdown.
- **Emplacement** : `.agent/rules/[nom-categorie].md` (ex: `01-conventions.md`, `02-securite.md`).
- **Format** : Fichier Markdown avec Frontmatter YAML.

## 2. Validation & Standards

### Contenu du Fichier de Règles
- **En-tête YAML** :
  - `trigger`: Condition d'activation globale pour le fichier (`always_on` ou glob pattern).
- **Structure Interne** :
  - **Titre H1** : Nom de la Catégorie et Thème.
  - **Section "Objectif"** : But global de cette catégorie de règles.
  - **Section "Instructions"** : Groupes de directives organisés par sous-titres (`###`).
  - **Section "Interdictions"** : Liste claire des actions prohibées.
- **Langue** : Français strict.

### Workflow de Création
1. **Identifier** la catégorie pertinente (ex: Architecture, Code Style, Workflow).
2. **Vérifier** si un fichier de catégorie existe déjà. Si oui, l'amender.
3. **Créer** un nouveau fichier UNIQUEMENT si une nouvelle catégorie est nécessaire.
4. **Utiliser** `template-rule.md` pour la structure de base.

## 3. Bonnes Pratiques
- **Jamais** de fichier par règle individuelle (Granularité trop fine).
- Nommer les fichiers avec un préfixe numérique pour l'ordre de lecture (ex: `00-meta.md`, `01-stack.md`).
- Utiliser des impératifs forts ("DOIT", "NE DOIT PAS").
