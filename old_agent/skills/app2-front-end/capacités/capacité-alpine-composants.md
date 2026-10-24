# Capacité : Composants Alpine.js & Tailwind CSS (V2)

## 1. Philosophie V2
L'architecture V2 conserve le backend standard de SoliLMS (Controllers, Services, Modèles) mais remplace complètement la couche de présentation (AdminLTE, Bootstrap, jQuery) par **Tailwind CSS** et **Alpine.js**.

## 2. Architecture Orientée Composants
La logique frontend n'est plus dispersée dans de grands scripts JS. Elle est encapsulée dans des composants réutilisables.

### Principes
1. **Composants Blade Anonymes / Classes** : Les composants doivent être créés via le système de composants de Laravel (`resources/views/components/`).
2. **Isolation Alpine** : Chaque composant Blade responsable d'une logique dynamique doit déclarer son propre `x-data`.
3. **Fichiers JS dédiés (ES Modules)** : Pour toute logique complexe ou réutilisable, chaque composant Alpine (`Alpine.data()`) DOIT être déclaré dans son propre fichier JS séparé sous forme de module ES6 exportable (ex: `zoneCentraleComponent.js`, `sidebarComponent.js`). Ne jamais regrouper plusieurs composants distincts dans un même fichier fourre-tout.

## 3. Tailwind CSS
- Toutes les classes de style doivent utiliser Tailwind CSS.
- Dans le cadre de la transition, Tailwind peut être inclus via CDN, mais les classes doivent respecter l'utilitaire strict.

## 4. Architecture JavaScript (ES Modules)
Afin d'éviter tout problème de chargement asynchrone et d'initialisation, le code JS front-end s'appuie sur une structure modulaire stricte :

1. **Point d'entrée unique (`app.js`)** : 
   - Est chargé via `<script type="module" src="..."></script>` dans Blade.
   - Gère **seul** l'importation de la librairie AlpineJS via ESM (`import Alpine from '...module.esm.js'`). Il ne faut donc pas ajouter de balise `<script defer>` pour Alpine dans le Layout Blade.
   - Importe et enregistre tous les composants/stores locaux en leur passant l'instance Alpine.
   - Démarre explicitement Alpine avec `Alpine.start()`.

2. **Dossiers `components/` et `stores/`** : 
   - Chaque composant ou store est isolé dans son fichier.
   - Le fichier exporte une fonction par défaut prenant `Alpine` en paramètre (ex: `export default function registerTimerComponent(Alpine) { ... }`).

3. **Constantes métier séparées (`constants.js`) (Règle Stricte)** : 
   - Aucune chaîne de caractères métier "en dur" (Magic Strings) n'est autorisée dans les composants ou stores (ex: types de questions, états).
   - Ces valeurs doivent être exportées depuis un fichier `constants.js` centralisé.
   - Exemple : `export const QUESTION_TYPES = { CHOIX_MULTIPLE: 'choix multiple' };`

**Exemple du point d'entrée (`app.js`) :**
```javascript
import Alpine from 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/module.esm.js';
import registerQcmStore from './stores/qcmStore.js';
import registerTimerComponent from './components/timerComponent.js';

window.Alpine = Alpine;

registerQcmStore(Alpine);
registerTimerComponent(Alpine);

Alpine.start();
```
