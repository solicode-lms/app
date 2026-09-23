---
name: redacteur-technique
description: Expert de la maintenance et de la génération de la documentation technique dans le dossier "docs".
---

# Skill : Rédacteur Technique

## 🎯 Périmètre Global
**Mission** : Assurer la création, la mise à jour et la cohérence de la documentation technique et fonctionnelle du projet Solicode LMS, centralisée dans le dossier `docs/`.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Emplacement** : Ne JAMAIS générer de documentation en dehors du dossier `docs/` (sauf les README à la racine si explicitement demandé).
2. **Langue** : Toute la documentation doit être rédigée impérativement en **Français**, de manière claire, professionnelle et concise.
3. **Cohérence** : Avant de créer un nouveau fichier, s'assurer qu'il n'existe pas déjà un document similaire qu'il faudrait plutôt mettre à jour.

---

## ⚡ Actions (Orchestration)

### Action A : Documenter un Composant ou un Module
> **Description** : Créer ou actualiser un fichier de documentation pour un module ou un aspect technique.
- **Capacités Utilisées** :
  - `capacités/capacité-redaction-markdown.md`
- **Entrées** : `Sujet / Module concerné`, `Contenu brut`, `Mode (Création / Mise à jour)`
- **Sorties** : Fichier `.md` dans le dossier `docs/`
- **❌ Interdictions Spécifiques** :
  - Ne pas écraser l'historique d'un document existant s'il contient des informations toujours pertinentes.
- **✅ Points de Contrôle** :
  - **Structure** : Le document suit le template `resources/template-doc.md`.
  - **Nommage** : Le nom du fichier est en `kebab-case.md`.
- **📝 Instructions d'Orchestration** :
  1. **Recherche** : Vérifier si un document similaire existe dans `docs/`.
  2. **Création/Modification** : Utiliser `capacité-redaction-markdown.md` pour formater le contenu et appliquer le template si création.
  3. **Sauvegarde** : Écrire le document généré ou mis à jour dans `docs/[catégorie]/[nom-fichier].md`.

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `capacités/`*

### 1. `capacité-redaction-markdown.md`
- **Rôle** : Standards de formatage et utilisation de diagrammes (Mermaid, code blocks, etc.).
- **Règles Clés** : Un seul titre `#`, utilisation de tableaux et de schémas.

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario 1 : Génération initiale de documentation
*Cas : "Crée une documentation pour le PkgFormation"*
1. **Analyse** : Parcourir le code du composant demandé.
2. **Exécution** : Lancer l'**Action A** en mode Création.
3. **Rapport** : Indiquer le chemin du fichier créé.

### Scénario 2 : Mise à jour de spécifications
*Cas : "Mets à jour la doc suite à l'ajout du champ 'statut'"*
1. **Analyse** : Trouver le document existant avec `grep` ou en listant `docs/`.
2. **Exécution** : Lancer l'**Action A** en mode Mise à jour.
3. **Rapport** : Lister les modifications effectuées dans le document.
