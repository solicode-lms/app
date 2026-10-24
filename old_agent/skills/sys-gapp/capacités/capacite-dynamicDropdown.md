# Capacité : DynamicDropdown (Métadonnée Gapp)

*Note : Les chemins de relation (`relationPath`) doivent TOUJOURS être validés via le skill `db-savoir`.*

# 🧩 `DynamicDropdown`

La fonctionnalité `DynamicDropdown` permet de **filtrer dynamiquement les options d’un ou plusieurs champs `<select>` en fonction d’un autre champ déclencheur**.

🎯 **Cas d’usage** :
- Filtrer les **tâches (`tache_id`)** en fonction de l’**affectation de projet**.
- Mettre à jour plusieurs listes déroulantes (ex. `tache_id`, `user_id`, `ville`) à partir d’un même sélecteur source.

---

## 📦 Installation & Intégration

Le composant se branche aussi bien dans les UIs de **filtrage** (FilterUI) que dans les UIs de **formulaire** (FormUI, FormeUI). Il suffit de l’initialiser sur l’élément `<select>` déclencheur :

```js
import DynamicDropdownTreatment from '../treatments/global/DynamicDropdownTreatment';

const trigger = document.querySelector("[name='projet.affectationProjets.id']");
new DynamicDropdownTreatment(trigger, {
  formSelector: '#monFormulaire',        // optionnel : conteneur pour le loader
  filterFormSelector: '#formFiltres'     // optionnel
});
```

---

## ⚙️ Configuration HTML

### Attributs `data-*`

| Attribut                                | Description                                                                                   |
|-----------------------------------------|-----------------------------------------------------------------------------------------------|
| `data-target-dynamic-dropdown`          | Liste de sélecteurs CSS (CSV) des `<select>` à mettre à jour (ex. `#tache, #user`).           |
| `data-target-dynamic-dropdown-api-url`  | Liste d'URLs (CSV) correspondantes pour chaque cible (ex. `/api/taches, /api/users`).         |
| `data-target-dynamic-dropdown-filter`   | Liste de clés de filtre (CSV) pour chaque endpoint (ex. `projetId, userId`).                  |

> ⚠️ **Veiller à** avoir le **même nombre d’éléments** dans chacun des trois attributs, dans le même ordre.

### Exemple HTML

```html
<select
  id="filter_affectation"
  name="projet.affectationProjets.id"
  class="form-select form-control-sm"
  data-target-dynamic-dropdown="#tache, #user, #ville"
  data-target-dynamic-dropdown-api-url="/api/taches, /api/users, /api/villes"
  data-target-dynamic-dropdown-filter="projetId, projetId, regionId"
>
  <option value="">Sélectionnez...</option>
  <!-- autres options -->
</select>

<!-- Cibles à peupler -->
<select id="tache" name="tache_id" class="form-select form-control-sm">
  <option value="">Tâches</option>
</select>
<select id="user" name="user_id" class="form-select form-control-sm">
  <option value="">Utilisateurs</option>
</select>
<select id="ville" name="ville_id" class="form-select form-control-sm">
  <option value="">Villes</option>
</select>
```

---

## 💻 API JavaScript

La classe `DynamicDropdownTreatment` est capable de :

1. **Parser** les listes CSV des attributs pour construire une configuration par cible :  
   ```js
   [{ selector, apiUrl, filterParam, cache }, …]
   ```
2. **Écouter** l’événement `change` sur le `<select>` déclencheur.  
3. **Charger** (fetch) les données depuis chaque endpoint en passant la valeur du déclencheur comme filtre.  
4. **Mettre en cache** chaque réponse pour éviter les rechargements inutiles.  
5. **Peupler** chaque `<select>` cible avec la méthode privée `_populate()`.

### Exemple d’usage en JS

```js
const trigger = document.querySelector("[name='projet.affectationProjets.id']");
new DynamicDropdownTreatment(trigger, {
  formSelector: '#monFormulaire'
});
```

---

## ♿ Accessibilité & UX

- L’élément `<select>` cible est **désactivé** (`disabled`) pendant le chargement.  
- Un **loader** est affiché dans le conteneur spécifié.  
- Si aucune option n’est retournée, seule l’option vide reste visible.  
- La **sélection précédente** est restaurée si elle figure toujours dans la nouvelle liste.

---

## 🔗 Intégration dans un champ `SelectOne`

Pour les workflows basés sur la metadata **`SelectOne`**, ajoutez simplement les propriétés dynamiques dans la configuration JSON du champ :

```json
{
  "name": "groupe_id",
  "type": "SelectOne",
  "label": "Groupe",
  "metadata": {
    "targetDynamicDropdown": "#sous_groupe_id",
    "targetDynamicDropdownApiUrl": "route('sousGroupes.getData')",
    "targetDynamicDropdownFilter": "groupe_id"
  }
}
```

Le framework lira ces métadonnées et initialisera automatiquement :

```js
new DynamicDropdownTreatment(
  document.querySelector("[name='groupe_id']"),
  { formSelector: '#formDetail' }
);
```

---

> 🍀 *Cette documentation peut être complétée avec des exemples spécifiques à vos modules ou services.*
