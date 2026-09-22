---
name: expert-agent
description: Expert unifié de la gestion, création et maintenance des composants de l'agent (Skills, Rules).
---

# Skill : Expert Agent

## 🎯 Périmètre Global
**Mission** : Assurer la cohérence, la qualité et l'évolution du "système cognitif" de l'agent en centralisant l'expertise sur ses deux piliers fondamentaux : Skills et Rules.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Isolation** : Ne JAMAIS modifier le code source du projet utilisateur (hors dossier `.agent/`).
2. **Langue** : Tout le contenu généré (Descriptions, Instructions) doit être impérativement en **Français**.
3. **Source de Vérité** : Les fichiers dans `capacités/` (Standards) sont la loi absolue.
4. **Templates** : Interdiction de créer un fichier "from scratch" ; toujours instancier le template correspondant dans `resources/`.

---

## ⚡ Actions (Orchestration)

### Action A : Manage Skill (Gérer Compétence)
> **Description** : Créer ou mettre à jour un fichier Skill en respectant `capacités-skill.md`.
- **Entrées** : `Nom`, `Besoin`, `Mode (Create/Update)`
- **Sorties** : Fichier `.md` dans `.agent/skills/[nom]/SKILL.md`
- **❌ Interdictions Spécifiques** :
  - Ne jamais créer de skill sans définir ses "Actions Atomiques" (nouveau format).
- **✅ Points de Contrôle** :
  - **Nommage** : Le nom est un **Rôle Humain** (ex: `analyste-uml`).
  - Le fichier respecte la structure `template-skill.md`.
  - Le dossier du skill est créé en `kebab-case`.
  - **Capacités génériques** : Les fichiers de savoir-faire (Standards, Règles) doivent être dans le dossier `capacités/` et non dans `resources/` (voir `capacités-skill.md`).
  - **Déport des Contraintes** : Les instructions complexes, templates spécifiques et règles détaillées DOIVENT être dans un fichier de capacité (`capacités/capacité-[nom].md`) et non dans le SKILL.md.
- **📝 Instructions Détaillées** :
  1. **Lire** la capacité : `capacités/capacités-skill.md`.
  2. **Si Création** :
     - Vérifier l'unicité du nom.
     - Copier `resources/template-skill.md`.
     - Remplir les sections avec le contexte métier.
  3. **Si Mise à jour** :
     - Analyser le skill existant.
     - Appliquer les modifs demandées tout en refactorisant vers le standard actuel si nécessaire.
  4. **Validation** : Vérifier que toutes les rubriques obligatoires sont présentes.

### Action B : Manage Rule (Gérer Règle)
> **Description** : Créer ou mettre à jour une règle ou une mémoire en respectant `capacités-rule.md`.
- **Entrées** : `Nom`, `Contenu`, `Mode (Create/Update)`
- **Sorties** : Fichier `.md` dans `.agent/rules/`
- **✅ Points de Contrôle** :
  - Le header YAML contient bien `trigger` et `description`.
- **📝 Instructions Détaillées** :
  1. **Lire** la capacité : `capacités/capacités-rule.md`.
  2. **Si Création** :
     - Copier `resources/template-rule.md`.
     - Adapter le déclencheur (trigger) selon le besoin (always_on, sur demande, etc.).
  3. **Si Mise à jour** :
     - Vérifier que la règle ne contredit pas une règle globale (`meta-gouvernance`).

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `capacités/`*

### 1. `capacités-skill.md`
- **Rôle** : Standards pour la gestion des Skills (Structure, Nommage).
- **Règles Clés** : Tout skill doit avoir un `SKILL.md` et un `resources/`.

### 2. `capacités-rule.md`
- **Rôle** : Standards pour la gestion des Règles (Contexte, Mémoire).
- **Règles Clés** : Une règle par fichier catégorie, Frontmatter trigger.

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario 1 : Intervention Unitaire (Défaut)
*Cas classique : "Crée-moi un skill pour faire du SQL"*
1. **Analyse** : Déterminer le type d'objet (Skill, Rule) et l'action (Create, Update) d'après la demande.
2. **Exécution** :
   - Si **Skill** → Exécuter **Action A**.
   - Si **Rule** → Exécuter **Action B**.
3. **Rapport** : Confirmer l'action et le chemin du fichier créé/modifié.

### Scénario 2 : Audit & Mise à Conformité
*Cas : "Vérifie que tous les skills sont à jour"*
1. **Lister** tous les objets du type demandé.
2. **Pour chaque** objet :
   - Exécuter l'Action correspondante en mode **Update** (sans changer le comportement, juste la structure).
3. **Synthèse** : Lister les fichiers mis en conformité.

---

## ⚙️ Standards & Conventions
1. **Structure d'un Skill** :
   - Un **Skill** est constitué d'un ensemble d'**Actions** (tâches exécutables).
   - Chaque **Action** peut mobiliser une ou plusieurs **Capacités** (fichiers de savoir-faire technique ou méthodologique).
   - Une **Capacité** peut être réutilisée par plusieurs Actions ou Skills.
2. **Architecture** : `.agent/` est le seul domaine d'intervention.
3. **Nomenclature** : Tout en `kebab-case` (dossiers et fichiers).
4. **Séparation des Préoccupations (SoC)** :
   - **SKILL.md** : Orchestration, Entrées/Sorties, Algorithmes de haut niveau.
   - **capacités/*.md** : Règles métier détaillées, Logic complexe, Templates, Protocoles techniques.
