# 🛑 Règles Critiques (Golden Rules)

Ces règles sont **INVIOLABLES**. Elles garantissent la stabilité et la maintenabilité du projet Solicode LMS.

## 1. Intégrité du Code & Base de Données
- **AUCUNE Suppression Massive** : Interdiction formelle de proposer des commandes destructives (`DROP TABLE`, `rm -rf`) sans validation explicite et avertissement en majuscules.
- **Respect du Code Existant** : Ne pas modifier la structure, renommer des classes/méthodes ou supprimer du code fonctionnel sans justification validée.
- **Migrations** :
    - Toujours ajouter une colonne `reference` (string, unique) aux nouvelles tables.
    - Ne jamais exécuter `migrate` soi-même. Proposer la commande à l'utilisateur.

## 2. Le Générateur Gapp (CRITIQUE)
Le projet utilise un générateur de code (Gapp).
- **Fichiers Protégés** : NE JAMAIS modifier un fichier commençant par `// Ce fichier est maintenu par ESSARRAJ Fouad`.
    - *Exception* : Si modification indispensable, demander à l'utilisateur de supprimer l'en-tête de protection d'abord.
- **Workflow Gapp** :
    1. Migration BDD (`php artisan migrate`)
    2. Sync Gapp (`php artisan gapp meta:sync`)
    3. Regeneration CRUD (`php artisan gapp make:crud NomModel`)
- **Héritage** : Ne jamais modifier les classes `Base/`. Toujours surcharger dans la classe enfant (ex: `Tache extends BaseTache`).

## 3. Sécurité
- **Paranoïa** : Valider toutes les entrées (FormRequests) et échapper toutes les sorties (Blade `{{ }`).
- **Permissions** : Vérifier les droits (Spatie) avant toute action sensible.

## 4. Communication Inter-Agents
- Si une tâche dépend d'un autre agent, vérifier les fichiers de communication (`communication_agent_*.md`).
