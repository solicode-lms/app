# Capacité : ownedByUser (Métadonnée Gapp)

*Note : Les chemins de relation (`relationPath`) doivent TOUJOURS être validés via le skill `db-savoir`.*

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
