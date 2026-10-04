# Issue : Conception d'un Projet par Groupe (Sous-groupes d'apprenants)

## 1. Contexte et Problématique
Actuellement, un **Projet** (modèle de projet) est assigné via une **AffectationProjet** à un groupe global d'apprenants (ex: une classe).
Cependant, dans la réalité pédagogique, les apprenants travaillent souvent en sous-groupes (équipes de travail) sur un projet.
Le formateur a besoin de :
1. Créer des sous-groupes de travail pour un projet.
2. Affecter des apprenants à ces sous-groupes.
3. Lors de la création des **Tâches** du projet, pouvoir affecter une tâche à un sous-groupe spécifique.
4. Lors de la génération des **RealisationTache**, celles-ci ne doivent être créées que pour les apprenants du sous-groupe assigné à la tâche.

**Conclusion de l'analyse :**
Si les sous-groupes sont créés dans l'AffectationProjet, et que les Tâches appartiennent au Projet, il y a un décalage structurel (une tâche du modèle ne peut pas cibler un sous-groupe d'une affectation spécifique).
La solution est donc de s'orienter vers la **conception d'un "Projet par groupe"**. Ainsi, le projet n'est plus seulement un modèle générique, mais devient une instance spécifique pour une équipe/groupe d'apprenants. L'affectation devient alors une simple planification. Le groupe de travail (ou sous-groupe) doit être rattaché directement au **Projet**, soit lors de sa création, soit après la planification.

## 2. Propositions d'Architecture (Modèle de données)

### Option 1 : Projet = Instance pour une Équipe (Recommandée)
Un `Projet` gère directement ses propres `EquipeProjet` (sous-groupes).
Les `Tache`s du projet peuvent optionnellement être affectées à un groupe spécifique si le projet contient plusieurs sous-groupes.

### Modifications demandées
1. **Module `PkgCreationProjet` (Projet)** :
   - Ajout d'une clé étrangère `groupe_id` (nullable) dans la table `projets` pour lier le projet au groupe global (classe).
   - Création d'une entité `EquipeProjet` (Sous-groupe) qui appartient à un `Projet`.
   - Création de la relation Many-to-Many entre `EquipeProjet` et `Apprenant`.
2. **Module `PkgCreationTache` (Tache)** :
   - Ajout d'une clé étrangère `equipe_projet_id` (nullable) dans la table `taches` pour lier la tâche à un sous-groupe.
3. **Module `PkgRealisationTache` (RealisationTache)** :
   - Le service de création de `RealisationTache` doit filtrer les apprenants : si la tâche a un `equipe_projet_id`, créer les réalisations uniquement pour les apprenants de cette équipe. Sinon, créer pour tous les apprenants assignés au projet.

## 3. Diagramme de Classes (Mermaid) - Proposition

> **Note :** Ce diagramme est une proposition pour validation avant développement, conformément à la règle de conception.

```mermaid
classDiagram
    namespace PkgCreationProjet {
        class Projet {
            id
            titre
            description
            reference
            +int groupe_id
        }
        class EquipeProjet {
            +int id
            +int projet_id
            +String nom
            +String reference
        }
    }
    
    namespace PkgCreationTache {
        class Tache {
            +int id
            +int projet_id
            +int equipe_projet_id
            +String nom
            +String reference
        }
    }

    namespace PkgApprenants {
        class Apprenant {
            +int id
            +String nom
        }
        class Groupe {
            +int id
            +String nom
        }
    }
    
    namespace PkgRealisationTache {
        class RealisationTache {
            +int id
            +int tache_id
            +int apprenant_id
        }
    }

    Projet "*" --> "0..1" Groupe : appartient à
    Projet "1" --> "*" EquipeProjet : contient
    EquipeProjet "*" --> "*" Apprenant : est composé de
    Projet "1" --> "*" Tache : possède
    Tache "*" --> "0..1" EquipeProjet : est assignée à
    Tache "1" --> "*" RealisationTache : engendre
    RealisationTache "*" --> "1" Apprenant : est réalisée par
```

