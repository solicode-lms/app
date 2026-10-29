# Spécification Indicateur 09 : Taux de Conformité de Conversion Institutionnelle ($\tau_{\text{conformité}}$)

## 📌 1. Fiche d'Identité de l'Indicateur

- **Code / Symbole** : $\tau_{\text{conformité}}$
- **Nom complet** : Taux de conformité docimologique de la règle de conversion 65/35
- **Axe de Recherche** : Axe 3 — Institution (Hypothèse H3 : Équité docimologique, suppression de toute divergence arithmétique et automatisation de l'évaluation sommative officielle)
- **Acteurs Cibles** :
  - **Responsable de formation / Directeur pédagogique** (sécurisation juridique et réglementaire des notes officielles transmises à l'institution)
  - **Formateur** (suppression de la double saisie manuelle et calcul instantané des bordereaux)
  - **Apprenant** (transparence docimologique et visibilité claire sur le barème officiel)
- **Unité de mesure** : Pourcentage ($0 \le \tau_{\text{conformité}} \le 100\,\%$)
- **Périmètre Institutionnel** : Génération automatisée des bordereaux officiels de Contrôle Continu (Note CC sur 20) et d'Examen de Fin de Module (Note EFM sur 40) à partir des évaluations formatives continues selon la règle 65/35.

---

## 🧮 2. Modélisation Mathématique & Formules d'Agrégation

### 2.1. Algorithme de Conversion Docimologique (Règle 65/35)
Pour chaque apprenant $i$ sur un module donné comportant $P$ Unités d'Apprentissage (UA) évaluées :

#### A. Note de Contrôle Continu (CC sur 20)
Moyenne arithmétique des notes de Live Coding individuel obtenues sur les prototypes des $P$ Unités d'Apprentissage :

$$\text{Note CC}(i) = \frac{1}{P} \sum_{p=1}^{P} \text{Note}(N2_p, i) \quad \in [0, 20]$$

- $\text{Note}(N2_p, i)$ : Note sur 20 attribuée au prototype de l'UA $p$ lors de la session de Live Coding sous surveillance.

#### B. Note d'Examen de Fin de Module (EFM sur 40)
Application de la règle de pondération 65/35 combinant l'autonomie surveillée (65%) et l'intégration du projet assisté par IA (35%), ramenée sur 40 :

$$\text{Note EFM}(i) = 2 \times \left( 0{,}65 \times \overline{\text{Note}}(N2, i) + 0{,}35 \times \text{Note}(N3, i) \right) \quad \in [0, 40]$$

- $\overline{\text{Note}}(N2, i) = \text{Note CC}(i)$ : Moyenne sur 20 des prototypes individuels.
- $\text{Note}(N3, i)$ : Note sur 20 obtenue lors de la soutenance finale du projet Fil Rouge.

### 2.2. Formule Collective de l'Indicateur de Recherche ($\tau_{\text{conformité}}$)
Proportion de bordereaux officiels générés sans aucune anomalie arithmétique, omission de note, ni discordance de barème :

$$\tau_{\text{conformité}} = \frac{N_{\text{bordereaux conformes sans anomalie}}}{N_{\text{total bordereaux générés}}} \times 100$$

- Critère de conformité sans anomalie pour un bordereau :
  1. $100\%$ des apprenants de la promotion disposent de notes $N2$ et $N3$ complètes.
  2. Toutes les notes CC sont strictement bornées dans $[0, 20]$.
  3. Toutes les notes EFM sont strictement bornées dans $[0, 40]$.
  4. L'écart entre la formule théorique et la valeur calculée est inférieur à $10^{-4}$ (zéro erreur d'arrondi).
  5. Aucune ressaisie manuelle n'a été nécessaire.

---

## 🔍 3. Audit de l'Existant dans Solicode LMS (Skills Métier)

L'audit croisé mené via `pkg-apprentissage` et `app-db-savoir` établit les points suivants :

### 3.1. Ce qui existe déjà dans SoliLMS :
- **Notes sources disponibles** :
  - `RealisationUaPrototype` contient les notes $N2_p$ par UA pour chaque apprenant.
  - `RealisationUaProjet` contient la note $N3$ du projet intégrateur.
- **Agrégation de module** :
  - `RealisationModule` relie l'apprenant au module et stocke `note_cache` et `bareme_cache`.

### 3.2. Écarts constatés (Ce qui manque pour calculer l'indicateur) :
1. **Absence du moteur docimologique 65/35 formel** :
   - `RealisationModuleService` somme les compétences brutes mais n'applique pas spécifiquement la pondération réglementaire 65% Prototype + 35% Projet Fil Rouge pour scinder en Note CC (20) et Note EFM (40).
2. **Absence de module d'audit et de validation des bordereaux** :
   - Aucun service ne valide formellement la conformité préalable d'un groupe avant d'autoriser l'export du bordereau officiel pour le secrétariat pédagogique.

---

## 🛠️ 4. Modifications à Apporter à Solicode LMS

### 4.1. Modifications Base de Données (Sprint 1)

1. **Table `realisation_modules`** :
   - Ajouter la colonne `note_cc_officielle` (`decimal(4,2) NULL`) : Note de Contrôle Continu calculée sur 20.
   - Ajouter la colonne `note_efm_officielle` (`decimal(4,2) NULL`) : Note d'Examen de Fin de Module calculée sur 40.
   - Ajouter la colonne `statut_docimologique` (`varchar(30) DEFAULT 'EN_COURS'`) : (`EN_COURS`, `CONFORME`, `ANOMALIE_NOTES_INCOMPLETES`).

2. **Création de la table `bordereaux_officiels`** :
   - `id` : bigint unsigned auto-increment (PK).
   - `module_id` : bigint unsigned (FK $\rightarrow$ `modules.id`).
   - `groupe_id` : bigint unsigned (FK $\rightarrow$ `groupes.id`).
   - `annee_formation_id` : bigint unsigned (FK $\rightarrow$ `annee_formations.id`).
   - `nb_apprenants` : int unsigned.
   - `taux_conformite_calcul` : decimal(5,2).
   - `statut` : varchar(30) (`VALIDE_SANS_ANOMALIE`, `DIVERGENCE_DETECTEE`).
   - `genere_par_user_id` : bigint unsigned (FK $\rightarrow$ `users.id`).
   - `reference` : varchar unique.
   - `created_at`, `updated_at`.

### 4.2. Modifications Applicatives (Services & Algorithmes)

1. **Création du Service Docimologique (`ConversionEvaluationService` dans `PkgTableauBord`)** :
   ```php
   public function genererBordereauOfficiel(int $moduleId, int $groupeId): array
   {
       $apprenants = Apprenant::whereHas('groupes', fn($q) => $q->where('groupes.id', $groupeId))->get();
       $anomalies = [];
       $lignesBordereau = [];

       foreach ($apprenants as $apprenant) {
           // 1. Récupérer toutes les notes N2 (Prototypes) pour ce module
           $prototypes = RealisationUaPrototype::whereHas('realisationUa.microCompetence.competence', fn($q) => 
               $q->where('module_id', $moduleId)
           )->whereHas('realisationTache.realisationProjet', fn($q) => 
               $q->where('apprenant_id', $apprenant->id)
           )->get();

           // 2. Récupérer la note N3 (Projet Fil Rouge)
           $projet = RealisationUaProjet::whereHas('realisationUa.microCompetence.competence', fn($q) => 
               $q->where('module_id', $moduleId)
           )->whereHas('realisationTache.realisationProjet', fn($q) => 
               $q->where('apprenant_id', $apprenant->id)
           )->first();

           if ($prototypes->isEmpty() || is_null($projet) || is_null($projet->note)) {
               $anomalies[] = "Apprenant {$apprenant->nom} {$apprenant->prenom} : évaluations incomplètes.";
               continue;
           }

           // Calcul Note CC (sur 20)
           $notesN2 = $prototypes->map(fn($p) => ($p->note / ($p->bareme ?: 20)) * 20);
           $noteCC = round($notesN2->avg(), 2);

           // Calcul Note N3 (sur 20)
           $noteN3 = round(($projet->note / ($projet->bareme ?: 20)) * 20, 2);

           // Calcul Note EFM (sur 40) selon règle 65/35
           $noteEFM = round(2 * ((0.65 * $noteCC) + (0.35 * $noteN3)), 2);

           $lignesBordereau[] = [
               'apprenant_id' => $apprenant->id,
               'nom_complet' => "{$apprenant->nom} {$apprenant->prenom}",
               'note_cc' => $noteCC,
               'note_efm' => $noteEFM,
           ];
       }

       $estConforme = empty($anomalies);
       
       return [
           'est_conforme' => $estConforme,
           'total_apprenants' => $apprenants->count(),
           'nb_conformes' => count($lignesBordereau),
           'anomalies' => $anomalies,
           'lignes' => $lignesBordereau,
       ];
   }
   ```

---

## 🚦 5. Seuils d'Alerte & Actionnabilité Didactique

| Zone | Valeur $\tau_{\text{conformité}}$ | Diagnostic Docimologique | Action Institutionnelle Immédiate |
| :--- | :---: | :--- | :--- |
| 🟢 **Conformité Absolue** | $100\,\%$ | Zéro divergence arithmétique, zéro ressaisie. Équité et sécurité administrative parfaites. | Autorisation immédiate de l'export officiel des bordereaux signés. |
| 🔴 **Divergence / Incomplet** | $< 100\,\%$ | Évaluations manquantes, barèmes hétérogènes ou divergence d'arrondi détectée. | **Action Bloquante** : Verrouillage de l'exportation officielle. Liste rouge des apprenants à évaluer transmise au formateur. |

---

## 📊 6. Rendu UI Prévu dans les Maquettes

- **Vue Formateur (`dashboard-formateur.html`)** :
  - Section *"Conversion Officielle 65/35"* :
    - Prévisualisation du tableau des notes calculées CC et EFM.
    - Indicateur de statut : *"Prêt pour validation institutionnelle"* (Vert) ou *"2 notes N2 manquantes"* (Rouge).
- **Vue Responsable (`dashboard-responsable.html`)** :
  - KPI Docimologique : *"Conformité des Bordereaux 65/35 : 100%"* (Badge vert émeraude).
  - Bouton d'export sécurisé : *"Télécharger les bordereaux officiels CC & EFM (Excel / PDF signé)"*.
