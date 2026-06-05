---
name: expert-inline-edit
description: Expert de la configuration et de la validation des champs d'édition en ligne (Inline Edit).
---

# Skill : Expert Inline Edit (expert-inline-edit)

## 🎯 Périmètre Global
**Mission** : Guider l'agent pour configurer l'édition en ligne (Inline Edit) sur les champs des entités et implémenter les contraintes de validation associées, tant au niveau du FormRequest qu'au niveau du service métier.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Intégrité Base** : Ne jamais modifier les fichiers de base générés par Gapp (`Base/`).
2. **Standard de Validation** : Toujours utiliser les mécanismes natifs de validation Laravel (ValidationException et FormRequest).

---

## ⚡ Actions (Orchestration)

### Action A : Configurer l'édition en ligne et ses contraintes
> **Description** : Configurer la visibilité et la validation d'un champ éditable en ligne.
- **Capacités Utilisées** :
  - `capacités/capacité-validation-inline.md`
- **Entrées** : `Nom de l'entité`, `Nom du champ`, `Règles de validation`
- **Sorties** : Fichiers modifiés `[Model]Request.php` et `[Model]Service.php`
- **❌ Interdictions Spécifiques** :
  - Ne jamais modifier un fichier marqué avec le header Gapp sans consentement de l'utilisateur.
- **✅ Points de Contrôle** :
  - Le champ doit être déclaré dans `getInlineFieldsEditable()` dans le service final.
  - La validation doit s'appliquer de manière transparente en cas de modification via l'édition en ligne.
- **📝 Instructions d'Orchestration** :
  1. **Étape 1** : Utiliser `capacité-validation-inline` pour configurer le FormRequest avec la validation dynamique et le mécanisme de repli (fallback) sur la requête globale.
  2. **Étape 2** : S'assurer que le service métier surcharge `beforeUpdateRules` pour jeter une `ValidationException` en cas de dépassement.

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `capacités/`*

### 1. `capacité-validation-inline.md`
- **Rôle** : Décrire la méthode technique pour ajouter la validation dynamique sur un champ éditable en ligne en gérant les particularités d'instanciation manuelle des FormRequests par Gapp.
- **Règles Clés** : Utiliser `request()` pour accéder aux paramètres de route, ajouter la règle dynamique de validation.

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario 1 : Configuration d'une validation dynamique pour l'édition en ligne
1. Ouvrir le fichier FormRequest lié (ex: `[Model]Request.php`) et surcharger la méthode `rules()`.
2. Utiliser le helper `request()` en cas d'instanciation manuelle pour retrouver le modèle en cours d'édition.
3. Appliquer la règle de validation au champ cible dans le tableau `$rules`.
