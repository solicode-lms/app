---
trigger: always_on
---

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
- **Fichiers Protégés** :
    - **Classes de Base (`Base/`)** : NE JAMAIS modifier un fichier contenant `Base` dans son nom ou situé dans un dossier `Base/` sous aucune condition. Ces fichiers sont 100% gérés par Gapp.
    - **Classes Enfants (Héritières)** : Ces fichiers sont à la disposition de l'IA pour injecter le code métier. Si un fichier enfant contient la ligne de protection `// Ce fichier est maintenu par ESSARRAJ Fouad`, **l'agent EST AUTORISÉ ET DOIT la supprimer lui-même** pour injecter le code métier.
- **Workflow Gapp** :
    1. Migration BDD (`php artisan migrate`)
    2. Sync Gapp (`gapp meta:sync`)
    3. Regeneration CRUD (`gapp make:crud NomModel`)
- **Héritage** : Ne jamais modifier les classes `Base/`. Toujours surcharger dans la classe enfant (ex: `Tache extends BaseTache`).

## 3. Sécurité
- **Paranoïa** : Valider toutes les entrées (FormRequests) et échapper toutes les sorties (Blade `{{ }`).
- **Permissions** : Vérifier les droits (Spatie) avant toute action sensible.
