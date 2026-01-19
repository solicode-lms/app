# Règles Spécifiques - Gapp & Métadonnées (Présentation)

> Ce fichier contient les directives avancées pour l'interaction avec le générateur Gapp côté Présentation (Vues, Contrôleurs).

## 1. Filtrage Contextuel des Combos (scopeDataInEditContext)

Lorsqu'il est nécessaire de filtrer les données d'une liste déroulante (select/combo) dans un formulaire d'édition pour qu'elle n'affiche que les éléments liés au contexte de l'objet parent ou courant :

**IL FAUT PROPOSER d'ajouter une métadonnée `scopeDataInEditContext`** pour le champ concerné dans la définition de l'entité.

### Format de la configuration JSON

Le paramètre doit être un tableau JSON contenant la logique de filtrage :

```json
[
  {
    "key": "scope.{RelationCible}.{ChampCible}",
    "value": "{ChampSource}",
    "modelName": "{ModeleSource}"
  }
]
```

- **key** : Définit le scope à appliquer sur le modèle cible.
- **value** : Le nom du champ dans le modèle source (celui qu'on édite) dont la valeur servira de filtre.
- **modelName** : Le nom du modèle source en cours d'édition.

### Exemple Concret

**Besoin** : Dans le formulaire `Tache`, le champ `livrable_id` ne doit proposer que les livetables associés au même projet que la tâche.

**Configuration Metadata** :
- **Entité** : `Tache`
- **Champ** : `livrable_id`
- **Metadata Type** : `scopeDataInEditContext`
- **Value (JSON)** :
  ```json
  [
    {
      "key": "scope.livrable.projet_id",
      "value": "projet_id",
      "modelName": "Tache"
    }
  ]
  ```

---
**Note** : L'ajout de métadonnées se fait généralement via des scripts de migration de données spécifiques ou une interface d'administration.
