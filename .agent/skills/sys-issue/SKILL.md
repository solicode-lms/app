---
name: sys-issue
description: Expert de la rédaction structurée des demandes de modifications et de l'analyse d'impact (Issues).
---

# Skill : Rédacteur d'Issues (Sys)

## 🎯 Périmètre Global
**Mission** : Assurer la documentation claire, exhaustive et normalisée des demandes de modification (Issues) du projet Solicode LMS avant leur réalisation. Ce skill s'assure que chaque issue est prête pour le développement en analysant son impact sur les différents composants (BDD, Controller, Service, Blade).

### 🚫 Interdictions Globales (Règles d'Or)
1. **Dossier Cible** : Ne jamais écrire d'issues directement dans le code source, à la racine, ou dans le dossier todo. Toujours les placer dans le dossier `cahiers-charges/issues/` (ou un sous-dossier de module, ex: `cahiers-charges/issues/PkgGapp/` pour les issues liées au générateur Gapp).
2. **Couverture Totale** : Ne jamais rédiger une issue sans lister explicitement l'impact sur chaque composant architectural.
3. **Assignation des Skills** : Toujours indiquer précisément quels skills de l'agent devront être invoqués pour traiter l'issue (ex: `app-service`, `sys-gapp`).

---

## ⚡ Actions (Orchestration)

### Action A : Rédaction et structuration d'une Issue
> **Description** : Documenter formellement un bug, une amélioration ou une nouvelle fonctionnalité.
- **Capacités Utilisées** :
  - `.agent/skills/sys-conception/capacités/capacité-architecture-composants.md`
- **Entrées** : `Contexte du besoin`, `Détails techniques`
- **Sorties** : Fichier `.md` dans le dossier `cahiers-charges/issues/` (ou un sous-dossier dédié au module, ex: `PkgGapp/`).
- **❌ Interdictions Spécifiques** :
  - Rédiger une issue sans l'analyse d'impact ni la liste des skills.
- **✅ Points de Contrôle** :
  - L'issue contient un titre, une description claire, une proposition de solution, et l'impact par composant.
- **📝 Instructions d'Orchestration** :
  1. **Analyse d'Impact** : Analyser le besoin pour déterminer quels composants (Base de données, Modèle, Service, Contrôleur, Vues, UI, Gapp) seront affectés.
  2. **Structuration** : Utiliser un format clair incluant `Description du Problème`, `Proposition de Solution`, `Impact par Composant`, `Skills requis`.
  3. **Création** : Générer le fichier Markdown dans le sous-dossier approprié (ex: `cahiers-charges/issues/PkgGapp/` si c'est pour Gapp).

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario 1 : Création d'une Issue standard
*Déclencheur : "Crée une issue pour [besoin]"*
1. Le Rédacteur lit la demande et analyse les composants touchés (et le module concerné).
2. Il rédige le fichier `cahiers-charges/issues/[Module]/[nom_de_l_issue].md` avec une description du problème et la solution proposée.
3. Il ajoute la section "Impact par Composant" et liste les skills qui devront être mobilisés pour réaliser la tâche.
