# Spécification Indicateur 07 : Authenticité Évaluative & Corrélation Intégrité IA ($r$)

## 📌 1. Fiche d'Identité de l'Indicateur

- **Code / Symbole** : $r$ (Coefficient de corrélation linéaire de Pearson)
- **Nom complet** : Écart d'authenticité évaluative et corrélation d'intégrité face à l'IA
- **Axe de Recherche** : Axe 3 — Institution (Hypothèse H3 : Concordance docimologique entre maîtrise individuelle sous surveillance et performance sur projet assisté par IA)
- **Acteurs Cibles** :
  - **Responsable de formation** (garant de l'intégrité certificative et détection des illusions de compétence dues à l'IA)
  - **Formateur** (ciblage des soutenances de projet pour les apprenants présentant une divergence anormale)
  - **Apprenant** (responsabilisation sur l'usage professionnel et maîtrisé des assistants génératifs)
- **Unité de mesure** : Coefficient de Pearson sans dimension ($-1 \le r \le +1$) et écart absolu de note $\Delta_{\text{IA}} \in [-20, +20]$
- **Périmètre Didactique** : Confrontation appariée entre la note de Live Coding individuel sur prototype ($N2$ — 65% de la certification, sans aide extérieure) et la note de projet Fil Rouge ($N3$ — 35%, usage libre de l'IA encouragé).

---

## 🧮 2. Modélisation Mathématique & Formules d'Agrégation

### 2.1. Normalisation Préalable des Notes
Pour chaque apprenant $i$, les notes brutes de prototype et de projet sont converties sur un barème standardisé sur 20 :

$$x_i = \frac{\text{note}(N2_i)}{\text{bareme}(N2_i)} \times 20 \quad \in [0, 20]$$

$$y_i = \frac{\text{note}(N3_i)}{\text{bareme}(N3_i)} \times 20 \quad \in [0, 20]$$

### 2.2. Formule Collective (Niveau Promotion / Module — Pearson $r$)
Coefficient de corrélation linéaire sur l'échantillon des $N$ apprenants ayant validé les deux épreuves :

$$r = \frac{\sum_{i=1}^{N} (x_i - \bar{x})(y_i - \bar{y})}{\sqrt{\sum_{i=1}^{N} (x_i - \bar{x})^2 \sum_{i=1}^{N} (y_i - \bar{y})^2}}$$

- $\bar{x} = \frac{1}{N} \sum x_i$ : Moyenne arithmétique de la cohorte au Live Coding Prototype ($N2$).
- $\bar{y} = \frac{1}{N} \sum y_i$ : Moyenne arithmétique de la cohorte au Projet Fil Rouge ($N3$).

### 2.3. Formule Individuelle (Écart d'Authenticité $\Delta_{\text{IA}}$)
Pour chaque stagiaire $i$, l'écart de performance mesure la surperformance ou sous-performance sur le projet :

$$\Delta_{\text{IA}}(i) = y_i - x_i = \text{Note}(N3_i) - \text{Note}(N2_i)$$

- $\Delta_{\text{IA}} \approx 0$ ($-2 \le \Delta \le +3$) : **Concordance parfaite**.
- $\Delta_{\text{IA}} < -3$ : **Projet inachevé** (contrainte de temps ou gestion de projet défaillante).
- $\Delta_{\text{IA}} > +5$ : **Suspicion de complaisance IA** (délégation aveugle sans maîtrise architecturale).

---

## 🔍 3. Audit de l'Existant dans Solicode LMS (Skills Métier)

L'audit croisé mené via `pkg-apprentissage`, `pkg-creation-projet` et `app-db-savoir` établit les points suivants :

### 3.1. Ce qui existe déjà dans SoliLMS :
- **Entités de notation distinctes dans `PkgApprentissage`** :
  - `RealisationUaPrototype` stocke la note individuelle de Live Coding ($N2$) via les colonnes `note` et `bareme`.
  - `RealisationUaProjet` stocke la note de projet final ($N3$) via les colonnes `note` et `bareme`.
- **Lien vers l'Apprenant** :
  - Les deux modèles sont reliés à l'apprenant via `realisationTache.realisationProjet.apprenant_id` et à l'Unité d'Apprentissage (`realisation_ua_id`).

### 3.2. Écarts constatés (Ce qui manque pour calculer l'indicateur) :
1. **Absence de fonction statistique de Pearson intégrée** :
   - Laravel/Eloquent ne fournit pas de méthode native de calcul de covariance et de corrélation de Pearson. Cette logique doit être implémentée dans un Service dédié (`TableauBordService`).
2. **Absence de drapeau d'alerte individuelle** :
   - Il n'existe pas d'attribut ou de filtre permettant d'isoler en un clic les apprenants pour lesquels $\Delta_{\text{IA}} > 5$ afin de préparer les jurys de soutenance.

---

## 🛠️ 4. Modifications à Apporter à Solicode LMS

### 4.1. Modifications Base de Données (Sprint 1)
- Aucune création de table lourde n'est requise car `realisation_ua_prototypes` et `realisation_ua_projets` contiennent déjà les notes et barèmes.
- Ajout d'un index combiné sur `realisation_ua_prototypes (realisation_ua_id, note)` et `realisation_ua_projets (realisation_ua_id, note)` pour accélérer les requêtes d'appariement.

### 4.2. Modifications Applicatives (Services & Algorithmes)

1. **Service de Calcul Statistique dans `PkgTableauBord` (`DashboardCalculTrait`)** :
   ```php
   public function calculerCorrelationPearsonIA(int $affectationProjetId): array
   {
       // Récupérer les paires de notes (N2, N3) par apprenant
       $donneesAppariees = DB::table('realisation_ua_prototypes as rup')
           ->join('realisation_taches as rt_proto', 'rt_proto.id', '=', 'rup.realisation_tache_id')
           ->join('realisation_projets as rp', 'rp.id', '=', 'rt_proto.realisation_tache_id')
           ->join('realisation_ua_projets as ru_proj', 'ru_proj.realisation_ua_id', '=', 'rup.realisation_ua_id')
           ->where('rp.affectation_projet_id', $affectationProjetId)
           ->whereNotNull('rup.note')
           ->whereNotNull('ru_proj.note')
           ->select([
               'rp.apprenant_id',
               DB::raw('(rup.note / rup.bareme) * 20 as note_n2'),
               DB::raw('(ru_proj.note / ru_proj.bareme) * 20 as note_n3'),
           ])->get();

       $n = $donneesAppariees->count();
       if ($n < 3) {
           return ['r' => null, 'statut' => 'DONNEES_INSUFFISANTES', 'paires' => []];
       }

       $x = $donneesAppariees->pluck('note_n2')->toArray();
       $y = $donneesAppariees->pluck('note_n3')->toArray();

       $meanX = array_sum($x) / $n;
       $meanY = array_sum($y) / $n;

       $num = 0.0;
       $denX = 0.0;
       $denY = 0.0;

       for ($i = 0; $i < $n; $i++) {
           $dx = $x[$i] - $meanX;
           $dy = $y[$i] - $meanY;
           $num += ($dx * $dy);
           $denX += ($dx * $dx);
           $denY += ($dy * $dy);
       }

       $den = sqrt($denX * $denY);
       $r = ($den > 0) ? round($num / $den, 3) : 0.0;

       // Détection des anomalies individuelles (surperformance IA suspecte)
       $anomalies = $donneesAppariees->filter(fn($p) => ($p->note_n3 - $p->note_n2) > 5.0);

       return [
           'r' => $r,
           'effectif' => $n,
           'moyenne_n2' => round($meanX, 2),
           'moyenne_n3' => round($meanY, 2),
           'nb_anomalies_ia' => $anomalies->count(),
           'anomalies' => $anomalies->values()->all(),
       ];
   }
   ```

---

## 🚦 5. Seuils d'Alerte & Actionnabilité Didactique

| Zone | Valeur de Pearson $r$ | Diagnostic Didactique | Action Institutionnelle / Formateur |
| :--- | :---: | :--- | :--- |
| 🟢 **Alignement Robuste** | $r \ge 0{,}70$ | **Authenticité avérée** : les compétences fondamentales validées en direct sont efficacement réinvesties dans le projet outillé par l'IA. | Validation sans réserve du processus certificatif. |
| 🟡 **Dispersion Modérée** | $0{,}45 \le r < 0{,}70$ | Corrélation acceptable mais hétérogénéité d'usage de l'IA ou retards de finalisation sur le projet fil rouge. | Vérification des apprenants à fort écart $\Delta_{\text{IA}}$. |
| 🔴 **Divergence / Déconnexion** | $r < 0{,}45$ ou négatif | **Illusion d'intégrité** : l'évaluation globale de projet ne reflète pas l'autonomie réelle des stagiaires. | **Action Responsable** : Audit docimologique immédiat. Vérification des questions de soutenance individuelle. |

---

## 📊 6. Rendu UI Prévu dans les Maquettes

- **Vue Formateur (`dashboard-formateur.html`)** :
  - Tableau nominatif des soutenances avec colonne *"Indice IA"* :
    - Badge vert si $\Delta_{\text{IA}} \le +3$.
    - Badge rouge avec icône attention si $\Delta_{\text{IA}} > +5$ (*"Écart Prototype/Projet élevé (+6.5 pts) — Vérifier maîtrise du code en soutenance"*).
- **Vue Responsable (`dashboard-responsable.html`)** :
  - Carte KPI : *"Indice d'Intégrité IA : r = 0.78"* (Badge vert : Alignement robuste).
  - Graphique Scatter Plot (Nuage de points) :
    - Axe X : Note Live Coding Prototype ($N2$).
    - Axe Y : Note Projet Fil Rouge ($N3$).
    - Droite de régression idéale ($y = x$) et zone rouge d'anomalies ($y \gg x$).
