# Demande de modification : Gestion de la visibilité dynamique des filtres (targetDynamicDropdown)

## 📌 Contexte
Dans le cadre de l'intégration du module **PkgCreationProjet** avec **PkgRealisationTache**, un filtre par `EquipeProjet` a été ajouté sur la liste des réalisations de tâches.
Toutefois, les équipes dépendent du Projet (ou de l'`AffectationProjet`) sélectionné. Si aucun projet n'est sélectionné, ou si le projet ne contient pas d'équipes, ce filtre ne devrait pas être visible.

Nous avons implémenté la partie backend dans `RealisationTacheGetterTrait` en utilisant la logique `targetDynamicDropdown` pour que le filtre `AffectationProjet` charge dynamiquement les options du filtre `EquipeProjet` par AJAX, et nous avons ajouté une propriété `'hidden' => true` au tableau généré par `generateRelationFilter`.

## 🛠️ Modification JavaScript attendue (Gapp CRUD)
Il est nécessaire d'adapter le framework JS (`resources/js/crud/`) et/ou les vues Blade génériques des filtres pour prendre en charge l'affichage/masquage conditionnel des filtres.

### 1. Ajout de la classe CSS au rendu Blade (optionnel mais recommandé)
Dans la vue qui génère les filtres (ex: `_index.blade.php` ou le composant générique `filter.blade.php`), ajouter une classe CSS `d-none` sur le conteneur du filtre si `$filter['hidden'] == true`.

### 2. Modification de la gestion AJAX des `targetDynamicDropdown`
Dans le script Javascript gérant le `onchange` des filtres (probablement dans la logique qui intercepte les requêtes Fetch/Axios pour populer le dropdown cible) :
- **Après avoir mis à jour les options** du `targetDynamicDropdown` avec la réponse AJAX :
    - Vérifier le nombre d'options dans le `<select>`.
    - S'il n'y a que l'option par défaut (ex: "Tous" ou ""), **masquer** le conteneur parent (le `div.form-group` ou équivalent) en ajoutant la classe `d-none`.
    - S'il y a des options récupérées (nombre d'options > 1), **afficher** le conteneur parent en retirant la classe `d-none`.

### Exemple de logique JS (Pseudo-code)
```javascript
function updateDynamicDropdown(targetSelect, newOptionsHTML) {
    // 1. Mettre à jour le select avec les nouvelles options
    targetSelect.innerHTML = newOptionsHTML;

    // 2. Trouver le conteneur parent du filtre (ex: le div de la grille)
    const filterContainer = targetSelect.closest('.filter-container'); // Remplacer par la bonne classe

    // 3. Masquer ou afficher en fonction des options
    // On suppose que option.length == 1 correspond à l'option "Sélectionner..." ou "Tous"
    if (targetSelect.options.length <= 1) {
        filterContainer.classList.add('d-none');
    } else {
        filterContainer.classList.remove('d-none');
    }
}
```

## 🎯 Impact
- Expérience utilisateur améliorée : Le filtre `Equipe` n'apparaîtra "magiquement" que si un projet comportant des équipes est sélectionné.
- Code générique : Cette amélioration du framework Gapp profitera à n'importe quel autre filtre conditionnel nécessitant de rester masqué tant qu'il est vide.