## 4. Impact sur les Composants et Skills Nécessaires

Pour implémenter cette issue, les skills suivants seront mobilisés :
- **Générateur Gapp** : C'est le générateur `gapp` qui portera les modifications dans les Modèles (dossier Base) et créera/régénérera les CRUD (Contrôleurs, Vues, Routes).
- `app-migration` : Création de la table `equipe_projets`, table pivot `apprenant_equipe_projet`, modification de `taches` (ajout `equipe_projet_id`) et modification de `projets` (ajout `groupe_id`).
- `app-model` : Le générateur `gapp` s'occupe des modèles de base, mais ce skill servira pour les relations ou méthodes personnalisées dans les classes enfants.
- `sys-gapp` : Synchronisation des métadonnées Gapp pour prendre en compte les nouveaux champs et entités.
- `app-service` : Mise à jour de `TacheService` et `RealisationTacheService` pour appliquer la logique métier (filtrage des apprenants).
- `app-controller` & `app-blade` : Mise à jour de l'interface pour permettre l'ajout d'équipes et l'affectation d'apprenants, et sélection de l'équipe dans le formulaire de création de tâche.

## 5. Règles de Gestion (Business Rules)
- **RG1** : Un formateur peut créer des `EquipeProjet` (sous-groupes) dans un `Projet`.
- **RG2** : Un apprenant ne peut appartenir qu'à une seule équipe pour un même projet.
- **RG3** : Lors de la création d'une `Tache`, le formateur peut sélectionner une `EquipeProjet`. Si sélectionnée, la tâche est exclusive à ce groupe.
- **RG4** : La génération des `RealisationTache` pour une `Tache` spécifique à une équipe ne cible que les apprenants membres de cette équipe.

## 6. Migration des Données Existantes (Script de mise à jour)
Afin de maintenir la cohérence de l'historique, un script (commande Artisan ou Seeder spécifique) devra être créé pour mettre à jour les projets existants :
- **Règle de migration** : Pour chaque projet existant, affecter le `groupe_id` en récupérant le groupe de la première `AffectationProjet` qui y est liée. S'il n'y a pas d'affectation, utiliser le premier groupe affecté par le formateur propriétaire du projet.

## 7. Plan de Réalisation (Sprints)

### Sprint 1 : Base de données, Modèles et Migration
- **Objectif** : Mettre en place l'infrastructure de données et mettre à jour l'historique.
- **Tâches** :
  - Créer les migrations pour ajouter `groupe_id` à `projets`, créer la table `equipe_projets`, et la table pivot `apprenant_equipe_projet`.
  - Exécuter le script de migration de données pour associer les projets existants à leurs classes (groupes).
  - Synchroniser via Gapp (`gapp meta:sync`) et générer les CRUD de base (`gapp make:crud EquipeProjet`).

### Sprint 2 : Interfaces et Affectations
- **Objectif** : Permettre au formateur de structurer ses équipes et ses tâches.
- **Tâches** :
  - Dans l'interface de gestion de Projet, ajouter l'onglet/vue pour créer des `EquipeProjet` et y affecter des apprenants.
  - Ajouter le champ `equipe_projet_id` dans la table `taches` (migration).
  - Modifier le formulaire de création/édition d'une Tâche pour permettre de sélectionner optionnellement une `EquipeProjet`.

### Sprint 3 : Logique Métier (Génération des réalisations)
- **Objectif** : Appliquer les règles de gestion sur la génération des `RealisationTache`.
- **Tâches** :
  - Modifier le service métier (`TacheService` / `RealisationTacheService`) responsable de l'initialisation des réalisations.
  - **Logique** : 
    - Si la tâche a un `equipe_projet_id`, récupérer uniquement les apprenants liés à cette équipe.
    - Sinon, récupérer tous les apprenants du projet (via le `groupe_id` global).
    - Générer les `RealisationTache` pour cette sélection d'apprenants.
  - Effectuer les tests pour valider les règles RG3 et RG4.
