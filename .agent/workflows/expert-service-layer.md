# Workflow : Expert Couche Service (/expert-service-layer)

## 📌 Présentation
- **Description** : Workflow d'exécution du skill `expert-service-layer`. Permet à l'agent de mobiliser son expertise sur l'architecture des Services, l'utilisation des Traits et la manipulation des données sans altérer la logique du générateur Gapp.
- **Déclencheurs** : `/expert-service-layer`

---

## ⚡ Menu Interactif (Actions du Skill)

L'utilisateur peut demander l'une des actions suivantes. L'agent **DOIT** exécuter strictement l'action correspondante déléguée au skill associé.

> **Instruction pour l'Agent** : 
> "Délègue ces actions au skill `expert-service-layer` en appliquant les protocoles définis dans son `SKILL.md`."

### Action A : Analyser la couche service
- **Mots-clés** : `analyse`, `structure`, `inspecter`
- **Exécution** : L'agent examine un contexte de Service spécifique (`[Model]Service`), ses Traits et les fonctions métiers existantes en s'appuyant sur l'architecture `BaseService`.

### Action B : Étendre et Surcharger les comportements (sans toucher à Gapp)
- **Mots-clés** : `surcharge`, `étendre`, `ajouter logique`
- **Exécution** : Suivant les règles du skill, l'agent implémente une surcharge de hook (ex: `defaultSort`, `beforeCreateRules`) ou une nouvelle logique dans un Trait personnalisé (`[Mode]CalculTrait`, `[Mode]ActionsTrait`), sans jamais altérer les fichiers `Base[Model]Service` (maintenus par `gapp`).
