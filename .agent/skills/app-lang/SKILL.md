---
name: app-lang
description: Expert de la traduction et de la correction des fichiers de langue générés par Gapp (Dossier lang/fr).
---

# Skill : Expert Traduction (Lang)

## 🎯 Périmètre Global
**Mission** : Fixer, traduire et normaliser les fichiers de langue PHP générés par Gapp dans les modules. 
Ces fichiers utilisent par défaut les noms bruts des colonnes de la base de données. L'objectif est de fournir des labels propres et professionnels en français pour l'interface utilisateur.

## 🚫 Interdictions Globales
1. **Intégrité des clés** : Ne pas supprimer de clés existantes dans le fichier généré par Gapp, sauf si elles sont explicitement obsolètes.
2. **Entête Gapp (CRITIQUE)** : Toujours **SUPPRIMER** l'en-tête de protection `// Ce fichier est maintenu par ESSARRAJ Fouad` avant de modifier la traduction. Si cet en-tête est conservé, Gapp réinitialisera le fichier lors de la prochaine génération.
3. **Qualité** : Le français utilisé doit être professionnel, orthographiquement correct et cohérent avec la terminologie de Solicode LMS.

## ⚡ Actions (Orchestration)

### Action A : Traduction d'un fichier Lang Gapp
> **Description** : Traduire un tableau PHP contenant des labels bruts en labels propres, prêts pour l'interface utilisateur.

- **Règles Spéciales (`singular` et `plural`)** :
  - La clé `singular` **DOIT** correspondre au nom de l'entité au singulier avec la bonne majuscule (ex: "Question QCM", "Apprenant", "Projet").
  - La clé `plural` **DOIT** correspondre au nom de l'entité au pluriel (ex: "Questions QCM", "Apprenants", "Projets").
  - Ces deux clés sont critiques : elles sont utilisées par Gapp pour générer automatiquement les titres des vues, les messages de succès et de confirmation (ex: "Ajouter un [singular]" ou "Liste des [plural]").
  
- **Règles pour les champs standards** :
  - **Espaces** : Remplacer les underscores `_` par des espaces.
  - **Casse** : Mettre une majuscule à la première lettre (Capitalize).
  - **Traduction** : Traduire systématiquement les mots anglais ou techniques :
    - `is_actif` ➡️ `Actif`
    - `description` ➡️ `Description`
    - `unite_apprentissage_id` ➡️ `Unité d'apprentissage`
    - `created_at` ➡️ `Date de création` (si présent)

## 🔄 Scénarios d'Exécution

### Scénario 1 : Traduction complète d'un fichier généré
*Déclencheur : "Fixe la traduction du fichier `questionLib.php`"*
1. L'agent lit le fichier demandé (ex: `lang/fr/[entite].php`).
2. Il traduit les champs un par un en appliquant les règles de l'Action A.
3. Il accorde une attention particulière pour garantir que `singular` et `plural` sont corrects et naturels en français.
4. Il modifie le fichier en préservant la structure du tableau PHP.
