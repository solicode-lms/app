# Capacité : Structuration d'un Plan de Réalisation
**Skill Associé :** `sys-analyse`

## 🎯 Objectif
Définir les règles méthodologiques et architecturales pour structurer un plan de réalisation ordonné, sans ambiguïté, garantissant la cohérence avec le générateur Gapp et les règles critiques de Solicode LMS.

---

## 🛑 Règles d'Or de la Planification (Inviolables)

1. **Règle BDD au Sprint 1 (CRITIQUE)** :
   - Toutes les modifications de schéma de base de données (migrations, ajouts de colonnes `reference`, tables, index) DOIVENT être impérativement regroupées dans le **Sprint 1**.
   - Il est strictement interdit d'éparpiller des migrations BDD sur plusieurs sprints ultérieurs.
   - Le Sprint 1 se conclut obligatoirement par la synchronisation Gapp (`php artisan migrate` par le développeur, puis `gapp meta:sync` et régénération CRUD).
2. **Ordre des Dépendances Techniques** :
   - **Sprint 1** : Base de données, Migrations, Modèles/Enums, Sync Gapp.
   - **Sprint 2** : Services Métier, Logique de calcul, Traitement de données, Hooks.
   - **Sprint 3** : Contrôleurs, FormRequests, ViewState, Routes web.
   - **Sprint 4** : Frontend, Composants Blade / Alpine.js / Tailwind CSS, Ergonomie.
3. **Traçabilité des Skills par Tâche** :
   - Chaque tâche ou sprint du plan doit mentionner explicitement le ou les skills d'agent nécessaires à sa réalisation (ex: `app-migration`, `app-model`, `app-service`, `app-controller`, `app-blade`, `app2-front-end`).

---

## 📋 2. Structure Standard d'un Plan de Réalisation

Un plan de réalisation complet doit être structuré selon les sections suivantes :

### A. Synthèse des Besoins & Backlog des Fonctionnalités
- Liste numérotée des fonctionnalités retenues (User Stories / Epics).
- Priorisation (Must have, Should have, Could have).
- Acteurs cibles (Apprenant, Formateur, Responsable).

### B. Matrice des Impacts par Package SoliLMS
- Tableau croisé des packages impactés (`Pkg*`) et de la nature des changements (BDD, Service, UI).

### C. Découpage en Sprints Séquentiels
Pour chaque sprint :
- **Numéro et Titre du Sprint**
- **Objectif du Sprint**
- **Liste des Tâches Détaillées** (avec cases à cocher `[ ]`)
- **Livrables attendus**
- **Skills Requis pour l'Exécution**

### D. Diagramme Mermaid (Gantt ou Dépendances)
- Schéma visuel illustrant l'enchaînement chronologique des sprints et des livrables.
