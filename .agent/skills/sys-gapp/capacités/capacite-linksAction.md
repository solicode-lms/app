# Capacité : linksAction (Métadonnée Gapp)

*Note : Les chemins de relation (`relationPath`) doivent TOUJOURS être validés via le skill `db-savoir`.*

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
