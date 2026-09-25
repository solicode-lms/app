# Règle de Gestion : Interface de Passage QCM

## 1. Contexte UI/UX
L'interface de passage des QCMs par les apprenants doit offrir une expérience utilisateur (UX/UI) moderne et immersive, se détachant du standard de gestion (AdminLTE).

## 2. Charte Graphique
- L'interface doit être riche visuellement.
- Les couleurs d'accentuation doivent rester compatibles avec la charte globale (par exemple les couleurs primaires d'AdminLTE).

## 3. Stack Technique (Vue)
- **Framework CSS** : Tailwind CSS est strictement utilisé à la place de Bootstrap pour ces interfaces.
- **Intégration Initiale (Sprint 2/3)** : L'utilisation de Vite est écartée dans un premier temps pour simplifier. Tailwind CSS (ainsi que tout script JS nécessaire) doit être intégré directement via des balises `<link>` (CDN) dans le `<head>` du layout spécifique.
- **Layout** : L'ensemble des vues de ce module doit étendre un layout dédié (`layouts.passer-qcm` par exemple), qui ne charge PAS les assets AdminLTE standards, mais uniquement les assets spécifiques à Tailwind.

## 4. Modèle de Données
- Le module `PkgPasserQcm` est un module purement fonctionnel/interface. Il ne possède pas de base de données en propre.
- Il manipule les données (lecture et sauvegarde) via les modèles du module `PkgQcm` (`Qcm`, `Question`, `PropositionReponse`, `RealisationQcm`, `ReponseQcm`).
