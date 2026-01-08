# Projet : Merge-MD Prompts

## Description

Ce projet permet de :

* Parcourir automatiquement tous les dossiers situés à la racine du projet (par exemple : `Context/`, `Fonctionnalité/`, `Identité/`, etc.), en ignorant les dossiers `prompts/` et `node_modules/`.
* Récupérer tous les fichiers Markdown (`*.md`) contenus dans chacun de ces dossiers.
* Fusionner le contenu de ces fichiers Markdown pour chaque dossier en un unique fichier :

  ```
  prompts/{NomDossier}.prompt.md
  ```
* Enfin, générer un fichier central `prompts/agent-soli-lms.prompt.md` qui rassemble l’ensemble des `{NomDossier}.prompt.md` produits.

Ainsi, pour chaque dossier racine, on obtient un fichier `{NomDossier}.prompt.md` dans `prompts/` réunissant tous les textes Markdown de ce dossier, puis un fichier global `agent-soli-lms.prompt.md` qui consolide tous les prompts.

---

## Installation

1. **Cloner le dépôt (si nécessaire)**

   ```bash
   git clone https://votre-repo-git.git
   cd nom-du-projet
   ```

2. **Placer `merge-md.js` à la racine du projet**
   Assurez-vous que le script `merge-md.js` (et éventuellement `watch-md.js` si vous souhaitez surveiller en temps réel) se trouve au même niveau que vos dossiers `Context/`, `Fonctionnalité/`, `Identité/`, etc.

3. **Installer Node.js (si non déjà installé)**

   * Version recommandée : ≥ 14

   ```bash
   node -v
   npm -v
   ```

4. **(Optionnel) Installer les dépendances pour le watcher**
   Si vous voulez lancer automatiquement la fusion à chaque modification de fichier `.md`, installez :

   ```bash
   npm install --save-dev nodemon chokidar
   ```

   Aucun paquet n’est nécessaire si vous ne lancez que `merge-md.js`.

5. **Ajouter les scripts NPM**
   Dans votre `package.json`, ajoutez :

   ```json
   {
     "scripts": {
       "merge-md": "node merge-md.js",
       "watch-md": "node watch-md.js"
     }
   }
   ```

---

## Utilisation

* **Exécution manuelle**
  Pour fusionner tous les fichiers Markdown, exécutez :

  ```bash
  npm run merge-md
  ```

  ou directement :

  ```bash
  node merge-md.js
  ```

* **Watcher (facultatif)**
  Pour démarrer la surveillance et regénérer automatiquement lors de chaque modification `.md` :

  ```bash
  npm run watch-md
  ```

  (nécessite d’avoir installé `nodemon` ou `chokidar` et d’avoir le script `watch-md.js` à la racine)

* **Résultats**

  * Des fichiers `{NomDossier}.prompt.md` sont créés dans `prompts/` pour chaque dossier de la racine contenant des `.md`.
  * Un fichier unique `agent-soli-lms.prompt.md` rassemble l’ensemble des prompts dans `prompts/`.
