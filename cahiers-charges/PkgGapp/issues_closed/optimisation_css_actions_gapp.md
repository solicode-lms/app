# Issue Gapp : Optimisation de l'intégration CSS des Boutons d'Action (Tableau CRUD)

**Titre** : Améliorer les sélecteurs CSS (Classes/IDs) des boutons d'actions dans `_table.blade.php` pour faciliter le stylage.

## 📝 Description du Problème
Actuellement, la génération de la colonne des actions dans `_table.blade.php` ne possède pas de classes sémantiques ou de conteneurs de regroupement clairs. 
Cela rend la personnalisation CSS (ex: mettre en évidence les actions principales, changer la couleur du bouton de suppression, ou organiser les boutons sur plusieurs lignes) très fragile, car on doit s'appuyer sur des sélecteurs non optimaux comme `td:last-child` ou détourner des classes techniques (`editEntity`, `deleteEntity`).

## 💡 Proposition de Solution (Modifications requises dans le générateur Gapp)

Il est proposé d'enrichir le générateur de la vue `_table.blade.php` et du composant `<x-action-button>` avec les améliorations suivantes :

### 1. Classes spécifiques sur la colonne des Actions
Ajouter une classe métier sur l'entête `<th>` et la cellule `<td>` des actions :
```blade
<!-- Au lieu de -->
<th class="text-center">{{ __('Core::msg.action') }}</th>
<td class="text-right wrappable" style="max-width: 15%;">

<!-- Remplacer par -->
<th class="text-center crud-actions-header">{{ __('Core::msg.action') }}</th>
<td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
```

### 2. Conteneurs de Regroupement (Flexbox Ready)
Diviser les actions en deux groupes logiques pour faciliter leur disposition (ex: `flex-direction: column` ou `wrap`) :
```blade
<td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
    <!-- Groupe 1 : Actions Secondaires (Custom Actions générées via x-action-button) -->
    <div class="actions-secondary-group">
        ... (Boutons "voirTaches", "clonerProjet", etc.)
    </div>

    <!-- Groupe 2 : Actions Principales (Show, Edit, Delete) -->
    <div class="actions-main-group">
        ... (Boutons standard du CRUD)
    </div>
</td>
```

### 3. Classes CSS Sémantiques sur les Boutons
Ajouter des classes spécifiques pour identifier visuellement le type d'action sans toucher au JavaScript :
- Ajouter `btn-action-main` sur les liens `Show` et `Edit`.
- Ajouter `btn-action-delete` (et potentiellement remplacer `btn-default` par `btn-danger` généré nativement) sur le bouton de suppression.
- Ajouter `btn-action-secondary` dans la génération des `<x-action-button>` personnalisés.

## ✅ Avantages Attendus
- **Robustesse** : Le CSS global de l'application pourra cibler `.crud-actions-cell .btn-action-delete` plutôt que de bidouiller avec des pseudos-sélecteurs.
- **Maintenabilité** : Permet aux développeurs front-end de réorganiser toutes les actions de tous les CRUDs (ex: les empiler verticalement sur mobile) via une simple règle Flexbox sur `.crud-actions-cell`.
- **Esthétique** : Facilite la mise en avant des actions principales (plus grandes) par rapport aux actions secondaires (plus petites).

---

## 🔍 Analyse d'Impact par Composant
| Composant | Impact |
| --- | --- |
| **Database** | Aucun impact. |
| **Model** | Aucun impact. |
| **Service** | Aucun impact. |
| **Controller** | Aucun impact. |
| **Blade** | Aucun impact direct sur le code des modules (Généré par Gapp). |
| **UI (CSS)** | **Fort impact positif**. Le CSS pourra être refactorisé dans `custom.css` de manière beaucoup plus propre et maintenable. |
| **Générateur (Gapp)** | **Impact majeur**. C'est le cœur de l'issue : modification des stubs de Gapp (`_table.blade.php` et `x-action-button`). |

## 🛠️ Skills Requis pour Implémentation
Pour réaliser cette issue (une fois implémentée dans Gapp puis synchronisée), les skills suivants devront être invoqués :
- `sys-gapp` : (optionnel, pour d'éventuelles adaptations de métadonnées de UI si nécessaires).
- `app-blade` : Pour vérifier que la génération n'a pas cassé le rendu actuel et appliquer le nouveau CSS Flexbox dans le système de styles de Solicode LMS.
