# Capacité : scopeDataInEditContext (Métadonnée Gapp)

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
