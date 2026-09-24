# Capacité : Métadonnées Gapp

*Note : Les chemins de relation (`relationPath`) doivent TOUJOURS être validés via le skill `db-savoir`.*

## 1. scopeDataInEditContext
- **Rôle** : Restreindre les options d'un select ManyToOne/ManyToMany selon un attribut du formulaire principal en cours d'édition.
- **Format JSON** :
```json
[
  {
    "key": "scope.[ModelApplication].[RelationPath.Attribute]",
    "value": "[RelationPathFromFormModel].[attribute]",
    "modelName": "[FormModelName]"
  }
]
```

## 2. scopeDataByRole
- **Rôle** : Restreindre les options d'un select en fonction du rôle utilisateur (ex: formateur, apprenant).
- **Format JSON** :
```json
[
  {
    "key": "scope.[relationPathFromScopedModel].[attribute]",
    "role": "[roleName]",
    "value": "[sessionStateKey]"
  }
]
```

## 3. ownedByUser
- **Rôle** : Filtrer l'accès complet à un modèle ou préremplir l'ID dans un formulaire, selon l'utilisateur connecté.
- **Format JSON** :
```json
[
  {
    "role": "[roleName]",
    "dataScope": "[filter|scope]",
    "userModelName": "[UserModelName]",
    "ownerRelationPath": "[RelationPathInPascalCase.user]"
  }
]
```
