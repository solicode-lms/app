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

## 4. linksAction
- **Rôle** : Ajouter un bouton d'action contextuel dans l'index (TableUI) pour rediriger vers une vue personnalisée (avec des paramètres dynamiques).
- **Format JSON** :
```json
[
  {
    "actionName" : "[ActionName]",
    "ordre": 1,
    "icon": "[FontAwesomeIcon]",
    "tooltip": "[TooltipText]",
    "route": "[laravelRoute]",
    "params": {
      "[paramKey]": "[paramValue]"
    },
    "class": "btn btn-default btn-sm actionEntity",
    "permission": "[permissionName]"
  }
]
```
