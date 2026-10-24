---
name: feature-dev
description: Developpe, modifie ou etend une fonctionnalite traversante dans Solicode LMS (Service, Trait, Controller, Blade custom, TableUI, FilterUI) en respectant les locks Gapp.
version: 1.0.0
inspiration:
  source: "anthropic/claude-plugins-official"
  plugin: "feature-dev"
  pattern: "5-phase implementation workflow & progressive disclosure"
---

# 1. Mission
Ce skill pilote la réalisation de fonctionnalités complètes ou de modifications transversales au sein de l'application Solicode LMS. Il assure la modification des contrôleurs fins, l'encapsulation de la logique métier dans la couche Service (et ses Traits), l'intégration aux composants frontend `TableUI`/`FilterUI`, et la préservation absolue des fichiers protégés par le générateur Gapp.

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Ajout ou modification d'un filtre d'indexation (`initFieldsFilterable`).
  - Ajout ou ajustement d'un formulaire, d'un champ calculé (`data-calcule`) ou de listes déroulantes dynamiques (`DynamicDropdownTreatment`).
  - Implémentation d'une règle métier ou d'un hook de cycle de vie CRUD (`beforeCreateRules`, `beforeUpdateRules`) avec levée de `BlException`.
  - Personnalisation d'une vue Blade via le sous-dossier `custom/`.
- **Quand NE PAS l'activer :**
  - Simple analyse ou cartographie sans demande de modification -> Déclencher `architecture-planner`.
  - Création complète d'une nouvelle table en BDD -> Déclencher `concevoir-architecture`.
  - Rédaction d'un tutoriel ou guide de cours -> Déclencher `tutorial-writer`.

# 3. Entrées & Ressources à Charger
Avant d'écrire ou modifier la moindre ligne de code, charger :
1. **Priorité 1 (Conventions du Projet) :**
   - `.agents/references/conventions/crud-backend-mechanic.md` (Règles des filtres, Services et Traits).
   - `.agents/references/conventions/crud-frontend-ui.md` (Structure TableUI, FilterUI et sous-dossier custom/).
   - `.agents/references/conventions/project-conventions.md` (Rôles Spatie, ViewState).
2. **Priorité 2 (Références Partagées) :**
   - `.agents/references/architecture/layers-3tiers.md`
   - `.agents/references/frontend/tableui-framework.md`
   - `.agents/references/gapp/01-conventions-et-garde-fous.md`
3. **Priorité 3 (Contexte Package) :** Fiche `AI_CONTEXT.md` du module concerné dans `modules/Pkg.../`.

# 4. Contraintes & Règles Inviolables
1. **Intégrité de Gapp (Classes Base/) :**
   - Ne **JAMAIS** modifier un fichier situé dans un sous-dossier `Base/` ou commençant par `Base` (ex: `BaseProjetService.php`).
   - Surcharger uniquement dans les classes filles (`ProjetService.php`) ou leurs Traits associés (`modules/Pkg.../Services/Traits/`).
2. **Intégrité de Gapp (Vues Blade) :**
   - Si un fichier `.blade.php` contient `{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}`, ne **JAMAIS** le modifier directement. Utiliser le dossier `custom/` (ex: `custom/_table.blade.php`).
3. **Persistance des Données :**
   - Interdiction formelle d'appeler `$model->save()`, `$model->update()`, `$model->delete()` directement dans les contrôleurs. Toute persistance doit passer par le Service (`$service->create()`, `$service->update()`).
   - Interdiction d'appeler `$model->relation()->sync()`. Passer le tableau d'IDs dans les données envoyées au Service.
4. **Exceptions Métier BLL :**
   - Toujours lever `throw new BlException("Message...");` pour interrompre une opération non conforme. Pas de redirection HTTP ad-hoc.
5. **Migrations BDD :**
   - L'agent a l'interdiction d'exécuter `php artisan migrate` de manière autonome.

# 5. Procédure Pas-à-Pas (Workflow en 5 Phases)
1. **Phase 1 : Exploration & Vérification des Locks :**
   - Localiser les classes enfants modifiables (`[Model]Service.php`, `[Model]Controller.php`).
   - Vérifier la présence éventuelle de la vue dans `custom/`.
2. **Phase 2 : Clarification :** Si des données critiques manquent, poser au maximum 3 questions ciblées.
3. **Phase 3 : Conception du Contrat :**
   - Identifier le Trait ou la méthode à surcharger (`initFieldsFilterable`, `dataCalcul`, `beforeUpdateRules`).
   - Déterminer les variables de vue et les attributs HTML (`data-calcule`, `data-target-dynamic-dropdown`).
4. **Phase 4 : Implémentation Chirurgicale :**
   - Injecter le code métier minimaliste et propre.
   - Si une classe enfant PHP contient `// Ce fichier est maintenu par ESSARRAJ Fouad`, supprimer cette ligne pour y intégrer le code.
5. **Phase 5 : Contrôle & Preuve de Fonctionnement :**
   - Exécuter la checklist de contrôle qualité.
   - Présenter au développeur les diffs précis et lui indiquer comment tester le résultat.

# 6. Gestion des Ambiguïtés & Décisions
- Si le type de filtre n'est pas spécifié : appliquer par défaut `generateManyToOneFilter` si c'est une clé étrangère standard.
- Si une permission dynamique bloque une nouvelle route de contrôleur : ajouter l'annotation PHPDoc `/** @DynamicPermissionIgnore */` et appeler `$this->authorizeAction(...)`.

# 7. Format de Sortie & Livrables
L'agent fournit :
- Les modifications de fichiers sous forme de blocs de code ou diffs clairs.
- L'explication de la mécanique retenue (Service, Trait, Blade custom, ou attribut JS).
- La commande ou l'action de vérification à réaliser dans l'interface (ex: rafraîchir la page du CRUD).

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] Aucun fichier dans `Base/` n'a été modifié ?
- [ ] Aucun template Blade locké n'a été altéré directement (utilisation de `custom/`) ?
- [ ] La logique métier est-elle isolée dans le Service ou un Trait (et non dans le Contrôleur) ?
- [ ] Les erreurs de validation métier utilisent-elles `BlException` ?
- [ ] Le code frontend s'appuie-t-il sur `TableUI.js` / `FilterUI.js` sans jQuery sauvage ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Modifier `BaseProjetService.php` | Méconnaissance de la structure Gapp | Déplacer immédiatement la méthode dans `ProjetService.php` |
| Écraser un filtre parent lors du filtrage | Oubli des `$scopeVariables` | Vérifier `if (!array_key_exists('champ_id', $scopeVariables))` avant d'ajouter le filtre |
| Boucle infinie dans un Job asynchrone | Appel à `$this->update()` dans le Job | Utiliser `withoutEvents(function() { ... })` pour la sauvegarde en arrière-plan |

# 10. Exemples Concrets (Few-Shot)
**Exemple d'ajout d'un filtre ManyToOne :**
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

