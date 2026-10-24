# Guide d'Utilisation : Skill `context-generator`

> **Compétence :** `context-generator`  
> **Portée :** Locale (Incubation Solicode LMS)  
> **Catégorie :** Analyse Statique & Documentation Contextuelle IA  
> **Fichier technique IA :** [`SKILL.md`](SKILL.md)

---

## 🎯 1. À quoi sert ce Skill ?

Le skill `context-generator` outille l'Agent IA pour qu'il comprenne instantanément n'importe quel package modulaire de Solicode LMS (`modules/Pkg*`).

Il effectue une analyse statique approfondie du package et produit sa fiche **`AI_CONTEXT.md`** récapitulant :
- Les entités du domaine et leurs relations Eloquent.
- Les services métiers, leurs hooks CRUD et leurs traits spécialisés.
- Les contrôleurs HTTP et les formulaires de validation `FormRequest`.
- Les vues Blade et les configurations des composants `TableUI` et `FilterUI`.
- Les particularités Gapp et les règles de gestion spécifiques.

---

## 💬 2. Comment l'Invoquer ? (Prompts Clés en Main)

- *« Analyse le package modules/PkgFormation et génère sa cartographie AI_CONTEXT.md. »*
- *« Mets à jour le AI_CONTEXT.md de modules/PkgRealisationProjet suite à l'ajout des sous-groupes. »*
- *« Scanne tous les services et contrôleurs de PkgApprenants pour documenter son contexte IA. »*

---

## 📋 3. Format du Livrable `AI_CONTEXT.md`

Le document produit est structuré pour une consommation optimale par l'Agent IA :
1. **Identité du Package :** Nom, namespace, dépendances avec d'autres packages.
2. **Modèles & Persistance :** Tables, colonnes clés, relations `belongsTo` / `hasMany`.
3. **Services & Métier :** Méthodes publiques, hooks CRUD (`beforeCreateRules`, etc.).
4. **Interface & Vues :** Présence de vues `custom/`, tables TableUI actives.
5. **Garde-fous Locaux :** Spécificités et pièges à éviter dans ce module.
