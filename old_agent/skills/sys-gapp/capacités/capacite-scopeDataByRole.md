# Capacité : scopeDataByRole (Métadonnée Gapp)

*Note : Les chemins de relation (`relationPath`) doivent TOUJOURS être validés via le skill `db-savoir`.*

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
