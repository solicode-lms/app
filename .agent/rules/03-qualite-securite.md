# Qualité et Sécurité

## Standards de Code
- **PHP** : PSR-12 (Appliqué par Laravel Pint).
- **Commits** : Conventionnal Commits (ex: `feat: ajout module auth`, `fix: correction bug login`).
- **Nomenclature** : 
    - Classes : PascalCase.
    - Méthodes / Variables : camelCase.
    - Tables BDD : snake_case (pluriel).

## Sécurité
- **Validation** : Toujours utiliser les FormRequest pour la validation des données entrantes.
- **Sanitization** : Échapper les outputs Blade (`{{ $var }}`) pour éviter XSS.
- **Autorisation** : Vérifier les permissions via Spatie Permission avant chaque action critique.
- **Données Sensibles** : Ne jamais commiter de secrets (.env).

## Workflow de Développement (Git)
1. Créer une branche pour chaque fonctionnalité/fix.
2. Tester localement (Unit & Dusk si possible).
3. Soumettre une PR (ou merge request) pour review.
