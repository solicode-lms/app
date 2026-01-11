# 🧱 Contexte du Projet Solicode LMS

## 🔹 Présentation de l’application

* **Finalité** :
  **Sollms** est une plateforme pédagogique dédiée à la **gestion des projets de formation**, à l’**attribution et au suivi des tâches**, ainsi qu’à l’**évaluation des compétences** dans un cadre éducatif structuré.

* **Public cible** :
  * **Formateurs** : création et pilotage de projets, suivi des réalisations, évaluation des apprenants.
  * **Apprenants** : réception et exécution des tâches, consultation de la progression individuelle.
  * **Administrateurs** : supervision globale, gestion des rôles et affectations, configuration système.

* **Environnement pédagogique** :
  * Basé sur une logique d’**apprentissage par projet**.
  * Encourage l’**autonomie**, la **responsabilisation** et la **progression par compétences**.

---

## 🔹 Stack technique

* **Framework** : Laravel 11 (avec structure modulaire)
* **Interface d’administration** : AdminLTE v3.2.0
* **Base de données** : MySQL
* **Technologies complémentaires** :
  * Blade, Eloquent ORM, Artisan, Composer.
  * Git (sous-modules).

---

## 🔹 Modules Fonctionnels

L’application est structurée autour de plusieurs modules fonctionnels spécialisés :
* **PkgAutorisation** : Gestion utilisateurs, rôles, permissions.
* **PkgFormation** : Organisation parcours pédagogiques (filières, modules).
* **PkgApprenants** : Gestion apprenants, groupes.
* **PkgCompetences** : Définition et évaluation compétences.
* **PkgCreationProjet** : Création projets pédagogiques.
* **PkgRealisationProjets** : Suivi opérationnel projets.
* **PkgGestionTaches** : Gestion tâches (assignation, suivi workflow).
* **PkgValidationProjets** : Validation tâches/projets par évaluateurs.
* **PkgAutoformation** : Parcours individualisés.
* **PkgWidgets** : Tableaux de bord dynamiques.
* **PkgNotification** : Alertes et messages.
* **PkgGapp** : Métadonnées dynamiques.
* **Core** : Composants transversaux.

Des dépendances logiques existent entre les modules (Tâches -> Projets -> Formateurs).
