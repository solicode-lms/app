# Capacité : Diagnostic de l'Existant et Analyse d'Écart (Gap Analysis)
**Skill Associé :** `sys-analyse`

## 🎯 Objectif
Fournir la démarche méthodologique pour auditer la structure actuelle de Solicode LMS en s'appuyant sur les skills `pkg-*` et déterminer avec précision les écarts fonctionnels et techniques nécessaires pour couvrir un nouveau besoin.

---

## 🔍 1. Consultation Obligatoire des Skills Métier (`pkg-*`)

Avant toute proposition de fonctionnalité ou de modification, l'analyste DOIT consulter les skills du domaine concerné afin d'aligner le besoin sur la réalité du code existant :

| Domaine Fonctionnel | Skill à Invoquer / Consulter | Rôle & Ressources à Inspecter |
| :--- | :--- | :--- |
| **Évaluation & Notes** | `pkg-apprentissage` | Notes par UA, progression, cascade de calculs, pondérations. |
| **Projets & Affectations** | `pkg-creation-projet` | Unités d'Apprentissage (UA), affectations, livrables attendus. |
| **Tâches & Réalisations** | `pkg-realisation-tache` | Statuts des réalisations (`A faire`, `En cours`, `En validation`, etc.), transitions, horodatages. |
| **QCM & Questionnaires** | `pkg-qcm` / `pkg-passer-qcm` | Banques de questions, réalisations QCM, scores, soumission synchrone/asynchrone. |
| **Structure BDD Globale** | `app-db-savoir` | Exploration directe de `db.sql` ou schémas existants sans supposition. |

---

## 📋 2. Grille d'Identification des Écarts (Gap Analysis)

Pour chaque nouveau besoin ou indicateur à calculer, passer systématiquement en revue les 5 dimensions suivantes :

### A. États et Machines à États (State Machines)
- Les états actuels de l'entité (ex: `RealisationTache`, `Sprint`, `Evaluation`) permettent-ils de tracer l'événement ?
- *Exemple* : Pour mesurer le temps de remédiation après Live Coding, un état `Besoin de révision` ou un horodatage d'échec de gating existe-t-il dans `pkg-realisation-tache` ?
- **Action si manquant** : Identifier le nouvel état à ajouter et le package responsable.

### B. Données & Horodatages (Traces Numériques)
- Les timestamps nécessaires sont-ils enregistrés nativement (ex: `date_debut`, `date_soumission`, `date_validation`) ?
- La table dispose-t-elle des clés étrangères requises pour les agrégations (ex: lien direct ou indirect vers `apprenant_id`, `groupe_id`, `etablissement_id`) ?
- **Action si manquant** : Proposer l'ajout de colonnes ou d'une table de traces/logs.

### C. Règles Métier & Hooks de Calcul
- Quel service métier calcule la donnée brute ?
- Existe-t-il déjà un hook de cycle de vie (`afterUpdateRules`, `afterCreateRules`) pour déclencher le calcul ?
- **Action si manquant** : Déterminer quel service de quel package doit héberger la logique.

### D. Niveaux d'Agrégation Requis
- **Micro** : Valeur individuelle par apprenant.
- **Méso** : Agrégation de cohorte / groupe (moyenne, écart-type, médiane).
- **Macro** : Agrégation par établissement ou filière.

---

## 📤 3. Format de Restitution du Diagnostic

Pour chaque besoin ou indicateur analysé, formuler la fiche d'impact sous la forme :

```markdown
### Analyse du Besoin : [Nom de la Fonctionnalité / Indicateur]
- **Objectif Métier** : [Explication claire du but didactique ou opérationnel]
- **Packages SoliLMS Ciblés** : [ex: PkgRealisationTache, PkgApprentissage]
- **Existant Audité** : [Ce qui existe actuellement dans le code / schéma]
- **Écarts Identifiés** :
  - Données manquantes : [colonnes, tables]
  - États manquants : [nouveaux statuts nécessaires]
  - Traitements manquants : [formule, service de calcul]
- **Modifications Requises par Package** :
  - `PkgRealisationTache` : Ajouter le statut X...
  - `PkgApprentissage` : Mettre à jour le calcul de note Y...
```
