# Spécification Indicateur 02 : Taux de Validation au Gating au Premier Essai ($R_{\text{gating}}$)

## 📌 1. Fiche d'Identité de l'Indicateur

- **Code / Symbole** : $R_{\text{gating}}$
- **Nom complet** : Taux de réussite au gating de prototype dès le premier essai
- **Axe de Recherche** : Axe 1 — Apprenant (Hypothèse H1 : Assimilation robuste des fondamentaux techniques et prévention de l'illusion de maîtrise)
- **Acteurs Cibles** :
  - **Apprenant** (sécurisation des prérequis avant passage au projet fil rouge)
  - **Formateur** (indicateur clé de régulation didactique et déclenchement des ateliers de remédiation)
  - **Responsable** (contrôle de la rigueur pédagogique et de l'équité certificative)
- **Unité de mesure** : Pourcentage ($0 \le R_{\text{gating}} \le 100\,\%$)
- **Périmètre Didactique** : Épreuve individuelle de Live Coding sur le Prototype (Niveau 2 — *Adapter*), d'une durée de 40 minutes, conditionnant l'accès au Projet Fil Rouge (Niveau 3 — *Transposer*).

---

## 🧮 2. Modélisation Mathématique & Formules d'Agrégation

### 2.1. Formule Individuelle (Niveau Micro — Apprenant $i$)
Pour chaque apprenant $i$ présenté à l'épreuve de Live Coding Prototype, l'issue de sa première tentative est une variable binaire :

$$\text{Gating}_1(i) = \begin{cases} 
1 & \text{si validé dès la tentative } 1 \ (\text{statut } \text{APPROVED}) \\
0 & \text{si ajourné à la tentative } 1 \ (\text{statut } \text{NOT\_VALIDATED} \text{ / Révision})
\end{cases}$$

En cas d'ajournement initial ($\text{Gating}_1 = 0$), l'apprenant bénéficie d'une remédiation ciblée et d'un second passage ($\text{tentative} = 2$) avant clôture du sprint, mais son statut pour cet indicateur de recherche reste fixé à $0$.

### 2.2. Formule Collective (Niveau Méso — Groupe / Promotion)
Ratio des validations au premier passage sur l'ensemble des apprenants ayant passé l'épreuve :

$$R_{\text{gating}} = \frac{N_{\text{validés\_essai\_1}}}{N_{\text{évalués}}} \times 100$$

- $N_{\text{validés\_essai\_1}} = \sum_{i=1}^{N_{\text{évalués}}} \text{Gating}_1(i)$ : Nombre de stagiaires validés dès la première tentative.
- $N_{\text{évalués}}$ : Effectif total des apprenants s'étant présentés à au moins une session de Live Coding Prototype.

### 2.3. Formule Établissement (Niveau Macro — Gouvernance)
Taux moyen de gating consolidé sur l'ensemble des cohortes d'un centre de formation :

$$R_{\text{gating\_macro}} = \frac{\sum_{g=1}^{G} N_{\text{validés\_essai\_1}}(g)}{\sum_{g=1}^{G} N_{\text{évalués}}(g)} \times 100$$

---

## 🔍 3. Audit de l'Existant dans Solicode LMS (Skills Métier)

L'audit croisé mené via `pkg-apprentissage`, `pkg-realisation-tache` et `app-db-savoir` établit les points suivants :

### 3.1. Ce qui existe déjà dans SoliLMS :
- **Entité Prototype dédiée** :
  - Le modèle `RealisationUaPrototype` (`PkgApprentissage`) gère le travail du prototype de l'apprenant adossé à l'UA (`realisation_ua_id`) et à la tâche (`realisation_tache_id`).
  - Champs existants : `note`, `bareme`, `remarque_formateur`, `date_debut`, `date_fin`.
- **Workflow de validation binaire** :
  - `workflow_taches` contient déjà l'état `APPROVED` (Validée) et l'état `NOT_VALIDATED` (Non validée).
- **Session de Live Coding** :
  - La colonne `is_live_coding` existe sur `realisation_taches`.

### 3.2. Écarts constatés (Ce qui manque pour calculer l'indicateur) :
1. **Absence de compteur de tentatives** :
   - Actuellement, ni `realisation_taches` ni `realisation_ua_prototypes` ne tracent le rang d'essai (`essai = 1`, `essai = 2`). Si un apprenant est ajourné (`NOT_VALIDATED`), puis repasse avec succès (`APPROVED`), la base ne conserve que l'état final et perd la trace de l'échec initial.
2. **Absence d'indicateur direct de validation au premier passage** :
   - Impossible sans scan complexe de `historique_realisation_taches` de distinguer un apprenant validé du 1er coup d'un apprenant validé après remédiation.

---

## 🛠️ 4. Modifications à Apporter à Solicode LMS

Pour implémenter $R_{\text{gating}}$ de manière performante, robuste et auditable :

### 4.1. Modifications Base de Données (Incluses dans le Sprint 1)

1. **Table `realisation_ua_prototypes`** (ou `realisation_taches`) :
   - Ajouter la colonne `nombre_tentatives` (`int unsigned DEFAULT 0`) :
     - Incrémentée chaque fois qu'une session de Live Coding est conclue par le formateur.
   - Ajouter la colonne `est_valide_premier_essai` (`boolean NULL DEFAULT NULL`) :
     - `true` : si la tâche passe à `APPROVED` dès sa 1ère évaluation (`nombre_tentatives == 1`).
     - `false` : si la tâche passe à `NOT_VALIDATED` ou `REVISION_NECESSAIRE` lors de sa 1ère évaluation.
     - `null` : tant que l'apprenant n'a pas encore passé son premier Live Coding.

### 4.2. Modifications Applicatives (Services & Hooks)

1. **Hook dans `RealisationUaPrototypeService` / `RealisationTacheCrudTrait`** :
   - Lors de la notation ou du changement d'état final du prototype par le formateur :
     ```php
     // Si c'est une évaluation de Live Coding Prototype
     if ($realisationTache->is_live_coding || $realisationUaPrototype) {
         $proto = $realisationUaPrototype ?? $realisationTache->realisationUaPrototype;
         
         if ($proto) {
             // Incrémentation de la tentative
             $proto->nombre_tentatives = ($proto->nombre_tentatives ?? 0) + 1;
             
             // Si c'est le tout premier essai
             if ($proto->nombre_tentatives === 1) {
                 $estValide = in_array($codeWorkflow, ['APPROVED']) || ($note >= 10);
                 $proto->est_valide_premier_essai = $estValide;
             }
             $proto->save();
         }
     }
     ```

2. **Service de Calcul dans `PkgTableauBord` (`DashboardCalculTrait`)** :
   - Méthode `calculerTauxGatingPremierEssai(int $affectationProjetId): array` :
     ```php
     $prototypes = RealisationUaPrototype::whereHas('realisationTache.realisationProjet', function($q) use ($affectationProjetId) {
         $q->where('affectation_projet_id', $affectationProjetId);
     })->whereNotNull('est_valide_premier_essai')->get();

     $totalEvalues = $prototypes->count();
     if ($totalEvalues === 0) {
         return ['taux' => 0.0, 'total' => 0, 'valides' => 0, 'en_remediation' => 0];
     }

     $valides1erEssai = $prototypes->where('est_valide_premier_essai', true)->count();
     $enRemediation = $totalEvalues - $valides1erEssai;
     $taux = round(($valides1erEssai / $totalEvalues) * 100, 1);

     return [
         'taux' => $taux,
         'total' => $totalEvalues,
         'valides' => $valides1erEssai,
         'en_remediation' => $enRemediation,
     ];
     ```

---

## 🚦 5. Seuils d'Alerte & Actionnabilité Didactique

| Zone | Seuil $R_{\text{gating}}$ | Diagnostic Didactique | Action Dashboard Immédiate |
| :--- | :---: | :--- | :--- |
| 🟢 **Excellence** | $\ge 80\,\%$ | Excellente assimilation des tutoriels Niveau 1. Fondations solides pour aborder le projet Fil Rouge. | Badge vert *"Maîtrise validée"*. Autorisation d'accès au projet. |
| 🟡 **Vigilance** | $65\,\% \le R < 80\,\%$ | Hétérogénéité du groupe. Certains prérequis ont été survolés lors de la phase d'imitation. | Badge orange. Recommandation d'accentuer le peer-learning collectif. |
| 🔴 **Alerte Didactique** | $< 65\,\%$ | **Illusion d'acquis structurelle** : le guidage initial n'a pas produit d'autonomie. Risque d'échec massif sur le projet. | **Action Formateur Impérative** : Verrouiller l'accès au projet. Ouvrir des ateliers de remédiation ciblée et planifier la 2nde tentative. |

---

## 📊 6. Rendu UI Prévu dans les Maquettes

- **Vue Apprenant (`dashboard-apprenant.html`)** :
  - Badge Statut Gating :
    - Si validé 1er essai : *"Gating Prototype Validé (Essai 1/2) — Accès Projet Débloqué"* (Vert).
    - Si ajourné : *"Gating en Remédiation — Séance d'étayage programmée (Essai 2 en attente)"* (Ambre).
- **Vue Formateur (`dashboard-formateur.html`)** :
  - Carte KPI : *"Taux de Réussite Gating : 76%"* (Badge orange) avec sous-texte *"22 validés / 7 en remédiation"*.
  - Bouton d'action directe sur la carte : *"Planifier session de rattrapage (7 apprenants)"*.
- **Vue Responsable (`dashboard-responsable.html`)** :
  - Jauge docimologique de conformité et benchmark des taux de gating par formateur pour harmoniser les critères d'exigence.
