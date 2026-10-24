---
name: context-generator
description: Explore un package modulaire dans modules/Pkg, cartographie ses entites, services, filtres et regles Gapp, et produit son fichier AI_CONTEXT.md.
version: 1.0.0
inspiration:
  source: "anthropic/claude-plugins-official"
  plugin: "context-generator"
  pattern: "modular application domain mapping & AI context generation"
---

# 1. Mission
Ce skill explore en profondeur un package modulaire applicatif dans `modules/Pkg[Nom]/` de Solicode LMS. Il inspecte ses modèles Eloquent, ses relations, sa couche Service, ses filtres dynamiques, ses composants d'interface et ses spécificités Gapp pour générer ou actualiser automatiquement un fichier standardisé `AI_CONTEXT.md` à la racine du package.

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Demande de génération ou mise à jour de la documentation IA d'un module ("Génère le AI_CONTEXT.md pour PkgFormation", "Documente le module PkgCreationTache").
  - Arrivée d'un nouveau package métier nécessitant sa fiche de contexte IA.
  - Mise à jour après ajout de nouvelles tables ou relations dans un module.
- **Quand NE PAS l'activer :**
  - Conception ou modification de skills IA ➔ Utiliser `plugin-dev`.
  - Développement applicatif de fonctionnalités ➔ Utiliser `feature-dev`.
  - Simple analyse temporaire sans production de documentation pérenne ➔ Utiliser `architecture-planner`.

# 3. Entrées & Ressources à Charger
- **Priorité 1 :** `templates/module-ai-context.md` (Modèle officiel de fiche colocalisé dans ce skill).
- **Priorité 2 :** Code source réel du package dans `modules/Pkg[Nom]/` :
  - `Models/` (Modèles enfants et sous-dossier `Base/`).
  - `Services/` (Services enfants, sous-dossier `Base/` et sous-dossier `Traits/`).
  - `Controllers/` (Contrôleurs et FormRequests).
  - `resources/views/` (Vues et sous-dossier `custom/`).
  - `Database/Migrations/` (Colonnes et clés de relations).
- **Priorité 3 :** `.agents/references/gapp/01-conventions-et-garde-fous.md`.

# 4. Contraintes & Règles Inviolables
- **Respect du Modèle Normé :** Le fichier produit `AI_CONTEXT.md` doit respecter strictement la structure de `templates/module-ai-context.md`.
- **Factualité Absolue :** Ne consigner QUE ce qui existe réellement dans le code du package (pas d'inventions de relations Eloquent ou de méthodes).
- **Préservation des Fichiers Métier :** Le skill ne doit créer ou modifier QUE le fichier `AI_CONTEXT.md` dans le module ciblé. Interdiction de toucher au code PHP, Blade ou aux migrations.

# 5. Procédure Pas-à-Pas (Workflow de Documentation de Package)
1. **Étape 1 : Cartographie des Modèles & Relations :**
   - Lister les fichiers dans `modules/Pkg[Nom]/Models/`.
   - Inspecter les méthodes de relation (`hasMany`, `belongsTo`, `belongsToMany`) dans les modèles enfants et les classes `Base/`.
2. **Étape 2 : Cartographie de la Couche Service & Logique Métier :**
   - Identifier `[Model]Service.php` et les Traits dans `Services/Traits/[Model]/`.
   - Noter les hooks surchargés (`beforeCreateRules`, `beforeUpdateRules`) et les méthodes de calcul (`dataCalcul`).
   - Repérer les filtres configurés dans `initFieldsFilterable()`.
3. **Étape 3 : Cartographie UI & Composants Personnalisés :**
   - Vérifier si des vues sont personnalisées dans `resources/views/[model]/custom/`.
   - Repérer l'usage éventuel de `data-calcule` ou `data-target-dynamic-dropdown`.
4. **Étape 4 : Cartographie des Droits d'Accès :**
   - Vérifier les rôles Spatie et les seeders dans `Database/Seeders/`.
5. **Étape 5 : Rédaction et Sauvegarde :**
   - Compiler l'ensemble dans `modules/Pkg[Nom]/AI_CONTEXT.md`.

# 6. Gestion des Ambiguïtés & Décisions
- Si le dossier du module n'existe pas : signaler l'erreur au développeur et arrêter l'exécution.
- Si le module ne comporte pas de logique métier dans les services : documenter explicitement qu'il s'agit d'un CRUD standard généré par Gapp.

# 7. Format de Sortie & Livrables
- Le fichier `modules/Pkg[Nom]/AI_CONTEXT.md` créé ou mis à jour.
- Une synthèse affichée à l'écran résumant le nombre de modèles, de services et de filtres recensés.

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] Le fichier AI_CONTEXT.md est-il déposé directement à la racine du package concerné ?
- [ ] Toutes les relations Eloquent listées correspondent-elles au code source réel ?
- [ ] Les méthodes surchargées et traits du service sont-ils clairement identifiés ?
- [ ] Aucun fichier de code applicatif n'a été altéré durant l'opération ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Documenter les classes Base/ comme du code personnalisé | Confusion dans la hiérarchie | Distinguer explicitement le socle généré Gapp des surcharges métier |
| Écrire le fichier au mauvais endroit | Erreur de chemin relatif | Enregistrer à la racine exacte modules/Pkg[Nom]/AI_CONTEXT.md |

# 10. Exemples Concrets (Few-Shot)
**Exemple d'invocation :** *"Documente le module PkgFormation."*
**Comportement :** L'agent explore `modules/PkgFormation`, recense les entités (`Formateur`, `Filiere`, `Module`, `AnneeFormation`, `Specialite`), extrait la table pivot `formateur_groupe`, documente les méthodes de `FormateurService` et génère `modules/PkgFormation/AI_CONTEXT.md`.
