# Spécification Indicateur 06 : Taux d'Intervention et de Remédiation Ciblée ($T_{\text{intervention}}$)

## 📌 1. Fiche d'Identité de l'Indicateur

- **Code / Symbole** : $T_{\text{intervention}}$
- **Nom complet** : Taux d'intervention et de remédiation formateur post-Peer Learning
- **Axe de Recherche** : Axe 2 — Formateur (Hypothèse H2 : Efficacité du relais entre étayage horizontal par les pairs et intervention experte ciblée du formateur)
- **Acteurs Cibles** :
  - **Apprenant** (prise en charge immédiate en cas de saturation cognitive non résolue par les pairs)
  - **Formateur** (cockpit de détection automatique des blocages à $+10$ minutes pour cibler ses efforts)
  - **Responsable** (suivi de la couverture d'accompagnement et prévention du décrochage silencieux)
- **Unité de mesure** : Pourcentage ($0 \le T_{\text{intervention}} \le 100\,\%$)
- **Périmètre Didactique** : Phase de tutoriel Niveau 1 (*Imiter*). Déclenché automatiquement à l'issue de la session plénière de Peer-Learning animée au tableau dès que 50% de la classe a validé ses tutoriels.

---

## 🧮 2. Modélisation Mathématique & Formules d'Agrégation

### 2.1. Déclencheur Didactique (La Fenêtre des $+10$ Minutes)
1. **Filtrage de l'Assiduité** :
   Le calcul s'applique exclusivement aux apprenants **physiquement présents** le jour de la séance ($N_{\text{présents}}$) via le pointage quotidien d'assiduité, afin d'éliminer tout faux positif lié aux absences.
2. **Fin de séance collective** :
   La séance de Live Coding au tableau se termine à l'horodatage $t_{\text{fin\_live\_coding}}$.
3. **Seuil critique d'autonomie** :
   $$t_{\text{seuil}} = t_{\text{fin\_live\_coding}} + 10\,\text{min}$$
   Tout apprenant présent n'ayant pas soumis sa tâche au statut `TO_APPROVE` à l'échéance de $t_{\text{seuil}}$ bascule automatiquement au statut `BESOIN_REVISION`.
4. **Cohorte en difficulté persistante** :
   $N_{\text{besoin\_révision}(+10\,\text{min})}$ recense le nombre total d'apprenants présents bloqués à $t_{\text{seuil}}$.

### 2.2. Formule Collective (Niveau Séance / Sprint)
Ratio des interventions effectives de remédiation conduites par le formateur sur le nombre d'apprenants identifiés en difficulté :

$$T_{\text{intervention}} = \frac{N_{\text{remédiations\_effectives}}}{N_{\text{besoin\_révision}(+10\,\text{min})}} \times 100$$

- $N_{\text{remédiations\_effectives}}$ : Nombre d'actions d'étayage ou fiches de remédiation tracées par le formateur pour ces apprenants.
- Si $N_{\text{besoin\_révision}(+10\,\text{min})} = 0$ (tous les apprenants ont soumis dans les 10 min), alors $T_{\text{intervention}} = 100\,\%$ (assimilation parfaite sans besoin de remédiation).

### 2.3. Formule Établissement (Niveau Macro — Gouvernance)
Moyenne globale de prise en charge des blocages par filière et par centre :

$$T_{\text{intervention\_macro}} = \frac{\sum_{s=1}^{S} N_{\text{remédiations\_effectives}}(s)}{\sum_{s=1}^{S} N_{\text{besoin\_révision}}(s)} \times 100$$

---

## 🔍 3. Audit de l'Existant dans Solicode LMS (Skills Métier)

L'audit croisé mené via `pkg-realisation-tache`, `app-db-savoir` et les règles existantes établit les points suivants :

### 3.1. Ce qui existe déjà dans SoliLMS :
- **Seuil des 50% de réalisation** :
  - Déjà implémenté dans `TacheAffectationService::lancerLiveCodingSiEligible` (`pkg-realisation-tache`), qui sélectionne un apprenant pour le Live Coding collectif dès que 50% des apprenants ont achevé la tâche.
- **Assiduité** :
  - Module d'assiduité / présences existant dans SoliLMS pour filtrer les apprenants présents.

### 3.2. Écarts constatés (Ce qui manque pour calculer l'indicateur) :
1. **Absence du statut `BESOIN_REVISION` dans `workflow_taches`** :
   - `workflow_taches` possède `REVISION_NECESSAIRE` (rejet par le formateur sur code rendu), mais n'a pas d'état traduisant le blocage automatique à $+10$ min post-Live Coding (code non encore soumis).
2. **Absence d'horodatage de fin de Live Coding collectif** :
   - Le moment exact de clôture de la présentation au tableau n'est pas enregistré pour déclencher le compte à rebours de 10 minutes.
3. **Absence de traçabilité explicite de la remédiation** :
   - Pas de table ni de flag direct pour consigner l'intervention de déblocage effectuée par le formateur.

---

## 🛠️ 4. Modifications à Apporter à Solicode LMS

### 4.1. Modifications Base de Données (Incluses dans le Sprint 1)

1. **Table `workflow_taches`** :
   - Ajouter un nouvel enregistrement d'état :
     - `code` : `BESOIN_REVISION`
     - `titre` : *"Besoin de révision (+10 min)"*
     - `description` : *"L'apprenant n'a pas soumis son livrable 10 minutes après le Live Coding collectif."*
     - `sys_color_reference` : `danger` / `rose`
     - `is_editable_only_by_formateur` : `0`

2. **Table `affectation_projets`** (ou table de session de cours) :
   - Ajouter la colonne `date_fin_live_coding_collectif` (`timestamp NULL`) :
     - Horodatage consigné lorsque le formateur clôture la séance de Live Coding des pairs au tableau.

3. **Création de la table `remediations`** :
   - `id` : bigint unsigned auto-increment (PK).
   - `realisation_tache_id` : bigint unsigned (FK $\rightarrow$ `realisation_taches.id`).
   - `formateur_id` : bigint unsigned (FK $\rightarrow$ `formateurs.id`).
   - `type_remediation` : varchar (50) (`INDIVIDUELLE`, `TRINOME_SOUTIEN`, `ENVIRONNEMENT_DEV`).
   - `diagnostic` : text nullable (cause du blocage).
   - `est_resolu` : boolean default false.
   - `reference` : varchar unique.
   - `created_at`, `updated_at`.

### 4.2. Modifications Applicatives & Jobs Planifiés

1. **Job Asynchrone de Détection Automatique (`VerifierSeuilDixMinutesJob`)** :
   - Déclenché 10 minutes après `date_fin_live_coding_collectif` :
     - Sélectionne les apprenants présents dont la tâche de tutoriel est toujours en `TODO` ou `IN_PROGRESS`.
     - Bascule leur statut vers `BESOIN_REVISION`.
     - Déclenche un événement d'alerte sur le tableau de bord du formateur.

2. **Action Formateur dans `TableauBordService`** :
   - `enregistrerRemediation(int $realisationTacheId, array $data)` :
     - Crée la ligne dans `remediations`.
     - Repasse la tâche en `IN_PROGRESS` (ou `TO_APPROVE` si débloqué).

3. **Service de Calcul dans `PkgTableauBord` (`DashboardCalculTrait`)** :
   - Méthode `calculerTauxIntervention(int $affectationProjetId): array` :
     ```php
     // Nombre d'apprenants ayant basculé en BESOIN_REVISION ou ayant nécessité remédiation
     $bloques = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectationProjetId))
         ->where(function($q) {
             $q->whereHas('etatRealisationTache.workflowTache', fn($w) => $w->where('code', 'BESOIN_REVISION'))
               ->orWhereHas('remediations');
         })->count();

     if ($bloques === 0) {
         return ['taux' => 100.0, 'total_bloques' => 0, 'remediations' => 0];
     }

     $remediations = Remediation::whereHas('realisationTache.realisationProjet', fn($q) => 
         $q->where('affectation_projet_id', $affectationProjetId)
     )->count();

     $taux = min(100.0, round(($remediations / $bloques) * 100, 1));

     return [
         'taux' => $taux,
         'total_bloques' => $bloques,
         'remediations' => $remediations,
     ];
     ```

---

## 🚦 5. Seuils d'Alerte & Actionnabilité Didactique

| Zone | Seuil $T_{\text{intervention}}$ | Diagnostic Didactique | Action Dashboard Immédiate |
| :--- | :---: | :--- | :--- |
| 🟢 **Excellence Réactive** | $\ge 85\,\%$ | Parfaite réactivité du formateur. Aucun stagiaire n'est laissé en situation d'échec silencieux. | Badge vert *"Étayage optimal"*. |
| 🟡 **Vigilance** | $60\,\% \le T < 85\,\%$ | Certains apprenants bloqués n'ont pas encore reçu de remédiation formalisée. | Badge orange. Notification de rappel formateur. |
| 🔴 **Rupture d'Étayage** | $< 60\,\%$ | **Déficit d'accompagnement critique** : des stagiaires bloqués à $+10$ min risquent de décrocher définitivement du sprint. | **Action Formateur Déclenchée** : Pop-up d'alerte prioritaires avec liste des stagiaires et bouton *"Créer atelier de soutien"*. |

---

## 📊 6. Rendu UI Prévu dans les Maquettes

- **Vue Apprenant (`dashboard-apprenant.html`)** :
  - Si basculé en `BESOIN_REVISION` :
    - Boîte de dialogue bienveillante : *"Le Live Coding est terminé depuis 10 minutes. Besoin d'aide sur votre code ? Le formateur a été notifié pour vous accompagner."*
- **Vue Formateur (`dashboard-formateur.html`)** :
  - **Zone d'Alerte Haute Priorité** :
    - Carte KPI : *"Taux de Remédiation : 88%"* | *"1 apprenant restant en attente d'aide"*.
    - Panneau *"Apprenants en attente de déblocage (+10 min)"* listant les noms avec bouton *"Intervenir / Débloquer"*.
- **Vue Responsable (`dashboard-responsable.html`)** :
  - Taux global de réactivité didactique par groupe, permettant de valoriser les formateurs les plus attentifs à la remédiation.
