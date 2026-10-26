---
name: feature-dev
description: Développe, modifie, étend ou dépanne toute fonctionnalité dans Solicode LMS (Service, Trait, Controller, Blade custom, TableUI, FilterUI) et diagnostique les erreurs de logs laravel.log, exceptions BlException et régressions.
version: 2.0.0
inspiration:
  source: "anthropic/claude-plugins-official"
  plugin: "feature-dev"
  pattern: "5-phase implementation & root-cause defect resolution workflow"
---

# 1. Mission
Ce skill pilote l'ensemble du cycle d'ingénierie logicielle (nouvelles fonctionnalités, ajustements métier, refactorisations et résolutions de bogues) au sein de Solicode LMS. Il assure la modification des contrôleurs fins, l'encapsulation de la logique métier dans la couche Service (et ses Traits découpés < 300 lignes), le diagnostic sur trace d'erreurs (`storage/logs/laravel.log`), l'intégration aux composants frontend `TableUI`/`FilterUI`, et la préservation absolue des verrous Gapp.

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - **Évolutions :** Ajout ou modification d'un filtre d'indexation (`initFieldsFilterable`), formulaire, champ calculé (`data-calcule`), listes déroulantes dynamiques (`DynamicDropdownTreatment`), ou vues Blade personnalisées (`custom/`).
  - **Règles Métier :** Implémentation ou ajustement de règles métier et hooks CRUD (`beforeCreateRules`, `beforeUpdateRules`, `afterUpdateRules`) avec levée de `BlException`.
  - **Dépannage & Bogues :** Signalement d'une anomalie, erreur HTTP 500/422, exception `BlException` inattendue, défaillance d'un tableau `TableUI.js`, d'une modale `ModalUI.js` ou d'un sélecteur en cascade.
  - **Refactorisations :** Découpage de services ou traits approchant 500 lignes pour les ramener sous la barre de dégagement des 300 lignes.
- **Quand NE PAS l'activer :**
  - Simple analyse d'impact ou cartographie sans modification de code ➔ Utiliser `planner-dev`.
  - Conception et génération d'une nouvelle table en BDD ➔ Utiliser `domain-scaffolder`.
  - Gouvernance des compétences et règles d'agent ➔ Utiliser `plugin-dev`.
  - Initialisation d'agent ou diagnostic projet ➔ Utiliser `project-setup`.

