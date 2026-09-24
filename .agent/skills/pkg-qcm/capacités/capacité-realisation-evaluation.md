# Capacité : Réalisation et Évaluation — PkgQcm

## 1. Cycle de Vie d'une Réalisation QCM

Le passage d'un QCM par un apprenant passe par l'entité `RealisationQcm`, dont le cycle de vie est dicté par `EtatRealisationQcm`.

### Étapes Principales
1. **Génération Automatique** : Lors de l'affectation d'un QCM à un projet (`AffectationQcmProjet`), le système crée **automatiquement** une `RealisationQcm` pour chaque apprenant appartenant au groupe affecté.
2. **Initialisation** : L'apprenant démarre le QCM. La `date_debut` de sa `RealisationQcm` (déjà existante) est alors renseignée.
3. **Réponses** : Au fur et à mesure ou à la fin, des `ReponseQcm` sont enregistrées pour chaque question.
4. **Soumission** : L'apprenant valide son QCM. La `date_soumission` est enregistrée.
5. **Évaluation** : Le système calcule la `note_obtenu` en fonction du barème des questions et de la justesse des réponses.

## 2. Calcul du Score (Évaluation)

### Formule
- La note d'une `RealisationQcm` est la somme des points obtenus pour chaque `Question`.
- Pour chaque `Question` :
  - Si l'apprenant a sélectionné la(les) bonne(s) `PropositionReponse` et aucune mauvaise : il obtient le `bareme` de la question.
  - S'il y a des erreurs ou oublis : le calcul des points peut être totalitaire (0 si erreur) ou partiel (selon les règles de l'application).
- La `note_obtenu` totale est stockée dans la `RealisationQcm`.

### Implémentation via Service
- **RÈGLE D'OR** : Le calcul de la note doit se faire dans `RealisationQcmService` (souvent via un hook comme `afterUpdateRules` lors du passage à l'état "Soumis", ou via une méthode dédiée `calculerScore()`).
- Ne jamais calculer le score dans le contrôleur ou dans la vue Blade.
- Une fois le score calculé, la note est persistée.

## 3. Gestion du Temps
- Le modèle `Qcm` possède `duree_minutes` et `is_duree_limitee`.
- Lors de la réalisation, si le QCM est limité dans le temps, la différence entre `date_debut` et l'heure actuelle/soumission ne doit pas dépasser `duree_minutes`.
- La logique de vérification du temps (auto-soumission ou blocage) doit être gérée côté frontend (JavaScript) ET vérifiée côté serveur (dans le FormRequest ou Service).

## 4. Lien avec PkgApprentissage (Notes en cascade)
- **Validation par le formateur** : Après validation du QCM par le formateur, la note des `RealisationUaPrototype` doit être calculée pour chaque Unité d'Apprentissage.
- **Calcul Spécifique à l'UA** : La note et le barème attribués à une `RealisationUaPrototype` ne correspondent **pas** à la note globale du QCM. Ils sont calculés **uniquement** à partir des questions (`Question`) qui appartiennent à la même Unité d'Apprentissage (`unite_apprentissage_id`) que la `RealisationUaPrototype`.
- **Enregistrement de la note** : Cette note spécifique à l'UA doit être enregistrée dans l'objet `RealisationUaPrototype` via les attributs spécifiques `note_qcm` et `barem_qcm`.
- **Saisie automatique** : Si l'affectation du QCM au projet (`AffectationQcmProjet`) possède le paramètre `saise_automatique_note_qcm` à vrai, la note calculée spécifiquement pour l'UA doit également être ajoutée directement dans l'attribut global `note` de `RealisationUaPrototype`.
