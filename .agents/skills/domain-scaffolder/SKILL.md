---
name: domain-scaffolder
description: Conçoit et génère de nouvelles entités métier multi-tiers et migrations Laravel compatibles Gapp avec clé unique reference, sans altérer les classes Base/.
version: 1.0.0
inspiration:
  source: "anthropic/claude-plugins-official"
  plugin: "feature-dev"
  pattern: "domain modeling & multi-tier code scaffolding"
---

# 1. Mission
Ce skill pilote la conception et l'échafaudage (*scaffolding*) architectural de nouvelles entités métier au sein des packages modulaires (`modules/Pkg[Nom]`) de Solicode LMS. Il intervient dès la modélisation conceptuelle des tables pour produire les migrations Laravel conformes, structurer les relations Eloquent, définir les champs de recherche et préparer les contrats requis pour la génération Gapp (`php artisan make:crud`) tout en respectant l'intégrité absolue des classes `Base/`.

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Demande de création d'une nouvelle entité métier ("Crée une entité Inscription dans PkgFormation", "Ajoute un modèle Tag pour les Projets").
  - Modélisation de nouvelles tables de base de données ou de tables pivots ManyToMany.
  - Préparation d'un schéma d'entité pour génération par Gapp (`make:crud`).
- **Quand NE PAS l'activer :**
  - Modification de code ou ajout de méthodes dans une entité existante ➔ Utiliser `feature-dev`.
  - Simple analyse d'impact sans intention de modéliser de nouvelles tables ➔ Utiliser `architecture-planner`.
  - Résolution d'anomalies ou de bugs sur une entité existante ➔ Utiliser `bug-fixer`.

# 3. Entrées & Ressources à Charger
- **Priorité 1 :** `.agents/rules/critical-rules.md` (Golden Rules, colonne `reference`, interdiction de migration aveugle).
- **Priorité 2 :** `.agents/references/architecture/layers-3tiers.md` et `.agents/skills/domain-scaffolder/resources/migrations-and-schema.md`.
- **Priorité 3 :** `.agents/references/gapp/01-conventions-et-garde-fous.md` et `.agents/skills/domain-scaffolder/resources/workflow-regeneration.md`.
- **Priorité 4 :** `modules/Pkg[Nom]/AI_CONTEXT.md` du package cible pour inspecter les relations déjà existantes.

# 4. Contraintes & Règles Inviolables
- **Chargement Préalable Obligatoire de `AI_CONTEXT.md` :** Dès que le package cible est identifié, l'agent DOIT impérativement charger son fichier `modules/Pkg[Nom]/AI_CONTEXT.md` pour appréhender les entités existantes et leurs relations avant de modéliser.
- **Colonne `reference` Obligatoire :** Toute nouvelle table principale DOIT impérativement comporter `$table->string('reference')->unique();`.
- **Interdiction Formelle d'Exécuter `php artisan migrate` :** L'agent produit le code source du fichier de migration, mais la commande `php artisan migrate` doit TOUJOURS être laissée à l'initiative du développeur humain.
- **Préservation des Classes `Base/` :** Les fichiers dans `Base/` sont la propriété exclusive du générateur Gapp. L'agent prépare les métadonnées et le schéma sans jamais éditer manuellement les fichiers du sous-dossier `Base/`.
- **Verrous `ESSARRAJ Fouad` :** Lors de la création des classes enfants PHP (`[Model].php`, `[Model]Service.php`), si le générateur Gapp appose l'en-tête de lock, l'agent doit le supprimer dans la classe enfant pour y insérer la logique métier personnalisée.

# 5. Procédure Pas-à-Pas (Workflow de Conception d'Entité)
1. **Étape 1 : Analyse du Contexte & Attributs :**
   - Identifier le package d'accueil (`modules/Pkg[Nom]/`) et charger obligatoirement son fichier `AI_CONTEXT.md`.
   - Lister les attributs (nom, type, contraintes, nullable, valeurs par défaut).
   - Définir les relations Eloquent (`belongsTo`, `hasMany`, `belongsToMany`).
2. **Étape 2 : Écriture de la Migration Normée :**
   - Générer le fichier de migration dans `modules/Pkg[Nom]/Database/Migrations/`.
   - Inclure systématiquement la colonne `$table->string('reference')->unique()`.
   - Définir les clés étrangères avec contraintes `onDelete('cascade')` ou `restrict`.
3. **Étape 3 : Définition des Modèles (Enfants) :**
   - Rédiger `modules/Pkg[Nom]/Models/[NomEntite].php` étendant `Base[NomEntite]`.
   - Définir la méthode `generateReference(): string` si une règle de génération métier existe.
4. **Étape 4 : Définition de la Couche Service & Filtres :**
   - Rédiger `modules/Pkg[Nom]/Services/[NomEntite]Service.php` étendant `Base[NomEntite]Service`.
   - Configurer `$fieldsSearchable` et pré-configurer `initFieldsFilterable()`.
5. **Étape 5 : Préparation de la Commande Gapp :**
   - Fournir la commande exacte de génération (`php artisan make:crud [Entite]`) pour que le développeur puisse lancer l'échafaudage automatique complet.

# 6. Gestion des Ambiguïtés & Décisions
- Si le type d'une colonne n'est pas précisé dans la demande : privilégier `string` (255) pour les libellés courts et `text` pour les descriptions.
- Si une relation ManyToMany est demandée : créer la migration de la table pivot normalisée (`[table1]_[table2]`) avec clés étrangères composites indexées.
- Si le package cible n'existe pas : proposer de le créer au préalable selon la structure modulaire de SoliLMS.

# 7. Format de Sortie & Livrables
- Le fichier de migration Laravel prêt à être exécuté par le développeur.
- Le fichier du modèle enfant PHP.
- Le fichier du service métier enfant PHP.
- La commande Gapp recommandée et les instructions claires de validation.

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] La migration contient-elle la colonne $table->string('reference')->unique() ?
- [ ] L'agent s'est-il abstenu d'exécuter php artisan migrate ?
- [ ] Les classes créées respectent-elles le découpage Base/ et classes enfants ?
- [ ] Aucun fichier existant dans Base/ n'a été altéré ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Oubli de la colonne `reference` | Habitude Laravel classique | Ajouter impérativement `$table->string('reference')->unique()` |
| Exécuter `php artisan migrate` | Zèle de l'agent | Interdiction absolue : donner la commande au développeur |
| Modifier un fichier dans `Base/` | Mauvaise cible | Coder uniquement dans la classe enfant PHP |

# 10. Exemples Concrets (Few-Shot)
**Exemple d'invocation :** *"Crée une entité SessionApprenant dans PkgSessions liée à Apprenant et SessionFormation."*
**Comportement :** L'agent conçoit la migration avec `reference`, `apprenant_id`, `session_formation_id`, crée le modèle `SessionApprenant` étendant `BaseSessionApprenant`, le service `SessionApprenantService`, et donne au développeur la commande de migration et de scaffolding Gapp.
