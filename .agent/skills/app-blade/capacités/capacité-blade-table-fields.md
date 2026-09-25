# Capacité : Personnalisation des DataTables (Fields)

## 1. Rôle et Emplacement
Le dossier `custom/fields/` est utilisé pour spécifier la personnalisation du rendu du contenu d'une colonne dans la liste `_table.blade.php`.
- **Chemin cible** : `custom/fields/[nom_du_champ].blade.php`

## 2. Règle Critique de Génération Gapp
**Les "Fields" nécessitent une régénération !**
Contrairement aux formulaires, Gapp doit physiquement intégrer le code de la vue custom dans son code généré `_table.blade.php`.
- Pendant l'exécution de la commande de génération, Gapp scanne le dossier `custom/fields/`.
- S'il trouve `tache.blade.php`, il va écrire : `@include('...custom.fields.tache', ['entity' => $item])` dans le tableau principal au lieu de son formatage par défaut.
- **Action Obligatoire** : Toute création ou renommage dans `custom/fields` impose à l'IA d'exécuter (ou demander l'autorisation d'exécuter) : `php artisan gapp make:crud NomModele`.

## 3. Format du code
Le fichier Blade ne reçoit que la portion HTML qui doit être affichée à l'intérieur de la colonne (le `<td>` ou simplement son contenu typographique). Il hérite du scope local (`$entity` ou nom du modèle de la boucle).
