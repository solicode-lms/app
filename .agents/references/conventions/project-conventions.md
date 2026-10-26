# Contexte Projet : Solicode LMS (Conventions Locales)

> **PRIORITÉ 1 (Contexte Local Inviolable)**  
> Ce document définit les conventions propres et exclusives au projet Solicode LMS.

## 1. Structure Modulaire (`modules/`)
Le projet contient 19 packages modulaires dans `modules/` :
- **Noyau :** `Core` (BaseModel, BaseService, AdminController, BlException, ViewState).
- **Domaines Métier :**
  - `PkgApprenants` : Gestion des apprenants, groupes et affectations.
  - `PkgFormation` : Formateurs, filières, années de formation, spécialités.
  - `PkgCreationProjet` & `PkgRealisationProjets` : Projets, affectations, livrables, validations.
  - `PkgCreationTache` & `PkgRealisationTache` : Tâches, commentaires, historique, états.
  - `PkgCompetences` & `PkgEvaluateurs` : Évaluations des compétences et grilles d'évaluation.
  - `PkgQcm` & `PkgPasserQcm` : Gestion et passation des questionnaires à choix multiples.
  - `PkgSessions` & `PkgApprentissage` : Sessions de formation et alignement pédagogique.
  - `PkgTableauBord` & `PkgWidgets` : Statistiques, widgets et bordereaux officiels.
  - `PkgAutorisation` & `PkgNotification` : Rôles Spatie, permissions, notifications.
  - `PkgGapp` : Métadonnées et générateur de code.

## 2. Rôles et Droits d'Accès (Spatie)
Les 3 rôles centraux sont :
- `admin` : Super-administrateur (accès complet).
- `formateur` : Responsable pédagogique (accès restreint aux groupes et projets dont il est référent).
- `apprenant` : Utilisateur final (accès restreint à ses réalisations et tâches assignées).

## 3. Gestion du ViewState
Les contrôleurs et services utilisent le composant `ViewState` pour conserver l'état des filtres en session :
- Ne jamais réinitialiser un filtre sans vérifier `setContextKeyIfEmpty()`.
- Respecter les variables de portée (`scopeVariables`) passées à la vue.
