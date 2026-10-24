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
- **Non-ambiguïté (Principe de Clarté)** : Le code ne doit JAMAIS être ambigu. Privilégier des valeurs sémantiques explicites (ex: `null` pour représenter l'infini ou l'absence de limite) plutôt que des valeurs de secours ou magiques implicites (ex: `3600` caché dans une condition ternaire). Le code doit exprimer son intention de manière transparente et prédictible.

## Sécurité
- **Validation** : Toujours utiliser les FormRequest pour la validation des données entrantes.
- **Sanitization** : Échapper les outputs Blade (`{{ $var }}`) pour éviter XSS.
- **Autorisation** : Vérifier les permissions via Spatie Permission avant chaque action critique.
- **Données Sensibles** : Ne jamais commiter de secrets (.env).

## Workflow de Développement (Git)
2. Ne PAS exécuter de tests unitaires ou de navigateur (PHPUnit, Dusk) après la modification de code. L'exécution des tests est réservée à l'utilisateur.