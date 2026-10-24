# Capacité : Composants Preline UI & Tailwind CSS (V2)

## 1. Rôle et Philosophie
**Preline UI** est la bibliothèque officielle de composants UI interactifs pour l'interface **V2** de Solicode LMS. Elle s'intègre nativement par-dessus **Tailwind CSS** et remplace AdminLTE/Bootstrap/Select2 pour tous les éléments d'interface dynamiques :
- Sélecteurs avancés avec recherche et templates d'options (`HSSelect` / `data-hs-select`)
- Menus déroulants (`HSDropdown` / `hs-dropdown`)
- Modales et panneaux coulissants (`HSOverlay` / `hs-overlay`)
- Onglets et accordéons (`HSTabs` / `hs-tab`, `HSAccordion`)
- Infobulles et popovers (`HSTooltip`)

---

## 2. Intégration dans le Layout V2 (`layouts/admin-v2.blade.php`)

Preline UI nécessite le plugin `@tailwindcss/forms` et la dépendance de positionnement **Floating UI** :

```html
<!-- 1. Tailwind CSS avec plugin forms dans le <head> -->
<script src="https://cdn.tailwindcss.com?plugins=forms"></script>

<!-- 2. Floating UI & Preline JS avant la fin du <body> -->
<script src="https://cdn.jsdelivr.net/npm/@floating-ui/dom@latest/dist/floating-ui.dom.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.js"></script>
```

Pour ré-initialiser les composants après une injection dynamique dans le DOM :
```javascript
window.HSStaticMethods?.autoInit();
```

---

## 3. Composant Select Avancé (`data-hs-select`)

Le composant Select de Preline transforme un `<select>` HTML natif en menu personnalisé moderne tout en préservant la sémantique de formulaire.

### Structure Blade Recommandée
Le `<select>` natif porte `class="hidden"` et les attributs de configuration JSON dans `data-hs-select` :

```blade
<div class="relative inline-flex items-center">
    <select id="mon_select" 
            name="mon_select" 
            data-hs-select='{
                "hasSearch": true,
                "searchPlaceholder": "Rechercher...",
                "searchClasses": "block w-full text-xs bg-slate-50 border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3 mb-1",
                "searchWrapperClasses": "p-2 bg-white sticky top-0 border-b border-slate-100 z-10",
                "placeholder": "Sélectionner une option...",
                "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-9 pe-8 inline-flex justify-between items-center text-start bg-indigo-50/70 hover:bg-indigo-50 border border-indigo-200 hover:border-indigo-300 text-indigo-900 text-xs font-bold rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer shadow-2xs transition truncate min-w-[200px] max-w-[320px]",
                "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1.5 space-y-0.5 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden overflow-y-auto",
                "optionClasses": "py-2 px-3 w-full text-xs font-medium text-slate-800 cursor-pointer hover:bg-indigo-50 hover:text-indigo-900 rounded-lg focus:outline-none focus:bg-indigo-50 focus:text-indigo-900 transition flex items-center justify-between",
                "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title class=\"truncate\"></span><span class=\"hidden hs-selected:block ms-2 text-indigo-600\"><i class=\"fas fa-check text-[10px]\"></i></span></div>",
                "extraMarkup": "<div class=\"absolute top-1/2 start-3 -translate-y-1/2 text-indigo-600 pointer-events-none\"><i class=\"fas fa-filter text-xs\"></i></div><div class=\"absolute top-1/2 end-2.5 -translate-y-1/2 text-indigo-500 pointer-events-none\"><i class=\"fas fa-chevron-down text-[10px]\"></i></div>"
            }' 
            class="hidden">
        @foreach($options as $option)
            <option value="{{ $option->id }}" {{ $selectedId == $option->id ? 'selected' : '' }}>
                {{ $option->nom }}
            </option>
        @endforeach
    </select>
</div>
```

### Soumission Automatique & Événements (`onchange`)
Preline met à jour la valeur du select sous-jacent et déclenche son événement. Pour garantir une soumission instantanée du formulaire au changement :

```javascript
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('mon_select');
    if (!select) return;

    let isSubmitting = false;
    const submitForm = () => {
        if (!isSubmitting) {
            isSubmitting = true;
            select.form?.submit();
        }
    };

    // Écoute du changement sur le select natif
    select.addEventListener('change', submitForm);

    // Écoute via l'instance Preline HSSelect
    const bindPreline = () => {
        if (window.HSSelect) {
            const instance = window.HSSelect.getInstance(select);
            if (instance && !instance._autoSubmitBound) {
                instance._autoSubmitBound = true;
                instance.on('change', submitForm);
            }
        }
    };

    bindPreline();
    window.addEventListener('load', bindPreline);
});
```

---

## 4. Règles d'Or & Conventions SoliLMS V2

1. **Cohérence Visuelle** :
   - Couleur d'accentuation principale : `indigo-600` / `indigo-50` / `indigo-900`.
   - Neutres : `slate-50` pour les fonds, `slate-200` pour les bordures, `slate-800` pour le texte.
   - Arrondis : `rounded-xl` pour les boutons/sélecteurs, `rounded-2xl` pour les conteneurs/cartes.
2. **Accessibilité & Sémantique** :
   - Toujours conserver un `<label for="..." class="sr-only">` explicite pour les lecteurs d'écran.
   - Ne jamais supprimer les attributs `aria-expanded` ou `data-title`.
3. **Préservation des Requêtes GET** :
   - Les sélecteurs de filtrage doivent préserver l'ensemble des autres paramètres d'URL via des `<input type="hidden">` dans le formulaire englobant.
