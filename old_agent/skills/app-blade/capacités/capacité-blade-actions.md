# Capacité : Personnalisation des Actions dans un Tableau (Gapp)

## 📌 Contexte
Dans les vues générées par Gapp (notamment `_table.blade.php`), les boutons d'actions spécifiques à chaque ligne (entité) sont encapsulés dans le composant `<x-action-button>`. 

Exemple de rendu par défaut dans `_table.blade.php` :
```blade
<x-action-button :entity="$monEntite" actionName="monAction">
    <a href="..." class="btn btn-info btn-sm">
        <i class="fas fa-play"></i>
    </a>
</x-action-button>
```

## 🛠️ Comment personnaliser une action ?
Il ne faut pas modifier le fichier `_table.blade.php` directement pour changer la logique d'affichage d'un bouton d'action (comme conditionner son affichage).

Il faut plutôt utiliser le mécanisme de résolution automatique des composants de Gapp en créant un fichier dédié dans le dossier `custom/actions/`.

### Étape 1 : Créer le fichier
Créez un fichier portant **exactement le nom de l'action** (la valeur passée à `actionName`) dans le dossier :
`modules/NomDuModule/resources/views/nom_entite/custom/actions/nomAction.blade.php`

### Étape 2 : Écrire la logique
Dans ce fichier, l'entité de la ligne courante est toujours injectée sous la variable `$entity`.

Exemple : `livrablesRealisations.blade.php`
```blade
@if($entity->tache->livrables->isNotEmpty())
<a
    data-toggle="tooltip"
    title="Livrables"
    href="{{ route('livrablesRealisations.index', [
            'showIndex' => true,
            'scope.livrablesRealisation.realisation_projet_id' => $entity->realisation_projet_id,
    ]) }}"
    class="btn btn-default btn-sm context-state actionEntity showIndex d-none d-md-inline d-lg-inline"
    data-id="{{ $entity->id }}">
    <i class="fas fa-file-alt"></i>
</a>
@endif
```

*Remarque : Aucune exécution de commande artisan n'est requise, la résolution par Laravel du composant `<x-action-button>` se fait dynamiquement à l'exécution.*
