---
name: sys-conception
description: Expert de l'analyse métier, de la conception architecturale, et de la maintenance des diagrammes de classe et cahiers des charges.
---

# Skill : Expert Conception & Architecture (Sys)

## 🎯 Périmètre Global
**Mission** : Assurer la cohérence et l'évolution de l'architecture fonctionnelle et technique (Conception UML, Spécifications) du projet Solicode LMS. Ce skill centralise la gestion des fichiers contenus dans le dossier `cahiers-charges/`.

## 🚫 Interdictions Globales
1. **Dossier Cible** : Ne jamais modifier de code source PHP/Blade. Ce skill opère **uniquement** sur les fichiers Markdown (`.md`) et les schémas du dossier `cahiers-charges/` (ou équivalents de documentation de conception).
2. **Cohérence UML/Texte** : Ne jamais ajouter une règle métier dans le texte sans vérifier qu'elle se reflète dans le diagramme de classes (et inversement), à l'exception des issues non développées.
3. **Miroir du Code (RÈGLE STRICTE)** : Le diagramme de classe Mermaid doit refléter **strictement** le code réel existant de l'application. Ne jamais modifier le diagramme pour y ajouter des concepts futurs. La modification du diagramme se fait *uniquement* après la réalisation effective de l'issue dans le code. Les propositions de modifications doivent être documentées dans un dossier nommé `issues`.
4. **Format des Diagrammes** : Toujours utiliser le format **Mermaid** (`classDiagram`) pour dessiner ou modifier les diagrammes de classes, afin de garantir leur rendu natif dans les fichiers Markdown.

## ⚡ Actions (Orchestration)

### Action A : Révision et Modification de Conception
> **Description** : Intégrer de nouvelles demandes (nouvelles colonnes, nouvelles tables, nouvelles entités) au modèle existant.

- **Points de Contrôle (Règles de conception)** :
  - **Normalisation** : Toute nouvelle entité doit être justifiée. Éviter la redondance de données.
  - **Clés Standard** : Lors de l'ajout d'une entité dans un diagramme Mermaid, toujours s'assurer de la présence d'un identifiant (ex: `+int id`) et de la clé unique conventionnelle (ex: `+String reference`).
  - **Relations** : Expliciter la cardinalité exacte entre les entités (ex: `EntiteA "1" --> "*" EntiteB`).
  
- **Déroulement** :
  1. **Analyse de l'existant** : Lire le cahier des charges et le diagramme de classe actuels du package ciblé.
  2. **Modification UML** : Mettre à jour le bloc ````mermaid ... ```` avec les nouvelles entités, attributs ou relations.
  3. **Modification Texte** : Mettre à jour les sections "Règles de gestion" ou "Description des entités" pour documenter les ajouts effectués sur le diagramme.

### Action B : Création d'une Nouvelle Conception (Nouveau Package)
> **Description** : Générer l'architecture initiale d'un nouveau package métier.

- **Livrables attendus** :
  - Un fichier `Cahier des charges — Module [Nom].md`.
  - Une introduction et l'objectif du module.
  - Un diagramme de classe complet (Mermaid).
  - Le dictionnaire des données et règles de gestion.

### Action C : Rédaction d'une Issue (Demande de modification)
> **Description** : Documenter une demande de modification de l'application ou du modèle de données avant son développement.

- **Points de Contrôle & Checklist des Composants** :
  - Lors de la création d'un fichier d'issue (ex: `ISSUE-002-Type-Question-Enum.md`), le concepteur **doit analyser l'impact de la modification sur tous les composants de l'application**.
  - Il est **obligatoire** de lister les skills nécessaires pour réaliser l'issue en se basant sur la grille d'analyse documentée dans la capacité `capacités/capacité-architecture-composants.md`.
  - Décrire clairement le contexte, les modifications demandées par composant, et les actions post-développement (mise à jour du diagramme).

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `capacités/`*

### 1. `capacité-architecture-composants.md`
- **Rôle** : Fournit la liste exhaustive des composants de l'application et de leurs skills associés, indispensable pour déterminer l'impact transverse d'une issue.

## 🔄 Scénarios d'Exécution

### Scénario 1 : Ajout d'une fonctionnalité ou d'une colonne
*Déclencheur : "Ajoute le champ 'score_minimum' au QCM et mets à jour la conception"*
1. L'agent lit le fichier `cahiers-charges/PkgQcm/...`.
2. Il insère l'attribut `+float score_minimum` dans le diagramme Mermaid sous la classe `Qcm`.
3. Il ajoute un paragraphe expliquant la règle de gestion de ce score dans le document.

### Scénario 2 : Audit de cohérence
*Déclencheur : "Vérifie si le diagramme de PkgQcm correspond bien aux règles décrites"*
1. Lecture analytique croisée du diagramme et des textes de règles.
2. Signalement des éventuelles entités ou relations manquantes dans l'un ou l'autre, et proposition de correction.
