# 🎨 Styles de Code et Conventions

## 1. Standards de Développement
- **PSR-12** : Respect strict des standards de codage PHP.
- **SOLID** : Application rigoureuse des principes SOLID.
- **Nommage** : Noms explicites, cohérents, en anglais pour le code (variables, méthodes), mais respect des conventions existantes (parfois franglais dans le métier, s'aligner sur l'existant).

## 2. Architecture des Services
- Architecture basée sur des **services métier** (`Modules/PkgX/Services`).
- **Héritage** : Utiliser `BaseService`, `BaseFormateurService`.
- **Règle** : Ne pas mettre de logique métier lourde dans les Contrôleurs. Déléguer aux Services.
- **Méthodes Service** :
    - Utiliser le `CrudTrait` (all, find, create, update...) en priorité.
    - Créer des méthodes spécifiques uniquement si besoin métier réel.
    - Utiliser `$this->model`, `$this->viewState`.

## 3. Format de Réponse
- **Bloc de code** : Bien formaté, prêt à l'emploi.
- **Explication** : Ligne par ligne ou fonctionnelle.
- **Conseils** : Ajouter des recommandations pertinentes.

## 4. Intégration AdminLTE
- Utiliser les composants visuels AdminLTE v3.
- Tableaux responsives, filtres dynamiques (Select2).
- Affichage conditionnel selon rôles.

## 5. Documentation
- Documenter chaque méthode complexe (PHPDoc).
- Expliquer les choix d'architecture dans les réponses.
