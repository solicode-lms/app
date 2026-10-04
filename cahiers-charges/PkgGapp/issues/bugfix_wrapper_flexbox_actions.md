# Issue Gapp : Bugfix du layout Flexbox sur les Actions CRUD

**Titre** : Englober les groupes d'actions dans un `<div class="crud-actions-wrapper">` pour préserver le comportement `table-cell`.

## 📝 Description du Problème
Lors de la mise en place de la disposition en colonnes (Flexbox) pour les boutons d'actions (issue `optimisation_css_actions_gapp.md` qui a été réalisée), un problème de rendu CSS a été identifié.
Appliquer `display: flex` directement sur une balise `<td>` (`.crud-actions-cell`) annule son comportement natif `display: table-cell`. En conséquence, le navigateur ignore l'algorithme `table-layout: fixed` et la contrainte `max-width: 15%`, ce qui casse la structure du tableau sur certains écrans.

## 💡 Proposition de Solution (Modifications requises dans Gapp)

Il faut modifier le stub de génération de `_table.blade.php` pour que Gapp génère un `div` conteneur intermédiaire à l'intérieur de la cellule `<td>`. Le CSS `display: flex` sera appliqué à ce `div`, permettant au `<td>` de conserver son rôle structurel intact.

### Modification dans `_table.blade.php` (Stub Gapp)
```blade
<td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
    <!-- NOUVEAU WRAPPER À AJOUTER PAR GAPP -->
    <div class="crud-actions-wrapper">
        
        <!-- Groupe 1 : Actions Secondaires -->
        <div class="actions-secondary-group">
            ...
        </div>

        <!-- Groupe 2 : Actions Principales -->
        <div class="actions-main-group">
            ...
        </div>
        
    </div>
</td>
```

---

## 🔍 Analyse d'Impact par Composant
| Composant | Impact |
| --- | --- |
| **Database** | Aucun impact. |
| **Model** | Aucun impact. |
| **Service** | Aucun impact. |
| **Controller** | Aucun impact. |
| **Blade** | **Impact sur l'UI**. Rétablit le respect du `max-width: 15%` de la colonne action. |
| **UI (CSS)** | **Léger impact**. Le CSS a déjà été adapté (`admin.css`) pour cibler `.crud-actions-wrapper`. |
| **Générateur (Gapp)** | **Impact principal**. Il faut modifier le stub responsable de la génération de `_table.blade.php`. |

## 🛠️ Skills Requis pour Implémentation
- `sys-gapp` : Pour reporter la modification dans les templates (stubs) du générateur Gapp et régénérer les CRUDs impactés.
- `app-blade` : Pour vérifier la stabilité du rendu final du tableau.
