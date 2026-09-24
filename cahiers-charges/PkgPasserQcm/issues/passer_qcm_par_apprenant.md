# ISSUE-001 : Passer QCM par Apprenant (Module PkgPasserQcm)

## Contexte et Objectif
L'objectif est de créer une interface dédiée permettant aux apprenants de passer leurs QCMs. L'interface doit offrir une expérience utilisateur (UX/UI) moderne, se détachant du standard de gestion (AdminLTE), tout en restant compatible visuellement avec les couleurs globales.

Pour bien isoler cette logique d'interface spécifique, un nouveau module sans base de données (`PkgPasserQcm`) sera créé.

## Modifications Demandées par Composant

### 1. Architecture et Module
- Création du module **`PkgPasserQcm`** : Ce module ne contiendra aucune nouvelle table, il exploitera les modèles existants du module `PkgQcm`.
- Création du skill **`pkg-passer-qcm`** (via `sys-agent`) pour documenter et gérer les règles de gestion de cette interface.

### 2. UI / UX et Layouts (Architecture V2)
- **Layout Spécifique** : Création d'un layout dédié (`passer-qcm.blade.php`) différent du layout principal.
- **Tailwind CSS & Alpine.js** : La V2 s'appuie sur Tailwind pour le style et **Alpine.js** pour la réactivité frontend, en adoptant une architecture orientée composants.
- **Intégration Sans Vite (Initialement)** : Dans un premier temps, Tailwind et Alpine.js seront intégrés directement via CDN dans le layout.
- **Charte Graphique** : L'interface doit proposer une expérience riche tout en respectant une charte graphique compatible avec AdminLTE (couleurs d'accentuation).

## Planification des Sprints de Réalisation

### Sprint 1 : Validation de la Conception (UI/UX)
- [ ] Validation de la charte graphique et du design (Wireframing/Maquettes) avec le client.
- [ ] Définition précise des couleurs et du comportement de l'interface de passage du QCM (UX moderne).

### Sprint 2 : Infrastructure du Module & Layout
- [ ] Création du layout spécifique intégrant Tailwind CSS et Alpine.js (via CDN).

### Sprint 3 : Implémentation Fonctionnelle (Composants Alpine)
- [ ] Création des contrôleurs dédiés dans `PkgPasserQcm` pour charger les données (QCM, Questions, Propositions).
- [ ] Développement des composants Blade/Alpine (ex: Timer, Question, Navigation) pour gérer le passage du QCM dynamiquement.
- [ ] Gestion de la soumission et de la sauvegarde des réponses en asynchrone ou classique.

## Skills Requis pour la Réalisation
- `app2-front-end` : Pour la conception de l'interface V2 avec Tailwind CSS et l'architecture des composants Alpine.js.
- `app-controller` : Pour la création des contrôleurs qui orchestrent le passage du QCM.
- `pkg-passer-qcm` : Pour l'orchestration des règles métier du module (une fois créé).
