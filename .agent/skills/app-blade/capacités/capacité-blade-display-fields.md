# Capacité : Personnalisation de l'affichage d'un attribut (Table et Show)

## 1. Rôle et Emplacement
Le dossier `custom/fields/` est utilisé pour spécifier la personnalisation globale du rendu du contenu d'un attribut (champ) de l'entité. 
Cette personnalisation sera utilisée par Gapp pour afficher le champ dans les listes (`_table.blade.php`) **ET** dans la fiche de consultation (`_show.blade.php`).

- **Chemin cible** : `custom/fields/[nom_du_champ].blade.php`

## 2. Règle Critique de Génération Gapp
**Les "Fields" (attributs) nécessitent une régénération !**
Contrairement aux formulaires (`custom/forms/`) qui sont résolus dynamiquement, Gapp doit physiquement intégrer le code de la vue custom dans son code généré `_table.blade.php` et `_show.blade.php`.
- Pendant l'exécution de la commande de génération, Gapp scanne le dossier `custom/fields/`.
- S'il trouve `note.blade.php`, il va écrire : `@include('...custom.fields.note', ['entity' => $item])` dans le tableau principal et dans la vue Show au lieu de son formatage par défaut.
- **Action Obligatoire** : Toute création, modification ou suppression dans `custom/fields` impose une régénération. **NE JAMAIS exécuter la commande vous-même**. Vous devez impérativement proposer à l'utilisateur d'exécuter la commande suivante :
  ```bash
  gapp make:crud NomModele
  ```

## 3. Format du code
Le fichier Blade ne reçoit que la portion HTML qui doit être affichée (sans balises de conteneur globales du tableau ou de la grille de formulaire).
Il reçoit automatiquement la variable `$entity` qui contient l'instance du modèle courant.

### Exemple de personnalisation (ex: `note.blade.php`)
```blade
@if($entity->note) 
    <span class="font-weight-bold text-success">{{ $entity->note }}</span> 
    <span class="text-muted">/ {{ $entity->projet?->total_notes }}</span>
@else
    <span class="text-muted">—</span>
@endif
```
