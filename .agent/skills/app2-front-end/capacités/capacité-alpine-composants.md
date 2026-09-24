# Capacité : Composants Alpine.js & Tailwind CSS (V2)

## 1. Philosophie V2
L'architecture V2 conserve le backend standard de SoliLMS (Controllers, Services, Modèles) mais remplace complètement la couche de présentation (AdminLTE, Bootstrap, jQuery) par **Tailwind CSS** et **Alpine.js**.

## 2. Architecture Orientée Composants
La logique frontend n'est plus dispersée dans de grands scripts JS. Elle est encapsulée dans des composants réutilisables.

### Principes
1. **Composants Blade Anonymes / Classes** : Les composants doivent être créés via le système de composants de Laravel (`resources/views/components/`).
2. **Isolation Alpine** : Chaque composant Blade responsable d'une logique dynamique doit déclarer son propre `x-data`.
3. **Fichiers JS dédiés (Optionnel mais recommandé)** : Pour une logique complexe, le `x-data` peut faire appel à une fonction JavaScript exportée globale (ex: `document.addEventListener('alpine:init', () => { Alpine.data('monComposant', () => ({})) })`).

## 3. Tailwind CSS
- Toutes les classes de style doivent utiliser Tailwind CSS.
- Dans le cadre de la transition, Tailwind peut être inclus via CDN, mais les classes doivent respecter l'utilitaire strict.

## 4. Exemple de Structure (Passage QCM)
Pour l'interface de passage de QCM, les composants suivants pourraient être créés :
- `<x-qcm.timer />` : Gère le décompte du temps via Alpine `setInterval`.
- `<x-qcm.question-card />` : Gère l'affichage d'une question et la sélection des réponses.
- `<x-qcm.navigation />` : Gère la pagination entre les questions.
