---
name: pkg-qcm
description: Expert du module PkgQcm — architecture des questionnaires, gestion des questions/réponses et réalisations.
---

# Skill : Expert QCM

## 🎯 Périmètre Global
**Mission** : Fournir à l'IA une connaissance exhaustive de l'architecture du module PkgQcm : modèle de données des questionnaires (QCM), des questions, des propositions de réponses, et la logique de réalisation et d'évaluation par les apprenants.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Intégrité Gapp** : Ne jamais modifier les fichiers `Base/` générés par Gapp sans autorisation explicite.
2. **Cohérence des données** : Ne pas supprimer ou modifier directement une réalisation (`RealisationQcm`) en base de données sans passer par le service approprié pour gérer les conséquences en cascade (notes, état).
3. **Logique Métier** : La logique de calcul du score d'un QCM doit être déléguée au Service, jamais au niveau du contrôleur ou de la vue.

---

## ⚡ Actions (Orchestration)

### Action A : Comprendre la Structure de Données
> **Description** : Expliquer la hiérarchie des entités du module QCM et leurs relations Eloquent.
- **Règles de Gestion Utilisées** : `règles-gestion/règle-gestion-modele-donnees.md`
- **Entrées** : Question sur une entité ou une relation (ex: Qcm, Question, PropositionReponse)
- **Sorties** : Explication de la hiérarchie et des relations Eloquent

### Action B : Gérer la Réalisation et l'Évaluation
> **Description** : Expliquer ou corriger la logique de passage d'un QCM (RealisationQcm, ReponseQcm) et le calcul du score/état.
- **Règles de Gestion Utilisées** : `règles-gestion/règle-gestion-realisation-evaluation.md`
- **Entrées** : Logique de passage, soumission des réponses, calcul du résultat
- **Sorties** : Explication de la logique, correction du service concerné

### Action C : Affichage Blade (Interfaces QCM)
> **Description** : Guider l'intégration ou la correction des vues affichant les formulaires de QCM, les questions et les résultats.
- **Règles de Gestion Utilisées** : `règles-gestion/règle-gestion-affichage-blade.md`
- **Entrées** : Vue cible (création QCM, passage QCM, résultats)
- **Sorties** : Code Blade corrigé ou implémenté

---

## 🛠️ Règles de Gestion (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `règles-gestion/`*

### 1. `règle-gestion-modele-donnees.md`
- **Rôle** : Décrire la hiérarchie complète des entités QCM et leurs relations.
- **Règles Clés** : Un QCM a plusieurs Questions, qui ont plusieurs Propositions de Réponses.

### 2. `règle-gestion-realisation-evaluation.md`
- **Rôle** : Documenter la logique de réalisation d'un QCM (AffectationQcmProjet, RealisationQcm, ReponseQcm) et les statuts (EtatRealisationQcm).
- **Règles Clés** : Les réponses de l'apprenant sont comparées aux propositions correctes pour calculer un score et déterminer l'état.

### 3. `règle-gestion-affichage-blade.md`
- **Rôle** : Documenter les patterns d'affichage pour les vues de gestion (admin/formateur) et les vues de passage (apprenant).
- **Règles Clés** : Validation côté client et serveur, affichage clair des propositions (radio/checkbox).

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario 1 : "Comment lier des questions à un QCM ?"
1. Lire `règle-gestion-modele-donnees.md`.
2. Identifier les relations entre `Qcm`, `Question` et `PropositionReponse`.
3. Pointer vers le système de relation HasMany et comment créer les entités enfants.

### Scénario 2 : "Calculer le score après la soumission d'un QCM"
1. Lire `règle-gestion-realisation-evaluation.md`.
2. Vérifier comment `ReponseQcm` est stocké pour la `RealisationQcm`.
3. S'assurer que le service calcule les points en comparant les réponses fournies avec la colonne booléenne de justesse de `PropositionReponse`.

### Scénario 3 : "Afficher les résultats d'un apprenant pour un QCM"
1. Lire `règle-gestion-affichage-blade.md`.
2. Vérifier que la variable `$realisationQcm` est disponible avec ses relations chargées.
3. Afficher les points obtenus par rapport au maximum possible, ainsi que l'état de la réalisation.
