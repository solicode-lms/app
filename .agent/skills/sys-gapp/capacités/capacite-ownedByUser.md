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
    "userModelName": "User",
    "ownerRelationPath": "[RelationPathInPascalCase].user"
  }
]
```

### ⚠️ Règle Critique (Parsing Eloquent Gapp)
Lors de la configuration de `ownedByUser`, Gapp utilise le `ownerRelationPath` pour générer des requêtes `whereHas` imbriquées et récupérer l'ID de l'utilisateur connecté via la variable `$this->sessionState->get('user_id')`.
1. **`userModelName` DOIT toujours être `User`** (ou le modèle final d'authentification). S'il est fixé à `Formateur` ou `Apprenant`, le générateur va chercher un `formateur_id` en session, ce qui n'est souvent pas le cas (l'ID en session est `user_id`).
2. **`ownerRelationPath` DOIT obligatoirement se terminer par la relation menant au modèle `User`** (ex: `Projet.formateur.user`).
   - *Pourquoi ?* Le dernier maillon de la chaîne `ownerRelationPath` est interprété par Gapp pour pointer vers le champ de vérification final. S'il s'arrête à `Projet.formateur`, Gapp tentera de générer `where('formateur', $id)` au lieu d'utiliser la clé étrangère correcte (générant l'erreur SQL `Unknown column 'formateur'`).
   - En spécifiant `.user` à la fin, Gapp va créer une nouvelle condition `whereHas('user')` qui vérifiera correctement la clé primaire `user_id` de la table d'authentification.
