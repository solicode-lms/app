# Référence Gapp : Catalogue des Métadonnées

Gapp utilise des métadonnées injectées via son interface Web d'administration ou synchronisées dans la base de données.

## 1. `dynamicDropdown`
Permet de configurer des listes déroulantes dépendantes :
- **Clé :** `dynamicDropdown`
- **Exemple de configuration :**
```json
{
  "target": "filiere_id",
  "endpoint": "/api/filieres-by-etablissement",
  "param": "etablissement_id"
}
```

## 2. `scopeDataInEditContext`
Permet de propager les variables de portée (scope variables) dans les formulaires d'édition :
```json
[
  {
    "key": "scope.projet_id",
    "value": "tache.projet_id",
    "modelName": "Tache"
  }
]
```
