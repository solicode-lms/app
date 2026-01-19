# 🛑 Règles Fondamentales (Core Rules)

Ce fichier contient les règles strictes que les 3 Agents du système (Business, Data, Presentation) doivent respecter. Toute modification de comportement doit être enregistrée ici et s'applique globalement.

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

## 6. Générateur Gapp & Code Maintenu
- **Gapp Protected Files** : NE JAMAIS modifier manuellement un fichier contenant `// Ce fichier est maintenu par ESSARRAJ Fouad` au début sans autorisation.
    - Si une modification est nécessaire au-delà du CRUD standard, l'agent DOIT demander : *"Ce fichier est sous maintenance Gapp. Voulez-vous supprimer l'en-tête de maintenance pour que je puisse le modifier manuellement ?"*.
    - Une fois l'en-tête supprimé par l'agent (après accord), le fichier sort du cycle de maintenance automatique Gapp.
- **Gapp & Migrations** : NE JAMAIS exécuter les migrations (`php artisan migrate`) soi-même. C'est la responsabilité du développeur.
- **Gapp Sequence** : Suite à une modification de structure BDD, le workflow obligatoire est : `php artisan migrate` -> `php artisan gapp meta:sync` -> `php artisan gapp make:crud NomModel`.
- **Scope Gapp** : Le générateur gère tous les fichiers dans les sous-dossiers `Base/` (Models, Requests, Exports, Imports, Controllers) ainsi que les Vues standard.
- **Stratégie de Modification** :
    - **Classes** : Ne jamais modifier les classes `Base`. Utiliser l'**héritage** (override) dans la classe correspondante (ex: `Tache extends BaseTache`).
    - **Vues** : Ne pas modifier les vues de base. Utiliser l'**héritage de vues Blade** (`@extends`, `@section`) pour personnaliser.

## 7. Protocole de Communication Inter-Agents (Le BUS)
- **Vérification Systématique** : À son réveil (initialisation), l'agent DOIT lire son fichier `communication_agent_{NOM}.md`.
    - Si des tâches sont `[EN ATTENTE]`, il doit les **lister** à l'utilisateur et demander : *"Souhaitez-vous que je traite ces tâches en attente maintenant ?"*.
    - Il NE DOIT PAS commencer le traitement sans cette **confirmation explicite**.
- **Principe de Tâche** : Toute demande inter-agent doit être formalisée comme une tâche dans le fichier `communication_agent_*.md` du destinataire.
- **Suivi d'État** : L'agent destinataire DOIT mettre à jour l'état de la tâche dans son fichier de communication.
    - `[EN ATTENTE]` : La tâche a été reçue mais pas encore traitée.
    - `[EN COURS]` : L'agent travaille dessus actuellement (réponse immédiate).
    - `[TERMINÉ]` : La tâche est réalisée.
    - `[BLOQUÉ]` : L'agent ne peut pas avancer (préciser la raison).
- **Notification** : Une fois la tâche `[TERMINÉ]`, l'agent exécutant doit notifier l'agent demandeur dans le fichier communication de ce dernier (ex: "TÂCHE-001 Terminée").
