---
trigger: always_on
---

# Qualité et Sécurité

## Standards de Code
- **PHP** : PSR-12 (Appliqué par Laravel Pint).
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
2. Ne PAS exécuter de tests unitaires ou de navigateur (PHPUnit, Dusk) après la modification de code. L'exécution des tests est réservée à l'utilisateur.