# Capacité : Modification du tri par défaut (defaultSort)

## 🎯 Rôle
Permet de définir ou de modifier la règle de tri appliquée par défaut aux requêtes de liste (index) d'un modèle lorsque l'utilisateur ne spécifie aucun tri dans l'interface.

## ⚙️ Mécanisme Standard
Par défaut, le trait `SortTrait` (`Modules\Core\Services\Traits\SortTrait.php`) applique le tri suivant :
1. Si la table possède une colonne `ordre`, il trie par `ordre` ascendant (`asc`).
2. Sinon, il trie par `updated_at` descendant (`desc`).

## 🛠️ Comment surcharger le tri par défaut

Pour appliquer un tri personnalisé, surchargez la méthode `defaultSort` dans la classe de service finale `[Model]Service` ou dans son trait associé (ex: `[Model]GetterTrait`).

### Signature de la méthode
```php
public function defaultSort($query)
{
    return $query->orderBy('nom_du_champ', 'asc|desc');
}
```

### Tri multi-colonnes
Il est possible de combiner plusieurs tris :
```php
public function defaultSort($query)
{
    return $query->orderBy('priorite', 'asc')
                 ->orderBy('created_at', 'desc');
}
```

### ⚠️ Bonnes Pratiques et Contraintes
- **Namespace** : Toujours utiliser le constructeur de requêtes d'Eloquent passé en argument `$query`.
- **Nom de la table** : Si vous faites des jointures dans vos filtres ou requêtes, préfixez le champ par le nom de la table pour éviter les ambiguïtés SQL (ex: `orderBy($query->getModel()->getTable() . '.nom_du_champ', 'asc')`).
- **Niveau d'implémentation** : N'éditez jamais la classe `Base[Model]Service` ni le trait `SortTrait`. Tout doit être dans `[Model]Service` (ou son trait enfant).
