---
name: sys-issue
description: Expert de la rédaction structurée des demandes de modifications et de l'analyse d'impact (Issues).
---

# Skill : Rédacteur d'Issues (Sys)

## 🎯 Périmètre Global
**Mission** : Assurer la documentation claire, exhaustive et normalisée des demandes de modification (Issues) du projet Solicode LMS avant leur réalisation. Ce skill s'assure que chaque issue est prête pour le développement en analysant son impact sur les différents composants (BDD, Controller, Service, Blade).

### 🚫 Interdictions Globales (Règles d'Or)
1. **Dossier Cible & Version Unique** : Une issue ne doit exister qu'en **un seul exemplaire (version unique, aucune duplication)**. Elle est déposée **directement à la racine du dossier du module concerné** dans `cahiers-charges/` (ex: `cahiers-charges/PkgCreationTache/Issue-XXX.md`). Ne jamais créer de dossier intermédiaire `issues/` ni dupliquer une issue.
2. **Couverture Totale** : Ne jamais rédiger une issue sans lister explicitement l'impact sur chaque composant architectural.
3. **Assignation des Skills** : Toujours indiquer précisément quels skills de l'agent devront être invoqués pour traiter l'issue (ex: `app-service`, `sys-gapp`).
4. **Clôture des Issues (RÈGLE STRICTE)** : Les issues résolues/fixées ne doivent jamais être supprimées ni laissées à la racine du module. Elles doivent être systématiquement déplacées dans un dossier d'archive nommé **`issues-closed/`** au niveau du module concerné (ex: `cahiers-charges/PkgCreationTache/issues-closed/Issue-XXX.md`).

---

## ⚡ Actions (Orchestration)

### Action A : Rédaction et structuration d'une Issue
> **Description** : Documenter formellement un bug, une amélioration ou une nouvelle fonctionnalité.
- **Capacités Utilisées** :
  - `.agent/skills/sys-conception/capacités/capacité-architecture-composants.md`
- **Entrées** : `Contexte du besoin`, `Détails techniques`, `Module propriétaire`
- **Sorties** : Fichier `.md` unique déposé directement dans le dossier `cahiers-charges/[NomDuModule]/` (ex: `cahiers-charges/PkgCreationTache/Issue-004.md`).
- **❌ Interdictions Spécifiques** :
  - Rédiger une issue sans l'analyse d'impact ni la liste des skills.
  - Créer des doublons ou placer l'issue dans un sous-dossier `issues/`.
- **✅ Points de Contrôle** :
  - L'issue contient un titre, une description claire, une proposition de solution, et l'impact par composant.
  - L'issue est rattachée au module métier adéquat (ex: gestion des tâches -> `PkgCreationTache`).
- **📝 Instructions d'Orchestration** :
  1. **Choix du Module** : Déterminer le package fonctionnel propriétaire de l'issue (ex: `PkgCreationTache`, `PkgRealisationTache`, etc.).
  2. **Analyse d'Impact** : Analyser le besoin pour déterminer quels composants (Base de données, Modèle, Service, Contrôleur, Vues, UI, Gapp) seront affectés.
  3. **Structuration** : Utiliser un format clair incluant `Description du Problème`, `Proposition de Solution`, `Impact par Composant`, `Skills requis`.
  4. **Création** : Générer le fichier Markdown directement à la racine de `cahiers-charges/[NomDuModule]/`.

### Action B : Clôture d'une Issue
> **Description** : Archiver une issue une fois son implémentation terminée.
- **Entrées** : `Nom du fichier de l'issue`
- **Sorties** : Déplacement du fichier vers `cahiers-charges/[NomDuModule]/issues-closed/`.
- **📝 Instructions d'Orchestration** :
  1. S'assurer que le dossier `issues-closed/` existe pour le module concerné.
  2. Déplacer physiquement le fichier `.md` de `cahiers-charges/[NomDuModule]/` vers `cahiers-charges/[NomDuModule]/issues-closed/`.

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario 1 : Création d'une Issue standard
*Déclencheur : "Crée une issue pour [besoin]"*
1. Le Rédacteur lit la demande, identifie le module fonctionnel responsable (`[NomDuModule]`).
2. Il rédige le fichier `cahiers-charges/[NomDuModule]/[nom_de_l_issue].md` avec une description du problème et la solution proposée.
3. Il ajoute la section "Impact par Composant" et liste les skills qui devront être mobilisés pour réaliser la tâche.

### Scénario 2 : Clôture d'une Issue
*Déclencheur : "L'issue est fixée / réalisée"*
1. L'agent exécute l'Action B pour déplacer le fichier de l'issue correspondante dans le dossier `cahiers-charges/[NomDuModule]/issues-closed/`.

