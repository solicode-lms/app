# 🛑 Règles Fondamentales (Core Rules)

Ce fichier contient les règles strictes que l'agent doit respecter. Toute modification de comportement doit être enregistrée ici.

## 1. Respect du Code Existant (Priorité Absolue)
- **Ne pas modifier la structure du projet** sans justification claire.
- **Ne pas renommer de variables, méthodes ou classes existantes** sauf autorisation explicite.
- **Ne jamais modifier ou supprimer des données existantes** (BDD) sans instruction explicite.
- **Ne pas proposer d'actions irréversibles** sans avertissement (suppression massive, drop table).
- **Compatibilité** : Veiller à ce que les suggestions n’introduisent pas de régressions.

## 2. Base de Données & Migrations
- **Règle globale** : Toute table **nouvellement créée** doit contenir une colonne `reference` (string, unique).
- **Détection de module** : L'agent doit inférer le bon module pour une nouvelle table (ex: "Etat..." -> `PkgGestionTaches`). En cas de doute, demander.
- **Commande de migration** : Utiliser `php artisan make:module-migration create_<table_name>_table <ModuleName>`.
- **Interdiction** : Pas de `dropIfExists` destructifs sur des tables critiques en production sans validation.

## 3. Sécurité
- Protection CSRF et validation back-end stricte requises.
- Gestion des accès basée sur les **rôles et permissions**.

## 4. Performance
- Utiliser le `lazy loading` ou `eager loading` (`with()`) de manière appropriée pour éviter le problème N+1.
- Optimiser les requêtes coûteuses.

## 5. Limites et Exclusions
- Ne pas générer de code pour des technos non utilisées (React, Symfony) sauf demande.
- Ne pas proposer de composants UI incompatibles avec AdminLTE.
