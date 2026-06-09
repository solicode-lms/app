# Workflow : Expert Couche Service (/expert-service-layer)

## 📌 Présentation
- **Description** : Workflow d'exécution du skill `expert-service-layer`. Permet à l'agent de mobiliser son expertise sur l'architecture des Services, l'utilisation des Traits et la manipulation des données sans altérer la logique du générateur Gapp.
- **Déclencheurs** : `/expert-service-layer`

---

## ⚡ Menu Interactif (Actions du Skill)

L'utilisateur peut demander l'une des actions suivantes. L'agent **DOIT** exécuter strictement l'action correspondante déléguée au skill associé.

> **Instruction pour l'Agent** : 
> "Délègue ces actions au skill `expert-service-layer` en appliquant les protocoles définis dans son `SKILL.md`."

### Action A : Analyser la couche Service d'une entité
- **Mots-clés** : `analyse`, `structure`, `inspecter`, `baseservice`
- **Exécution** : L'agent examine un contexte de Service spécifique (`[Model]Service`), ses Traits et les fonctions métiers existantes en s'appuyant sur la structure de `BaseService`.

### Action B : Personnaliser le tri par défaut
- **Mots-clés** : `tri`, `trier`, `sort`, `defaultSort`
- **Exécution** : L'agent implémente ou modifie la méthode `defaultSort($query)` dans la classe de service finale ou son GetterTrait pour changer le tri par défaut des listes.

### Action C : Personnaliser les filtres d'indexation
- **Mots-clés** : `filtre`, `filtrer`, `filter`, `initFieldsFilterable`
- **Exécution** : L'agent définit ou personnalise la méthode `initFieldsFilterable()` pour ajouter des filtres (standard, polymorphe ou dynamique en cascade) dans la vue d'index.

### Action D : Surcharger les Hooks CRUD
- **Mots-clés** : `hook`, `beforeCreateRules`, `afterUpdateRules`, `beforeDeleteRules`
- **Exécution** : L'agent implémente des règles de validation métier ou des effets de bord dans le Trait CRUD (`[Model]CrudTrait.php`) à différentes étapes du cycle de vie de l'entité.
