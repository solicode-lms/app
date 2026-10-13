---
name: pkg-tableau-bord
description: Expert du module PkgTableauBord — architecture de données, calcul des 9 indicateurs de recherche, régulation FIFO Live Coding, règle docimologique 65/35 et composants UI V2.
---

# Skill : Expert Tableau de Bord (PkgTableauBord)

## 🎯 Périmètre Global
**Mission** : Fournir à l'IA une maîtrise exhaustive de l'architecture, des flux de données, des formules statistiques et des composants graphiques du module **PkgTableauBord**. Ce skill encadre les tableaux de bord des trois acteurs (Apprenant, Formateur, Responsable), l'implémentation des 9 indicateurs de l'Annexe M, le pilotage en temps réel de la file d'attente FIFO Live Coding, et le moteur docimologique officiel (règle 65/35).

### 🚫 Interdictions Globales (Règles d'Or)
1. **Zéro Calcul dans les Contrôleurs ou Blade (CRITIQUE)** : Il est formellement interdit de calculer un indicateur, une moyenne ou une formule mathématique dans un contrôleur ou une vue Blade. Tout calcul passe exclusivement par la couche Service (`TableauBordService`, `DashboardCalculTrait`, `ConversionEvaluationService`).
2. **Ordre d'Appel ViewState Obligatoire** : Toujours appeler `$this->viewState->setContextKeyIfEmpty(...)` **AVANT** tout appel à `loadLastFilterIfEmpty()` pour retrouver les filtres persistés de l'utilisateur.
3. **Intégrité BDD & Gapp** : Ne jamais modifier les fichiers `Base/` générés par Gapp. Toutes les évolutions de base de données (colonnes de timestamps, tables de logs, statuts) doivent être regroupées dans le premier sprint de réalisation.
4. **Stack Frontend V2** : Interdiction d'utiliser Bootstrap ou jQuery pour les composants du tableau de bord. Utiliser exclusivement Tailwind CSS et Alpine.js avec une architecture orientée composants.

---

## ⚡ Actions (Orchestration)

### Action A : Comprendre la Structure de Données du Module
> **Description** : Expliquer les entités, colonnes d'enrichissement et relations de données du module `PkgTableauBord`.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-bdd-pkg-tableau-bord.md`
- **Entrées** : `Question sur une entité (log_activite_formateurs, remediations, bordereaux_officiels)`
- **Sorties** : Description des relations Eloquent, index et règles de modélisation.

### Action B : Calculer ou Déboguer un Indicateur de Pilotage
> **Description** : Expliquer, implémenter ou corriger l'algorithme d'un des 9 indicateurs opérationnels de recherche.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-indicateurs-pkg-tableau-bord.md`
- **Entrées** : `Code de l'indicateur (T_amorcage, R_gating, F_revision, C_logistique, T_file, T_intervention, r, ICP, tau_conformite)`
- **Sorties** : Formule mathématique exacte, code PHP de calcul, seuils d'alerte et actions recommandées.

### Action C : Gérer la File d'Attente FIFO et le Live Coding
> **Description** : Encadrer la régulation en temps réel des passages de prototype en Live Coding (1-à-1 ou session mutualisée 4x4).
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-fonctionnalites-pkg-tableau-bord.md`
  - `règles-gestion/règle-gestion-indicateurs-pkg-tableau-bord.md`
- **Entrées** : `Actions de file (demarrer, ajourner, valider, session_groupe)`
- **Sorties** : Mise à jour des horodatages `date_entree_file`, `date_debut_live_coding`, et calcul du délai $T_{\text{file}}$.

### Action D : Gérer la Conversion Docimologique (Règle 65/35) & Bordereaux
> **Description** : Superviser le calcul officiel des notes de Contrôle Continu (CC sur 20) et d'Examen de Fin de Module (EFM sur 40) et l'exportation des bordereaux officiels.
- **Ressources Utilisées** :
  - `règles-gestion/règle-gestion-indicateurs-pkg-tableau-bord.md`
- **Entrées** : `ModuleId`, `GroupeId`
- **Sorties** : Rapport de conformité, détection des évaluations incomplètes, bordereau officiel certifié.

### Action E : Conception & Intégration des Composants Dashboard (V2)
> **Description** : Guider la création et le découpage des composants Blade anonymes réactifs (`<x-tableau-bord.*>`) avec Tailwind et Alpine.js.
- **Ressources Utilisées** :
  - `resources/template-tableau-bord.md`
- **Sorties** : Composants UI (Stat cards, jauges, nuages de points, tables d'alerte).

---

## 🛠️ Règles de Gestion (Savoir-Faire Métier)
*Documentation des fichiers situés dans le dossier `règles-gestion/`*

### 1. `règle-gestion-bdd-pkg-tableau-bord.md`
- **Rôle** : Description exhaustive des tables dédiées (`log_activite_formateurs`, `remediations`, `bordereaux_officiels`) et des enrichissements de tables SoliLMS (`realisation_taches`, `affectation_projets`, `realisation_ua_prototypes`, `workflow_taches`).

### 2. `règle-gestion-fonctionnalites-pkg-tableau-bord.md`
- **Rôle** : Spécification des fonctionnalités et cas d'usage par acteur (Apprenant : cap et auto-régulation ; Formateur : console FIFO et alerte $+10$ min ; Responsable : ICP, corrélation IA et conformité 65/35).

### 3. `règle-gestion-indicateurs-pkg-tableau-bord.md`
- **Rôle** : Répertoire officiel des 9 indicateurs de l'Annexe M : modélisation mathématique exacte, seuils d'alerte didactiques (vert/orange/rouge) et actions directes associées.

---

## 🔄 Scénarios d'Exécution

### Scénario 1 : "Comment calculer l'intégrité face à l'IA ($r$ de Pearson) ?"
1. Lire `règle-gestion-indicateurs-pkg-tableau-bord.md` section Indicateur 07.
2. Extraire les paires de notes normalisées sur 20 : $x_i$ (Prototype $N2$) et $y_i$ (Projet fil rouge $N3$).
3. Appliquer la formule de Pearson dans `DashboardCalculTrait`.
4. Isoler les apprenants présentant une divergence $\Delta_{\text{IA}} > +5$ points pour le jury de soutenance.

### Scénario 2 : "Comment fonctionne le déclenchement de remédiation à $+10$ min ?"
1. Lire `règle-gestion-fonctionnalites-pkg-tableau-bord.md` (Section Formateur).
2. Vérifier `VerifierSeuilDixMinutesJob` : filtre les apprenants présents n'ayant pas soumis leur livrable 10 min après la fin du Live Coding collectif.
3. Basculer les tâches au statut `BESOIN_REVISION`.
4. Afficher la liste d'alerte sur la console du formateur avec bouton d'assistance directe.

### Scénario 3 : "Comment exporter un bordereau officiel 65/35 sans erreur ?"
1. Appeler `ConversionEvaluationService::genererBordereauOfficiel($moduleId, $groupeId)`.
2. Vérifier que $100\%$ des apprenants disposent de leurs notes $N2$ et $N3$.
3. Calculer $\text{Note CC} = \text{Moyenne}(N2)$ et $\text{Note EFM} = 2 \times (0{,}65 \times \text{Note CC} + 0{,}35 \times N3)$.
4. Si aucune anomalie : autoriser l'export scellé et enregistrer le bordereau dans `bordereaux_officiels`.
