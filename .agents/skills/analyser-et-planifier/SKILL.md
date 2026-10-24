---
name: analyser-et-planifier
description: Explore un projet ou un module, analyse limpact architectural dune demande et produit un plan daction etape par etape sans modifier le code applicatif.
version: 1.0.0
---

# 1. Mission
Ce skill a pour objectif d'analyser le code existant, d'évaluer la faisabilité technique d'une demande, d'identifier les composants et fichiers impactés, et de proposer un plan de réalisation phasé et sécurisé.
**Règle d'or : Ce skill opère strictement en lecture seule (read-only). Aucune modification de code métier ne doit être effectuée durant son exécution.**

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Demande d'analyse, d'audit ou de diagnostic architectural préalable ("Comment fonctionne le module X ?", "Analyse l'impact de l'ajout d'une colonne Y").
  - Demande de planification complexe avant implémentation ("Propose un plan pour ajouter la gestion des sessions").
  - Cartographie d'un composant ou d'une dépendance transversale (TableUI, ViewState, Service).
- **Quand NE PAS l'activer :**
  - Demande directe de modification ou d'implémentation de code -> Déclencher `implementer-fonctionnalite`.
  - Demande de tutoriel pour apprenants -> Déclencher `rediger-tutoriel`.
  - Simple question théorique sur Laravel ou PHP -> Répondre directement sans skill.

# 3. Entrées & Ressources à Charger
Lors de l'analyse, charger les informations selon cette hiérarchie stricte :
1. **Priorité 1 (Contexte Local) :** `.agents/local/project-conventions.md`, `crud-backend-mechanic.md`, `crud-frontend-ui.md`.
2. **Priorité 2 (Références Partagées) :** `.agents/references/architecture/layers-3tiers.md`, `.agents/references/gapp/01-conventions-et-garde-fous.md`.
3. **Priorité 3 (Contexte Module) :** Fichier `AI_CONTEXT.md` du module cible s'il existe dans `modules/Pkg[Nom]/`.

# 4. Contraintes & Règles Inviolables
- **Mode Read-Only Absolu :** Ne créer ni modifier aucun fichier source (PHP, Blade, JS, JSON).
- **Interdiction de commandes destructives :** Ne lancer aucun `migrate`, `db:seed` ou suppression.
- **Vérification systématique des locks Gapp :** Repérer d'avance les fichiers marqués `ESSARRAJ Fouad` pour orienter le futur plan vers les classes filles ou le sous-dossier `custom/`.

# 5. Procédure Pas-à-Pas (Workflow d'Analyse)
1. **Étape 1 : Cadrage du besoin :** Reformuler l'objectif et identifier les modules propriétaires dans `modules/`.
2. **Étape 2 : Exploration du code réel :**
   - Repérer le modèle Eloquent, les relations et la table MySQL associée.
   - Inspecter le Service (`[Model]Service.php`), ses Traits et ses filtres `initFieldsFilterable()`.
   - Inspecter le Contrôleur et la prise en compte du ViewState.
   - Inspecter les vues Blade et la présence éventuelle de vues `custom/`.
3. **Étape 3 : Cartographie des impacts :** Établir la matrice des fichiers à modifier vs fichiers à créer vs fichiers protégés par Gapp.
4. **Étape 4 : Élaboration du plan phasé :** Rédiger un plan d'action ordonné avec critères de validation pour chaque phase.

# 6. Gestion des Ambiguïtés & Décisions
- Si le module cible n'est pas évident ou si plusieurs implémentations sont possibles : poser **au maximum 3 questions fermées et précises** au développeur.
- Si une donnée manque mais que la réponse est standard : formuler explicitement une hypothèse technique et poursuivre l'analyse.

# 7. Format de Sortie & Livrables
Le livrable doit être un rapport d'analyse structuré comprenant :
- **Synthèse de l'état actuel :** Modules et classes concernés.
- **Matrice d'impact :** Liste des fichiers touchés (en distinguant les classes enfants des fichiers Base Gapp).
- **Plan d'implémentation recommandé :** Étapes ordonnées prêtes pour le skill `implementer-fonctionnalite`.
- **Risques identifiés & Garde-fous :** Points d'attention particuliers.

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] Aucun fichier applicatif n'a été altéré durant l'analyse ?
- [ ] La distinction entre classes Base/ (Gapp) et classes enfants est-elle clairement établie ?
- [ ] Le plan respecte-t-il la séparation 3-tiers (Contrôleur fin, logique dans le Service) ?
- [ ] Le composant TableUI.js existant est-il privilégié sans réinvention de code ad-hoc ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Modifier un fichier en cours d'analyse | Vouloir corriger un bug immédiatement | Bloquer toute écriture et consigner la correction dans le plan |
| Proposer de modifier un fichier Base/ | Non-vérification de l'en-tête Gapp | Rediriger systématiquement vers la classe fille ou custom/ |
| Planifier un php artisan migrate | Oubli de la règle d'or | Indiquer que la commande sera exécutée manuellement par l'humain |

# 10. Exemples Concrets (Few-Shot)
**Exemple de demande :** *"Comment ajouter un filtre par groupe sur le tableau des projets ?"*
**Comportement attendu :** L'agent lit `modules/PkgCreationProjet/Services/ProjetService.php`, constate la présence de `BaseProjetService`, vérifie si `initFieldsFilterable()` est surchargé, vérifie le lien avec `resources/js/crud/components/FilterUI.js`, et produit un plan d'action en 3 points sans modifier le moindre fichier.
