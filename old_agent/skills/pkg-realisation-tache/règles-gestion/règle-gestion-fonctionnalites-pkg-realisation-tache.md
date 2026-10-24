# Règle de Gestion : Fonctionnalités PkgRealisationTache

Ce document décrit les cas d'utilisation (fonctionnalités) du module PkgRealisationTache.

## 1. Consultation des réalisations
- **Acteur** : Formateur, Apprenant.
- **Description** : Permet de lister les tâches affectées à un apprenant dans le cadre d'un projet. L'apprenant voit ses propres tâches, le formateur voit celles de tous les apprenants.
- **Critères** : Filtrage possible par état, projet ou apprenant.

## 2. Changement d'état d'une tâche
- **Acteur** : Apprenant, Formateur.
- **Description** : Fait avancer une tâche dans son cycle de vie (ex: "À faire" -> "En cours" -> "Terminé").
- **Critères** :
  - L'apprenant ne peut pas modifier un état protégé par `is_editable_only_by_formateur` (ex: "Validé", "Refusé").
  - Tout changement d'état doit être enregistré dans l'historique (`HistoriqueRealisationTache`).

## 3. Ajout de commentaires et feedbacks
- **Acteur** : Formateur, Apprenant.
- **Description** : Ajout d'un commentaire sur la réalisation d'une tâche (`CommentaireRealisationTache`).
- **Critères** : Permet de donner un feedback formatif avant validation finale.

## 4. Évaluation et validation
- **Acteur** : Formateur (ou Évaluateur).
- **Description** : Validation finale du travail rendu. Renseignement des champs `note`, `is_live_coding`, et `remarques_formateur`.
- **Critères** : Clôture la réalisation de la tâche.

## 5. Sélection Automatique pour le Live Coding (50%)
- **Acteur** : Système (Automatique).
- **Description** : Dès que l'affectation d'une tâche franchit 50% de réalisation, le système désigne un apprenant pour effectuer un live coding.
- **Critères** :
  - Uniquement pour les tâches paramétrées comme nécessitant un live coding.
  - Sélectionne l'apprenant éligible ayant le moins participé aux live codings dans l'année scolaire.
  - Bascule l'état de la réalisation sélectionnée vers `READY_FOR_LIVE_CODING`.

