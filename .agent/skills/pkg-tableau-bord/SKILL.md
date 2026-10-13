---
name: pkg-tableau-bord
description: Expert du module PkgTableauBord — Architecture de données, calcul de l'Indicateur 01 (T_amorcage) et composants UI V2. Enrichi de façon itérative par sprint.
---

# Skill : Expert Tableau de Bord (PkgTableauBord) — Sprint 1

## 🎯 Périmètre Global
**Mission** : Fournir à l'IA une maîtrise rigoureuse de l'architecture, des flux de données, des calculs statistiques et des composants graphiques du module **PkgTableauBord** pour le **Sprint 1 : Indicateur 01 — Démarrage du travail ($T_{\text{amorçage}}$)**.

> [!NOTE]
> **Démarche Itérative** : Conformément à la méthodologie projet, ce skill est développé de façon itérative sprint par sprint. Il contient uniquement les connaissances et règles du Sprint 1, et sera enrichi lors de chaque sprint subséquent.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Zéro Calcul dans les Contrôleurs ou Blade (CRITIQUE)** : Il est formellement interdit de calculer un indicateur, une moyenne ou une formule mathématique dans un contrôleur ou une vue Blade. Tout calcul passe exclusivement par la couche Service (`TableauBordService`, `DashboardIndicateur01Trait`).
2. **Ordre d'Appel ViewState Obligatoire** : Toujours appeler `$this->viewState->setContextKeyIfEmpty(...)` **AVANT** tout appel à `loadLastFilterIfEmpty()` pour retrouver les filtres persistés de l'utilisateur.
3. **Intégrité BDD & Convention `cache_`** : Ne jamais modifier les fichiers `Base/` générés par Gapp. Tout champ dénormalisé ou issu d'un calcul pour accélérer les requêtes KPI DOIT porter le préfixe `cache_` (ex: `cache_date_lancement`, `cache_date_premiere_soumission`). L'agent ne doit jamais exécuter `php artisan migrate` (réservé au développeur).
4. **Stack Frontend V2** : Interdiction d'utiliser Bootstrap ou jQuery pour les composants du tableau de bord. Utiliser exclusivement Tailwind CSS et Alpine.js avec une architecture orientée composants.
5. **Architecture Layout V2 & Vues Épurées (CRITIQUE)** :
   - Toute vue de tableau de bord V2 doit obligatoirement étendre `@extends('layouts.admin-v2')`.
   - Interdiction formelle de recopier la structure globale de maquette (doctype, head, aside sidebar, header, footer, scripts) dans les vues Blade de contenu.
   - Les vues de production contiennent exclusivement les indicateurs fonctionnels calculés du sprint en cours, à l'exclusion de toute carte ou bloc factice issu de maquettes des sprints futurs.

---

## ⚡ Actions (Orchestration Sprint 1)

### Action A : Comprendre la Structure de Données (Sprint 1)
> **Description** : Expliquer les colonnes d'enrichissement et relations de données pour l'indicateur d'amorçage.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-bdd-pkg-tableau-bord.md`
- **Entrées** : `Question sur cache_date_lancement ou cache_date_premiere_soumission`
- **Sorties** : Description des colonnes, index, règles de persistance, tables d'accueil (`affectation_projets`, `realisation_projets`) et commande artisan de rattrapage `php artisan dashboard:recalculer-amorcage`.

### Action B : Calculer ou Déboguer l'Indicateur 01 ($T_{\text{amorçage}}$)
> **Description** : Expliquer, implémenter ou corriger l'algorithme du temps d'amorçage individuel et collectif.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-indicateurs-pkg-tableau-bord.md`
- **Entrées** : `RealisationProjetId`, `AffectationProjetId`, `GroupeIds`
- **Sorties** : Formule mathématique, seuils d'alerte didactiques, code PHP de calcul et formatage (ex: `1h15`).

### Action C : Concevoir et Intégrer les Composants Dashboard V2
> **Description** : Guider la création des composants Blade réactifs avec Tailwind CSS pour les 3 acteurs et leur intégration navigation.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-fonctionnalites-pkg-tableau-bord.md`
- **Sorties** :
  - Layout : `layouts.admin-v2`
  - Vues : `PkgTableauBord::apprenant.index`, `PkgTableauBord::formateur.index`, `PkgTableauBord::responsable.index`
  - Composant : `PkgTableauBord::components.kpi-amorcage`
  - Navigation : Intégration dans `resources/views/layouts/menu-sidebar.blade.php` conditionnée par rôles.

---

## 🛠️ Règles de Gestion (Savoir-Faire Métier Sprint 1)

### 1. `règle-gestion-bdd-pkg-tableau-bord.md`
- **Rôle** : Description des colonnes `cache_` ajoutées aux tables SoliLMS (`affectation_projets` et `realisation_projets`).

### 2. `règle-gestion-indicateurs-pkg-tableau-bord.md`
- **Rôle** : Spécification complète de l'Indicateur 01 ($T_{\text{amorçage}}$) : modélisation mathématique, seuils d'alerte et actions.

### 3. `règle-gestion-fonctionnalites-pkg-tableau-bord.md`
- **Rôle** : Fonctionnalités du Sprint 1 pour les trois profils (Apprenant, Formateur, Responsable).

---

## 🔄 Scénarios d'Exécution (Sprint 1)

### Scénario 1 : "Comment est calculé le temps d'amorçage d'un apprenant ?"
1. Lire `règle-gestion-indicateurs-pkg-tableau-bord.md`.
2. Vérifier que `cache_date_premiere_soumission` et `cache_date_lancement` sont renseignés sur `realisation_projets`.
3. Calculer la différence en minutes : `cache_date_premiere_soumission - cache_date_lancement`.
4. Formater en heures et minutes (ex: `75 min` $\rightarrow$ `1h15`).
5. Comparer à la moyenne de la promotion pour afficher le badge d'état (*Dans les temps* vs *En retard*).

### Scénario 2 : "Quand est renseignée la première soumission ?"
1. Déclencheur : Une tâche passe à l'état `TO_APPROVE` ou `READY_FOR_LIVE_CODING`.
2. Événement intercepté par `RealisationTacheJobTrait::updatedObserverJob`.
3. Si `realisationProjet->cache_date_premiere_soumission` est `null`, mettre à jour avec `now()`.
4. Si déjà renseigné : ne rien faire (idempotence).
