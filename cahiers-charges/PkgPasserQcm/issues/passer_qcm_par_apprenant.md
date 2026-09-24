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
- [x] Création d'un dossier `charte-graphique` dans `cahiers-charges/PkgPasserQcm`.
- [x] Réalisation de la charte graphique et de la maquette en HTML/CSS (Tailwind CDN) pour valider le rendu visuel.
- [x] Intégration du comportement de pagination par étapes (chaque étape représente une Unité d'Apprentissage - UA).
- [x] Définition précise des couleurs, de la typographie, et des animations (UX moderne).

### Sprint 2 : Infrastructure du Module & Layout
- [x] Création du layout spécifique intégrant Tailwind CSS et Alpine.js (via CDN).

### Sprint 3 : Routing & Contrôleur (Données)
- [ ] Définition de la route web (ex: `/passer-qcm/{realisation_qcm_id}`).
- [ ] Création du `PasserQcmController`.
- [ ] Chargement des données nécessaires : `RealisationQcm`, l'Apprenant, le `Qcm`, et la hiérarchie des Questions/Propositions regroupées par Unité d'Apprentissage (UA).

### Sprint 4 : Découpage en Composants Blade (Statique)
- [ ] Découpage de la maquette HTML en composants Blade anonymes (ex: `<x-passer-qcm.sidebar>`, `<x-passer-qcm.question-card>`).
- [ ] Création de la vue principale utilisant le layout `passer-qcm` et les composants.
- [ ] Injection des données réelles du contrôleur dans les composants Blade (affichage statique).

### Sprint 5 : Dynamisation Frontend (Alpine.js)
- [ ] Ajout du contexte global Alpine (`x-data`) pour stocker l'état du QCM (UA active, réponses sélectionnées).
- [ ] Rendre la navigation de la Sidebar interactive (changement d'UA sans recharger la page).
- [ ] Rendre les options de réponses sélectionnables et mémoriser le choix.
- [ ] Création et activation du composant Timer.

### Sprint 6 : Soumission et Sauvegarde (Backend)
- [ ] Ajout de la méthode de soumission dans le `PasserQcmController`.
- [ ] Gestion de la validation de la requête.
- [ ] Sauvegarde des réponses de l'apprenant via les services (`RealisationQcmService`, etc.).
- [ ] Redirection et feedback (QCM terminé).

## Skills Requis pour la Réalisation
- `app2-front-end` : Pour la conception de l'interface V2 avec Tailwind CSS et l'architecture des composants Alpine.js.
- `app-controller` : Pour la création des contrôleurs qui orchestrent le passage du QCM.
- `pkg-passer-qcm` : Pour l'orchestration des règles métier du module (une fois créé).
