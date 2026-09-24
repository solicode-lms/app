# ISSUE-003 : Passer QCM par Apprenant (Module PkgPasserQcm)

## Contexte et Objectif
L'objectif est de créer une interface dédiée permettant aux apprenants de passer leurs QCMs. L'interface doit offrir une expérience utilisateur (UX/UI) moderne, se détachant du standard de gestion (AdminLTE), tout en restant compatible visuellement avec les couleurs globales.

Pour bien isoler cette logique d'interface spécifique, un nouveau module sans base de données (`PkgPasserQcm`) sera créé.

## Modifications Demandées par Composant

### 1. Architecture et Module
- Création du module **`PkgPasserQcm`** : Ce module ne contiendra aucune nouvelle table, il exploitera les modèles existants du module `PkgQcm`.
- Création du skill **`pkg-passer-qcm`** (via `sys-agent`) pour documenter et gérer les règles de gestion de cette interface.

### 2. UI / UX et Layouts
- **Layout Spécifique** : Création d'un layout dédié (`passer-qcm.blade.php`) différent du layout principal.
- **Tailwind CSS** : Utilisation de Tailwind au lieu de Bootstrap pour une interface plus "UI/UX".
- **Intégration Sans Vite (Initialement)** : Dans un premier temps, Tailwind et le JS seront intégrés directement via des balises de liens (CDN ou fichiers bruts) dans le layout, sans passer par le pipeline de build Vite.
- **Charte Graphique** : L'interface doit proposer une expérience riche tout en respectant une charte graphique compatible avec AdminLTE (couleurs d'accentuation).

## Planification des Sprints de Réalisation

### Sprint 1 : Validation de la Conception (UI/UX)
- Validation de la charte graphique et du design (Wireframing/Maquettes) avec le client.
- Définition précise des couleurs et du comportement de l'interface de passage du QCM (UX moderne).

### Sprint 2 : Infrastructure du Module & Layout
- Création du skill `pkg-passer-qcm` via le skill `sys-agent`.
- Génération de la structure du module `PkgPasserQcm`.
- Création du layout spécifique intégrant Tailwind CSS (via CDN) et les assets JS de base.

### Sprint 3 : Implémentation Fonctionnelle (Vues & Contrôleurs)
- Création des contrôleurs dédiés dans `PkgPasserQcm` pour charger les données (QCM, Questions, Propositions).
- Développement des vues Blade avec Tailwind CSS pour afficher l'interface de passage du QCM.
- Gestion de la soumission et de la sauvegarde des réponses (`RealisationQcm`, `ReponseQcm`).

## Skills Requis pour la Réalisation
- `sys-conception` : Pour le suivi de cette issue.
- `sys-ux` : Pour la validation de la charte graphique et l'ergonomie (Sprint 1).
- `sys-agent` : Pour la création du skill `pkg-passer-qcm`.
- `app-blade` : Pour la création du layout et le développement des vues Tailwind.
- `app-controller` : Pour la création des contrôleurs qui orchestrent le passage du QCM.
- `pkg-passer-qcm` : Pour l'orchestration des règles métier du module (une fois créé).
