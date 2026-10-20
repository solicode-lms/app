# Modification Manuelle de _fields.blade.php

**Problème :**
Les listes déroulantes (select) générées par Gapp n'incluent pas l'attribut `data-color` par défaut. Par conséquent, l'affichage des couleurs dans l'interface (UI/UX) pour les entités ayant un `sys_color_id` (comme `EquipeProjet`) ne fonctionne pas correctement dans les formulaires d'édition ou de création générés.

**Solution temporaire appliquée :**
Modification manuelle du fichier généré `c:\AppServer\solicode-lms\modules\PkgCreationTache\resources\views\tache\_fields.blade.php` pour injecter manuellement le `data-color` dans les balises `<option>` de l'équipe de projet :
```blade
@if(method_exists($equipeProjet, 'sysColor') && $equipeProjet->sysColor) data-color="{{ $equipeProjet->sysColor->hex }}" @endif
```

**Action requise pour Gapp (Intégration permanente) :**
Il est nécessaire de mettre à jour le stub de Gapp responsable de la génération des vues de formulaires (`_fields.blade.php.stub`). Le générateur doit :
1. Détecter si l'entité étrangère liée (ex: `EquipeProjet`) possède la relation `sysColor` (par exemple via une vérification dans les métadonnées ou le modèle).
2. Si c'est le cas, injecter automatiquement l'attribut `data-color` dans la boucle de rendu des options (`<option>`) pour que `FormUI.js` puisse styliser le rendu natif du select via Select2.
