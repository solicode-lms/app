---
name: expert-mobilisation-affectation
description: Expert de la gestion des affectations de projets et de la mobilisation des Unités d'Apprentissage (UA).
---

# Skill : Expert Mobilisation & Affectation

## 🎯 Périmètre Global
**Mission** : Fournir à l'IA une connaissance exhaustive de l'architecture et des workflows liés à la Mobilisation UA et à l'Affectation Projet. Ce skill encadre la genèse structurelle d'un projet, de la session de formation jusqu'à la cascade de création des tâches et chapitres d'imitation.

### 🚫 Interdictions Globales
1. **Intégrité Gapp** : Ne jamais modifier les fichiers `Base/` générés par Gapp sans autorisation explicite.
2. **Workflow CRUD** : Ne jamais bypasser les hooks `beforeCreateRules` et `afterCreateRules` du `MobilisationUaService`.
3. **Unicité** : Ne jamais autoriser la mobilisation d'une même UA deux fois dans le même projet (vérifié dans `MobilisationUaCrudTrait`).
4. **Calculs d'ordre** : Ne pas modifier manuellement l'ordre des tâches sans passer par les méthodes centralisées de `TacheService` ou `TacheActionsTrait`.

---

## 🏗️ Architecture des Workflows

### 1. Cycle de Création de Projet
Le projet peut être initialisé de deux façons :
- **Manuelle** : Création simple d'un projet vide.
- **Via Session de Formation** : Si `session_formation_id` est fourni, le système extrait l'alignement des UA via `ProjetRelationsTrait::initializeProjectStructure`.

### 2. Flux de Mobilisation des UA
La mobilisation d'une UA est le pivot de la génération d'activités pour l'apprenant :
- **Action Utilisateur** : Ajout manuel d'une UA ou insertion automatique via une session.
- **Cascade afterCreate (MobilisationUaCrudTrait)** :
    - **Niveaux N1 (Apprentissage)** : 
        - Si `is_auto_insert_chapitres` (Projet) est `true` → Création d'une tâche par chapitre réel de l'UA.
        - Si `is_auto_insert_chapitres` est `false` → Création d'une tâche unique "Tutoriel UA" liée à un chapitre virtuel d'imitation (code `TUTO-[UA_CODE]`).
    - **Niveaux N2/N3 (Évaluation)** : Création automatique des tâches "Live coding" (N2) et "Réalisation et présentation" (N3).

### 3. Gestion de l'Ordre des Tâches
- L'ordre (`ordre` et `priorite`) est calculé dynamiquement au moment de la création pour se placer à la fin de la phase correspondante.
- Un réordonnancement global par phase (`Analyse < Apprentissage < Prototype < ...`) est maintenu par `ProjetCrudTrait::reorderTasksByPhase`.

---

## ⚡ Actions (Orchestration)

### Action A : Maintenance de la Mobilisation UA
> **Description** : Superviser la création et la synchronisation des tâches induites par les UA.
- **Composants** : `MobilisationUaCrudTrait`, `TacheActionsTrait`.
- **Règles** : Vérifier l'unicité et le barème (>0) avant insertion.

### Action B : Gestion des Chapitres d'Imitation (Tutoriels UA)
> **Description** : Gérer la création des chapitres virtuels lorsqu'un projet est en mode "imitation".
- **Composants** : `ChapitreService::getOrCreateImitationChapitre`.
- **Règle** : Le chapitre doit avoir le flag `is_imitation_ua` à `true` et un code préfixé par `TUTO-`.

### Action C : Affectation et Réalisation
> **Description** : Gérer le lien entre le Projet et les Groupes/Apprenants.
- **Composants** : `AffectationProjetService`, `RealisationProjetService`.

---

## 🔄 Scénarios d'Exécution

### Scénario 1 : "Ajouter une UA à un projet existant"
1. Vérifier si l'UA n'est pas déjà présente via l'Action A.
2. S'assurer que le barème est enrichi.
3. Vérifier que les tâches N1, N2 et N3 apparaissent bien après l'insertion.

### Scénario 2 : "Pourquoi l'ordre des tâches est incohérent ?"
1. Vérifier les `phase_projets.ordre`.
2. Appeler `reorderTasksByPhase` du `ProjetService` pour restaurer la hiérarchie temporelle du projet.

### Scénario 3 : "Un projet créé par session n'a pas ses tâches"
1. Vérifier `initializeProjectStructure` dans `ProjetRelationsTrait`.
2. Vérifier que la session a bien des `alignementUas` configurés.
