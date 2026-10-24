---
name: sys-redacteur-technique
description: Expert de la maintenance et de la génération de la documentation technique dans le dossier "docs".
---

# Skill : Rédacteur Technique

## 🎯 Périmètre Global
**Mission** : Assurer la création, la mise à jour et la cohérence de la documentation technique et fonctionnelle du projet Solicode LMS, centralisée dans le dossier `docs/`.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Emplacement de la documentation** : La documentation ne doit JAMAIS être générée dans le dossier local `docs/`. Elle doit **impérativement** être générée dans le dossier externe `E:\solicode-lms\docs` ou `D:\solicode-lms\docs` (selon le volume disponible sur la machine).
2. **Signalisation (README local)** : Le fichier local `docs/README.md` doit toujours mentionner que la documentation réelle est hébergée dans le dépôt externe (sans préciser l'emplacement physique exact).
3. **Langue** : Toute la documentation doit être rédigée impérativement en **Français**, de manière claire, professionnelle et concise.
4. **Cohérence** : Avant de créer un nouveau fichier, s'assurer qu'il n'existe pas déjà un document similaire qu'il faudrait plutôt mettre à jour.

---

## ⚡ Actions (Orchestration)

### Action A : Documenter un Composant ou un Module
> **Description** : Créer ou actualiser un fichier de documentation pour un module ou un aspect technique, sur le dépôt externe.
- **Capacités Utilisées** :
  - `capacités/capacité-redaction-markdown.md`
- **Entrées** : `Sujet / Module concerné`, `Contenu brut`, `Mode (Création / Mise à jour)`
- **Sorties** : Fichier `.md` dans le dossier externe (`E:\solicode-lms\docs` ou `D:\solicode-lms\docs`), et mise à jour du `docs/README.md` local si nécessaire.
- **❌ Interdictions Spécifiques** :
  - Ne pas écraser l'historique d'un document existant s'il contient des informations toujours pertinentes.
- **✅ Points de Contrôle** :
  - **Structure** : Le document suit le template `resources/template-doc.md`.
  - **Nommage** : Le nom du fichier est en `kebab-case.md`.
  - **Emplacement cible** : Vérifier quel lecteur externe (E: ou D:) est disponible avant d'écrire.
- **📝 Instructions d'Orchestration** :
  1. **Détermination de la cible** : Vérifier si `E:\solicode-lms\docs` ou `D:\solicode-lms\docs` est accessible.
  2. **Recherche** : Vérifier si un document similaire existe dans le dossier cible.
  3. **Création/Modification** : Utiliser `capacité-redaction-markdown.md` pour formater le contenu et appliquer le template si création.
  4. **Sauvegarde externe** : Écrire le document généré ou mis à jour dans le dossier externe cible (ex: `E:\solicode-lms\docs\[catégorie]\[nom-fichier].md`).
  5. **Mise à jour README local** : S'assurer que le fichier local `docs/README.md` contient un message clair indiquant que la documentation se trouve dans le dépôt externe.

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