# 3. Entrées & Ressources à Charger
Avant d'écrire ou modifier la moindre ligne de code, charger :
1. **Priorité 1 (Contexte Package Cible) :** Fiche `modules/Pkg[Nom]/[NomPkg]_IA_CONTEXT.md` du module concerné (obligatoire avant toute analyse).
2. **Priorité 2 (Journaux d'Erreurs si Bogue) :** `storage/logs/laravel.log` pour extraire la trace réelle de la pile d'appels.
3. **Priorité 3 (Conventions du Projet) :**
   - `.agents/references/conventions/crud-backend-mechanic.md` (Filtres, Services, Traits et cycle de vie).
   - `.agents/references/conventions/crud-frontend-ui.md` (TableUI, FilterUI et sous-dossier custom/).
   - `.agents/references/conventions/project-conventions.md` (Rôles Spatie, ViewState).
4. **Priorité 4 (Spécification Issue) :** Fiche d'issue dans `issues/<SousDossier>/` si existante (régie par [.agents/rules/gestion-issues.md](file:///c:/AppServer/solicode-lms/.agents/rules/gestion-issues.md)).
5. **Priorité 5 (Ressources Spécifiques Encapsulées) :** Guides internes disponibles dans `resources/` :
   - `resources/crud-jobs-and-polling.md` (Opérations asynchrones, Observer et Polling).
   - `resources/typage-enum.md` (Workflow de conversion d'un attribut en Enum PHP 8.1+).
   - `resources/widgets-system.md` (Configuration des widgets dynamiques JSON).
   - `resources/catalogue-metadonnees.md` (Métadonnées Gapp : dynamicDropdown, linkAction).
   - `resources/specifications-avancees.md` (Champs calculés data-calcule).

# 4. Contraintes & Règles Inviolables
1. **Règles Critiques Inviolables :**
   - Respecter scrupuleusement [.agents/rules/critical-rules.md](file:///c:/AppServer/solicode-lms/.agents/rules/critical-rules.md) (verrous Gapp `Base/`, Blade `custom/`, persistance via Service, interdiction de `git commit` et `php artisan migrate`).
2. **Diagnostic sur Preuve (Cas de Bogue) :**
   - Ne jamais deviner une panne sans avoir lu le message d'erreur et la pile d'appels dans `storage/logs/laravel.log` ou le code source exact.
3. **Plafond Strict de 500 Lignes & Règle de Dégagement (< 300 Lignes) :**
   - Aucun fichier de Service ou Trait ne doit dépasser 500 lignes.
   - En cas de refactorisation suite à un dépassement, ramener obligatoirement le fichier sous **300 lignes** (< 300 lignes).
4. **Émergence de Services Métier Dédiés :**
   - Dès qu'un concept fonctionnel transversal émerge (ex: `Seance`, `SituationPedagogique`), créer un Service dédié même sans table BDD.
5. **Chargement Préalable de `[NomPkg]_IA_CONTEXT.md` :**
   - Obligatoire dès qu'un package est ciblé.

# 5. Procédure Pas-à-Pas (Workflow en 5 Phases)
1. **Phase 1 : Diagnostic (si bogue) ou Analyse du Besoin (si évolution) :**
   - **Si bogue :** Consulter la fin de `storage/logs/laravel.log`, isoler l'exception, le fichier et la ligne exacte. Reconstituer la pile d'appels à travers les couches (Route ➔ Contrôleur ➔ Service & Traits ➔ Modèle / TableUI).
   - **Si évolution :** Analyser le besoin métier, identifier les classes enfants et templates Blade cibles.
   - Charger le fichier `[NomPkg]_IA_CONTEXT.md` du module.
2. **Phase 2 : Clarification :** Si des données critiques manquent (comportement attendu, cas limites), poser au maximum 3 questions ciblées.
3. **Phase 3 : Conception & Contrat :**
   - Identifier le Service ou Trait à modifier/créer (`initFieldsFilterable`, `dataCalcul`, `beforeUpdateRules`).
   - Veiller à ce qu'aucun fichier ne franchisse le seuil de 300/500 lignes (créer un nouveau Trait spécialisé si besoin).
4. **Phase 4 : Implémentation Chirurgicale :**
   - Injecter le code métier minimaliste et propre dans les classes enfants ou le sous-dossier `custom/`.
   - Si une classe enfant PHP contient `// Ce fichier est maintenu par ESSARRAJ Fouad`, supprimer cette ligne pour y intégrer le code.
5. **Phase 5 : Contrôle, Preuve & Traçabilité :**
   - Valider la solution (script scratch de test ou contrôle visuel TableUI).
   - En cas d'incident structurel résolu, consigner optionnellement un retour d'expérience dans `.agents/tests/feedbacks/INC-[NUM].yaml`.
   - Si l'intervention découle d'une issue, la clôturer vers `issues/issues-closed/` selon [.agents/rules/gestion-issues.md](file:///c:/AppServer/solicode-lms/.agents/rules/gestion-issues.md).

# 6. Gestion des Ambiguïtés & Décisions
- Si le fichier `laravel.log` ne contient pas d'erreur récente lors d'un bug : demander au développeur de reproduire l'action et réexaminer le journal.
- Si le type de filtre n'est pas spécifié : appliquer par défaut `generateManyToOneFilter` si c'est une clé étrangère standard.
- Si une permission dynamique bloque une nouvelle route : ajouter l'annotation PHPDoc `/** @DynamicPermissionIgnore */` et appeler `$this->authorizeAction(...)`.

# 7. Format de Sortie & Livrables
L'agent fournit :
- L'analyse synthétique : Cause racine identifiée (si bogue) ou logique d'extension (si évolution).
- Les diffs de code clairs appliqués exclusivement aux fichiers enfants ou `custom/`.
- La commande ou méthode de vérification (script scratch ou rafraîchissement d'interface).

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] Le fichier `[NomPkg]_IA_CONTEXT.md` a-t-il été chargé au préalable ?
- [ ] Aucun fichier dans `Base/` n'a été altéré ?
- [ ] Les templates Blade modifiés utilisent-ils le sous-dossier `custom/` ?
- [ ] La logique métier réside-t-elle dans le Service ou un Trait (et non dans le Contrôleur) ?
- [ ] Les erreurs de validation métier utilisent-elles `throw new BlException(...)` ?
- [ ] Tous les fichiers modifiés respectent-ils le plafond des 500 lignes et la cible de dégagement < 300 lignes ?
- [ ] Le code frontend s'appuie-t-il sur `TableUI.js` / `FilterUI.js` sans jQuery sauvage ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Modifier la classe `Base/` pour corriger un bug | Croyance que le bug vient du générateur | Surcharger la méthode dans la classe enfant PHP |
| Écraser une vue Blade verrouillée | Non-respect du lock ESSARRAJ Fouad | Utiliser le sous-dossier `custom/` de la vue |
| Écraser un filtre parent lors du filtrage | Oubli des `$scopeVariables` | Vérifier `if (!array_key_exists('champ_id', $scopeVariables))` avant d'ajouter le filtre |
| Supprimer du code fonctionnel par confusion | Manque d'analyse de la trace | Isoler précisément la ligne d'exception sans toucher au reste |
| Boucle infinie dans un Job asynchrone | Appel à `$this->update()` dans le Job | Utiliser `withoutEvents(function() { ... })` pour la sauvegarde en arrière-plan |

# 10. Exemples Concrets (Few-Shot)
**Exemple A (Évolution) : Ajout d'un filtre ManyToOne**
```php
// Dans modules/PkgFormation/Services/FiliereService.php
public function initFieldsFilterable()
{
    parent::initFieldsFilterable();
    $scopeVariables = $this->viewState->getScopeVariables('filiere');
    
    if (!array_key_exists('etablissement_id', $scopeVariables)) {
        $etablissementService = new \Modules\PkgFormation\Services\EtablissementService();
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgFormation::etablissement.plural"),
            'etablissement_id',
            \Modules\PkgFormation\Models\Etablissement::class,
            'nom',
            $etablissementService->all()
        );
    }
}
```

**Exemple B (Dépannage) : Résolution d'erreur 500 sur relation manquante**
```php
// Symptôme : Erreur 500 dans TableUI lors du listing des apprenants
// Trace dans laravel.log : Call to a member function nom on null in FormateurService.php:84
// Solution : Eager-loading de la relation dans le Service enfant :
protected array $index_with_relations = [
    'user',
    'groupes.filiere',
];
```
