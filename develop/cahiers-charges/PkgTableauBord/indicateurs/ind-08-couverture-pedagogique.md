# Spécification Indicateur 08 : Indicateur de Couverture Pédagogique ($\text{ICP}$)

## 📌 1. Fiche d'Identité de l'Indicateur

- **Code / Symbole** : $\text{ICP}$
- **Nom complet** : Indicateur de Couverture Pédagogique du Référentiel National
- **Axe de Recherche** : Axe 3 — Institution (Hypothèse H3 : Évaluation de l'exhaustivité des apprentissages et élimination des impasses techniques en pédagogie active)
- **Acteurs Cibles** :
  - **Responsable de formation** (gouvernance académique, respect du programme ministériel/OFPPT)
  - **Formateur** (identification des micro-compétences délaissées ou non atteintes par la classe)
  - **Apprenant** (jauge d'acquisition de son profil de compétences professionnelles)
- **Unité de mesure** : Pourcentage ($0 \le \text{ICP} \le 100\,\%$)
- **Périmètre Didactique** : Ensemble des micro-compétences officielles prescrites par le référentiel de compétences pour chaque module de formation.

---

## 🧮 2. Modélisation Mathématique & Formules d'Agrégation

### 2.1. Formule Individuelle (Niveau Micro — Apprenant $i$)
Taux de micro-compétences validées par un apprenant $i$ sur le total prescrit par le module :

$$\text{ICP}(i) = \frac{C_{\text{validées}}(i)}{C_{\text{référentiel}}} \times 100$$

- $C_{\text{validées}}(i)$ : Nombre de micro-compétences ayant atteint l'état `DONE` dans `RealisationMicroCompetence` pour le stagiaire $i$.
- $C_{\text{référentiel}}$ : Nombre total de micro-compétences prescrites dans le référentiel du module.

### 2.2. Formule Collective (Niveau Méso — Promotion / Module)
Part globale des micro-compétences effectivement validées sur l'ensemble de la cohorte de $N$ apprenants inscrits :

$$\text{ICP} = \frac{\sum_{i=1}^{N} C_{\text{validées}}(i)}{N \times C_{\text{référentiel}}} \times 100$$

### 2.3. Formule Établissement (Niveau Macro — Gouvernance Filière / Centre)
Taux moyen de couverture pédagogique consolidé sur l'ensemble des modules d'une filière :

$$\text{ICP}_{\text{macro}} = \frac{1}{\sum_{m=1}^{M} N_m \times C_{\text{réf}}(m)} \sum_{m=1}^{M} \left( \sum_{i=1}^{N_m} C_{\text{validées}}(i, m) \right) \times 100$$

---

## 🔍 3. Audit de l'Existant dans Solicode LMS (Skills Métier)

L'audit approfondi mené via `pkg-apprentissage` et `app-db-savoir` confirme que le modèle de données de Solicode LMS est idéalement structuré pour cet indicateur :

### 3.1. Ce qui existe déjà dans SoliLMS :
- **Hiérarchie pédagogique complète** :
  - `Module` $\rightarrow$ `Competence` $\rightarrow$ `MicroCompetence` $\rightarrow$ `UniteApprentissage`.
- **Modèle de suivi d'apprentissage par niveau** :
  - `RealisationModule` $\rightarrow$ `RealisationCompetence` $\rightarrow$ `RealisationMicroCompetence`.
- **Validation des micro-compétences** :
  - L'entité `RealisationMicroCompetence` dispose d'un statut lié à `EtatRealisationMicroCompetence` (dont le code `DONE` sanctionne l'acquisition validée).
  - Les cascades de validation lors de la validation d'une UA mettent déjà à jour l'état de `RealisationMicroCompetence`.

### 3.2. Écarts constatés (Ce qui manque pour calculer l'indicateur) :
1. **Absence d'agrégation d'ICP au niveau cohorte / groupe** :
   - `PkgApprentissage` calcule la progression individuelle (`progression_cache`), mais n'expose pas de service calculant l'ICP moyen consolidé d'un groupe pour le tableau de bord du formateur et du responsable.
2. **Absence de matrice matricielle d'impasse pédagogique** :
   - Il n'existe pas de vue permettant d'isoler en un clin d'œil la liste des micro-compétences dont le taux de validation au sein du groupe est anormalement bas ($< 50\%$).

---

## 🛠️ 4. Modifications à Apporter à Solicode LMS

### 4.1. Modifications Base de Données (Sprint 1)
- La structure relationnelle existante couvre l'intégralité du besoin sans création de table supplémentaire.
- Ajout d'un index composite d'optimisation sur `realisation_micro_competences (micro_competence_id, etat_realisation_micro_competence_id)` pour garantir des calculs instantanés sur des cohortes volumineuses.

### 4.2. Modifications Applicatives (Services & Algorithmes)

1. **Service de Calcul dans `PkgTableauBord` (`DashboardCalculTrait`)** :
   ```php
   public function calculerCouverturePedagogique(int $moduleId, int $groupeId): array
   {
       // 1. Récupérer l'effectif des apprenants du groupe
       $apprenantsIds = Apprenant::whereHas('groupes', fn($q) => $q->where('groupes.id', $groupeId))->pluck('id');
       $n = $apprenantsIds->count();
       if ($n === 0) {
           return ['icp' => 0.0, 'total_micro_competences' => 0, 'impasses' => []];
       }

       // 2. Compter le nombre de micro-compétences prescrites pour le module
       $microCompetences = MicroCompetence::whereHas('competence', fn($q) => $q->where('module_id', $moduleId))->get();
       $cReferentiel = $microCompetences->count();
       if ($cReferentiel === 0) {
           return ['icp' => 100.0, 'total_micro_competences' => 0, 'impasses' => []];
       }

       // 3. Récupérer l'état validé (DONE)
       $etatDoneId = EtatRealisationMicroCompetence::where('code', 'DONE')->value('id');

       // 4. Compter le total de validations effectives sur la cohorte
       $validationsParMicro = RealisationMicroCompetence::whereIn('apprenant_id', $apprenantsIds)
           ->whereIn('micro_competence_id', $microCompetences->pluck('id'))
           ->where('etat_realisation_micro_competence_id', $etatDoneId)
           ->select('micro_competence_id', DB::raw('count(*) as total_valide'))
           ->groupBy('micro_competence_id')
           ->pluck('total_valide', 'micro_competence_id');

       $totalValidations = $validationsParMicro->sum();
       $icp = round(($totalValidations / ($n * $cReferentiel)) * 100, 1);

       // 5. Détecter les impasses (micro-compétences validées par moins de 50% du groupe)
       $impasses = [];
       foreach ($microCompetences as $mc) {
           $nbValides = $validationsParMicro[$mc->id] ?? 0;
           $taux = round(($nbValides / $n) * 100, 1);
           if ($taux < 50.0) {
               $impasses[] = [
                   'code' => $mc->code ?? $mc->reference,
                   'titre' => $mc->titre,
                   'taux_validation' => $taux,
                   'nb_valides' => $nbValides,
                   'nb_requis' => $n,
               ];
           }
       }

       return [
           'icp' => $icp,
           'effectif' => $n,
           'c_referentiel' => $cReferentiel,
           'total_validations' => $totalValidations,
           'impasses' => $impasses,
       ];
   }
   ```

---

## 🚦 5. Seuils d'Alerte & Actionnabilité Didactique

| Zone | Seuil $\text{ICP}$ | Diagnostic Didactique | Action Dashboard Immédiate |
| :--- | :---: | :--- | :--- |
| 🟢 **Excellence Complète** | $\ge 90\,\%$ | Couverture exhaustive du référentiel national. Aucune compétence intermédiaire n'a été sacrifiée. | Badge vert *"Couverture optimale"*. |
| 🟡 **Vigilance** | $75\,\% \le \text{ICP} < 90\,\%$ | Quelques micro-compétences secondaires en retard d'acquisition. | Badge orange. Visualisation des compétences en attente. |
| 🔴 **Alerte Impasse** | $< 75\,\%$ en fin de cycle | **Parcours tronqué** : contournement de compétences techniques clés par manque de temps. | **Action Responsable / Formateur** : Programmation impérative de sessions de rattrapage sur les micro-compétences ciblées. |

---

## 📊 6. Rendu UI Prévu dans les Maquettes

- **Vue Apprenant (`dashboard-apprenant.html`)** :
  - Jauge circulaire ou barre de progression : *"Ma Couverture Référentiel : 88%"* (22 / 25 micro-compétences validées).
- **Vue Formateur (`dashboard-formateur.html`)** :
  - Carte KPI : *"Couverture Pédagogique (ICP) : 84%"*.
  - Section *"Compétences à risque / Impasses"* : alerte sur les 2 micro-compétences ayant un taux d'acquisition $< 50\%$ pour les intégrer au prochain sprint.
- **Vue Responsable (`dashboard-responsable.html`)** :
  - KPI de Gouvernance Majeur : *"ICP Moyen Établissement : 91.2%"* (Conformité réglementaire nationale certifiée).
  - Matrice de chaleur (Heatmap) comparant la couverture des modules entre toutes les promotions.
