# 📊 Catalogue KPI Soli‑LMS – Calculs, formules et codes (v1)

> **But** : fournir une liste la plus exhaustive possible des statistiques calculables avec le schéma `kpi_*` (cache agrégé), prête à être implémentée (codes, formules, période, sens de lecture, remarques).

---

## 0) Conventions de calcul

* **Tables** : `kpi_time`, `kpi_scope`, `kpi_target`, `kpi_metric`, `kpi_value`, `kpi_state`, `kpi_state_count`, `kpi_percentiles`, `kpi_plan_gap`, `kpi_tile_cache`.
* **Périodes** (`period_code`) : `Day`, `Week`, `Month` (recommandé : calcul quotidien + agrégations hebdo/mois).
* **Scope** (`scope_type`) : `Global`, `Session`, `Groupe`, `Formateur`, `Apprenant`.
* **Cible** (`target_type`) : `Programme`, `Module`, `Competence`, `Micro_competence`, `Ua`, `Projet`, `Tache`.
* **Codes métriques** (`kpi_metric.code`) : MAJUSCULES\_SNAKE\_CASE, uniques.
* **Agrégations** :

  * `value_number` = valeur agrégée (moyenne/somme/ratio)
  * `numerator_value`, `denominator_value` pour les pourcentages/ratios
  * `kpi_state_count.state_count_value` pour les comptages par état
  * `kpi_percentiles` : `percentile_50`, `percentile_75`, `percentile_90`, `min_observed_value`, `max_observed_value`
* **Sens** : `higher_is_better` = 1 si « plus haut = mieux », sinon 0.
* **Fenêtres glissantes** (fortement recommandé) : 7 jours, 28 jours, 90 jours.

---

## 1) Adoption & activité (plateforme)

| Code                     | Label                          | Unité  | Formule (idée)                                                    | Période        | HiB |
| ------------------------ | ------------------------------ | ------ | ----------------------------------------------------------------- | -------------- | --- |
| USERS\_ACTIFS            | Utilisateurs actifs            | Nombre | # users distincts connectés sur la période                        | Day/Week/Month | 1   |
| APPRENANTS\_ACTIFS       | Apprenants actifs              | Nombre | # apprenants distincts avec activité (login, tâche, UA, feedback) | D/W/M          | 1   |
| FORMATEURS\_ACTIFS       | Formateurs actifs              | Nombre | # formateurs distincts ayant noté/commenté/validé                 | D/W/M          | 1   |
| TAUX\_APPRENANTS\_ACTIFS | % apprenants actifs            | %      | APPRENANTS\_ACTIFS / NB\_APPRENANTS\_SCOPE                        | D/W/M          | 1   |
| CONNEXIONS\_TOTAL        | Nb connexions                  | Nombre | Somme des logins (events)                                         | D/W/M          | 1   |
| CONNEXION\_P50           | Sessions utilisateur (médiane) | Nombre | p50 nb connexions / utilisateur                                   | W/M            | 1   |
| CONNEXION\_P90           | Sessions utilisateur (p90)     | Nombre | p90 nb connexions / utilisateur                                   | W/M            | 1   |
| DUREE\_ACTIVE\_MOY       | Durée active moyenne           | Heures | moyenne temps actif / utilisateur (si log dispo)                  | W/M            | 1   |

> **Notes** : `NB_APPRENANTS_SCOPE` est une dimension « population de référence » à stocker dans `extra_data_json` ou une table de ref.

---

## 2) Progression (chapitres / UAs / compétences)

| Code                 | Label                  | Unité     | Formule (idée)                      | Période | HiB |
| -------------------- | ---------------------- | --------- | ----------------------------------- | ------- | --- |
| PROGRESSION\_MOY     | Progression moyenne    | %         | moyenne % complétion (UA/chapitres) | D/W/M   | 1   |
| PROGRESSION\_P50     | Progression médiane    | %         | p50 progression apprenants          | W/M     | 1   |
| PROGRESSION\_P90     | Progression p90        | %         | p90 progression apprenants          | W/M     | 1   |
| VITESSE\_PROGRESSION | Vitesse de progression | %/semaine | Δ progression / Δ temps             | W       | 1   |
| CHAPITRES\_VALIDES   | Chapitres validés      | Nombre    | somme validations chapitres         | D/W/M   | 1   |
| UAS\_VALIDEES        | UAs validées           | Nombre    | somme UAs statut = Validée          | D/W/M   | 1   |
| COMP\_VALIDEES       | Compétences validées   | Nombre    | somme compétences statut = Validée  | W/M     | 1   |
| PCT\_COMP\_ACQUISES  | % compétences acquises | %         | COMP\_VALIDEES / COMP\_ATTENDUES    | W/M     | 1   |

---

## 3) Couverture du plan (Plan vs Réalisé)

| Code                    | Label                | Unité  | Formule                                            | Période | HiB |
| ----------------------- | -------------------- | ------ | -------------------------------------------------- | ------- | --- |
| UA\_PLAN\_COUNT         | UAs planifiées       | Nombre | `kpi_plan_gap.expected_count` (target = Ua)        | W/M     | 1   |
| UA\_DONE\_COUNT         | UAs réalisées        | Nombre | `kpi_plan_gap.done_count`                          | W/M     | 1   |
| UA\_PLAN\_GAP           | Écart UAs            | Nombre | `done_count - expected_count`                      | W/M     | 1   |
| UA\_COVERAGE\_PCT       | % couverture UA      | %      | `(100 * done / expected)`                          | W/M     | 1   |
| PROJETS\_PLAN\_COUNT    | Projets planifiés    | Nombre | idem cible Projet                                  | W/M     | 1   |
| PROJETS\_DONE\_COUNT    | Projets livrés       | Nombre | idem                                               | W/M     | 1   |
| PROJETS\_COVERAGE\_PCT  | % couverture projets | %      | `(100 * done / expected)`                          | W/M     | 1   |
| COUVERTURE\_MODULE\_PCT | % modules couverts   | %      | UAs réalisées liées au module / UAs prévues module | M       | 1   |

---

## 4) Maîtrise & scores

| Code                  | Label                 | Unité  | Formule                              | Période | HiB |
| --------------------- | --------------------- | ------ | ------------------------------------ | ------- | --- |
| SCORE\_NORM           | Score normalisé moyen | Points | moyenne des scores /100              | D/W/M   | 1   |
| SCORE\_P50            | Score médian          | Points | p50 score apprenants                 | W/M     | 1   |
| SCORE\_P90            | Score p90             | Points | p90 score apprenants                 | W/M     | 1   |
| SCORE\_ECART\_TYPE    | Dispersion des scores | Points | écart-type                           | M       | 0   |
| MAITRISE\_COMP\_INDEX | Indice de maîtrise    | %      | % UAs clés maîtrisées par compétence | M       | 1   |

---

## 5) Feedback & qualité d’accompagnement

| Code                      | Label                           | Unité  | Formule                                                | Période | HiB |
| ------------------------- | ------------------------------- | ------ | ------------------------------------------------------ | ------- | --- |
| FEEDBACK\_COUNT           | Nb feedbacks                    | Nombre | # commentaires/validations                             | D/W/M   | 1   |
| FEEDBACK\_PAR\_APPRENANT  | Feedback / apprenant            | Nombre | FEEDBACK\_COUNT / # apprenants actifs                  | W/M     | 1   |
| DELAI\_FEEDBACK\_MED      | Délai médian feedback           | Heures | p50 (soumission → 1er retour)                          | W/M     | 0   |
| FEEDBACK\_48H\_PCT        | % feedback < 48h                | %      | part des tâches avec délai ≤ 48h                       | W/M     | 1   |
| TAUX\_REVISION\_APRES\_FB | Taux de révision suite feedback | %      | # tâches modifiées après feedback / tâches feedbackées | W/M     | 1   |
| FEEDBACK\_POS\_NEG\_RATIO | Ratio FB positif/négatif        | Ratio  | (labels/tones si disponibles)                          | M       | 1   |

---

## 6) Rythme & délais (flow)

| Code                      | Label                               | Unité | Formule                             | Période | HiB |
| ------------------------- | ----------------------------------- | ----- | ----------------------------------- | ------- | --- |
| DELAI\_ACQUIS\_MICRO\_MED | Délai médian acquisition micro‑comp | Jours | p50 (premier chapitre → UA validée) | W/M     | 0   |
| CYCLE\_TIME\_TACHE\_MED   | Cycle time tâche médian             | Jours | p50 (création → Done)               | W/M     | 0   |
| LEAD\_TIME\_TACHE\_MED    | Lead time tâche médian              | Jours | p50 (assignation → Done)            | W/M     | 0   |
| TEMPS\_EN\_ETAT\_MOY      | Temps moyen par état                | Jours | moyenne durée par `kpi_state`       | M       | 0   |
| TAUX\_TACHES\_A\_L\_HEURE | % tâches à l’heure                  | %     | # Done avant deadline / # Done      | W/M     | 1   |

---

## 7) Projets & tâches – Comptages par état

(Reposer massivement sur `kpi_state_count`, domaine `Projet` et `Tache`.)

| Code                 | Label              | Unité      | Formule                                             | Période | HiB |
| -------------------- | ------------------ | ---------- | --------------------------------------------------- | ------- | --- |
| TACHES\_A\_FAIRE     | Tâches à faire     | Nombre     | count état = A\_faire                               | D/W/M   | 0   |
| TACHES\_EN\_COURS    | Tâches en cours    | Nombre     | count état = En\_cours                              | D/W/M   | 0   |
| TACHES\_A\_VALIDER   | Tâches à valider   | Nombre     | count état = A\_valider (si état dispo)             | D/W/M   | 0   |
| TACHES\_TERMINEES    | Tâches terminées   | Nombre     | count état = Terminee                               | D/W/M   | 1   |
| TACHES\_REJETEES     | Tâches rejetées    | Nombre     | count état = Rejetee (si dispo)                     | D/W/M   | 0   |
| TACHE\_THROUGHPUT    | Débit de tâches    | Nombre/sem | # Terminee / période                                | W       | 1   |
| WIP\_TACHES          | WIP (en cours)     | Nombre     | # En\_cours                                         | D/W/M   | 0   |
| BACKLOG\_TACHES      | Backlog            | Nombre     | # A\_faire                                          | D/W/M   | 0   |
| TAUX\_RETARD\_TACHES | % tâches en retard | %          | # (En\_cours/A\_valider) dépassant deadline / total | W/M     | 0   |

---

## 8) Inactivité & risques

| Code                     | Label                     | Unité  | Formule                                             | Période | HiB |
| ------------------------ | ------------------------- | ------ | --------------------------------------------------- | ------- | --- |
| INACTIVITE\_7J           | % micro‑comp inactives 7j | %      | part UAs sans activité 7j                           | D/W     | 0   |
| APPRENANTS\_INACTIFS\_7J | # apprenants inactifs 7j  | Nombre | # sans login/activité 7 jours                       | D/W     | 0   |
| UA\_SANS\_FEEDBACK\_7J   | UAs sans feedback 7j      | Nombre | # UAs soumises sans retour                          | W       | 0   |
| RISK\_RETARD\_PROJET     | Risque retard projet      | %      | tâches critiques en retard / total tâches critiques | W       | 0   |
| RISK\_DESALIGNEMENT      | Risque désalignement      | %      | UAs non couvertes vs plan pour période              | W/M     | 0   |

---

## 9) Équité & hétérogénéité

| Code                    | Label                          | Unité  | Formule                                                     | Période | HiB |
| ----------------------- | ------------------------------ | ------ | ----------------------------------------------------------- | ------- | --- |
| DISPERSION\_PROGRESSION | Dispersion progression         | Points | p75 − p25 (IQR)                                             | M       | 0   |
| CV\_SCORE               | Coefficient de variation score | Ratio  | écart‑type / moyenne                                        | M       | 0   |
| GAPS\_GROUPE\_MIN\_MAX  | Écart min–max entre groupes    | Points | max(score moyen groupe) − min(...)                          | M       | 0   |
| TAUX\_EQUIVALENCE\_EVAL | Taux cohérence évaluateurs     | %      | % écarts < seuil entre 2 évaluateurs (si double correction) | M       | 1   |

---

## 10) Live coding & validations par niveau

| Code                 | Label                     | Unité  | Formule                                | Période | HiB |
| -------------------- | ------------------------- | ------ | -------------------------------------- | ------- | --- |
| N1\_VALIDATIONS      | Validations niveau 1      | Nombre | # validations /4 (chapitres/TP guidés) | W/M     | 1   |
| N2\_VALIDATIONS      | Validations niveau 2      | Nombre | # live coding / explications validées  | W/M     | 1   |
| N3\_VALIDATIONS      | Validations niveau 3      | Nombre | # soutenances projets validées         | W/M     | 1   |
| TAUX\_REUSSITE\_LIVE | Taux réussite live coding | %      | N2\_VALIDATIONS / N2\_TENTATIVES       | W/M     | 1   |

---

## 11) Pédagogie & effet du feedback

| Code                   | Label                    | Unité  | Formule                                     | Période | HiB |
| ---------------------- | ------------------------ | ------ | ------------------------------------------- | ------- | --- |
| IMPACT\_FB\_SUR\_SCORE | Gain après feedback      | Points | Δ score (avant→après) moyen                 | M       | 1   |
| IMPACT\_FB\_SUR\_DELAI | Réduction délai après FB | Jours  | Δ cycle\_time avant/après adoption feedback | M       | 1   |
| REMEDIATIONS\_LANCEES  | Nb remédiations          | Nombre | # UAs re‑ouvertes avec plan d’action        | M       | 1   |

---

## 12) Productivité formateur

| Code                       | Label                      | Unité  | Formule                                   | Période | HiB |
| -------------------------- | -------------------------- | ------ | ----------------------------------------- | ------- | --- |
| FB\_PAR\_FORMATEUR         | Feedbacks / formateur      | Nombre | FEEDBACK\_COUNT / # formateurs actifs     | W/M     | 1   |
| ELEVE\_PAR\_FORMATEUR      | Apprenants / formateur     | Nombre | # apprenants actifs / # formateurs actifs | W/M     | 0\* |
| CHARGE\_FB\_MED            | Charge médiane FB          | Nombre | p50 feedbacks / formateur                 | M       | 1   |
| DELAI\_VALIDATION\_UA\_MED | Délai médian validation UA | Heures | p50 (soumission UA → validation)          | W/M     | 0   |

\* *Interprétation : viser une plage cible plutôt qu’un extrême.*

---

## 13) Qualité de livrables & savoir‑être (projets)

| Code                           | Label                    | Unité | Formule                                | Période | HiB |
| ------------------------------ | ------------------------ | ----- | -------------------------------------- | ------- | --- |
| QUALITE\_CODE\_MOY             | Qualité du code moyenne  | /2    | moyenne critère « code » projets N3    | M       | 1   |
| ORGANISATION\_TRAVAIL\_MOY     | Organisation du travail  | /2    | moyenne critère méthode                | M       | 1   |
| COMMUNICATION\_ORALE\_MOY      | Communication orale      | /2    | moyenne critère présentation           | M       | 1   |
| PERTINENCE\_FONCTIONNELLE\_MOY | Pertinence fonctionnelle | /3    | moyenne critère adéquation brief       | M       | 1   |
| INTEGRATION\_UA\_MOY           | Intégration des UAs      | /6    | moyenne critère « UAs bien intégrées » | M       | 1   |
| BONUS\_GROUPE\_MOY             | Bonus groupe             | /1    | moyen                                  | M       | 1   |

> Source : grilles N3 (agrégation par critère → `kpi_value` avec `extra_data_json` détaillant la composante).

---

## 14) Présences & corrélations (si module activé)

| Code                   | Label                           | Unité  | Formule                       | Période | HiB |
| ---------------------- | ------------------------------- | ------ | ----------------------------- | ------- | --- |
| ABSENCES\_COUNT        | Nb absences                     | Nombre | total absences                | W/M     | 0   |
| RETARDS\_COUNT         | Nb retards                      | Nombre | total retards                 | W/M     | 0   |
| CORR\_ABS\_SCORE       | Corrélation absence‑score       | Coef   | corr(# absences, score)       | M       | —   |
| CORR\_ABS\_PROGRESSION | Corrélation absence‑progression | Coef   | corr(# absences, progression) | M       | —   |

---

## 15) Tendances & prévisions (simples)

| Code                       | Label                    | Unité | Formule                                     | Période | HiB |
| -------------------------- | ------------------------ | ----- | ------------------------------------------- | ------- | --- |
| TENDANCE\_PROGRESSION\_28J | Tendance progression 28j | %     | pente régression linéaire (dernier 28j)     | D       | 1   |
| ETA\_ATTEINTE\_OBJECTIF    | ETA % objectif           | Jours | extrapolation linéaire pour atteindre cible | D       | 1   |
| PREVISION\_COUVERTURE\_UA  | Prévision couverture UA  | %     | projection à fin période                    | W       | 1   |

---

## 16) Jeux d’agrégations standard (cubes)

**By Scope × Target × Period** pour chaque grande famille :

* *Adoption* : {USERS\_ACTIFS, APPRENANTS\_ACTIFS, TAUX\_APPRENANTS\_ACTIFS, CONNEXIONS\_TOTAL}
* *Progression* : {PROGRESSION\_MOY, PROGRESSION\_P50, PROGRESSION\_P90, VITESSE\_PROGRESSION}
* *Couverture* : {UA\_COVERAGE\_PCT, UA\_PLAN\_GAP, PROJETS\_COVERAGE\_PCT}
* *Scores* : {SCORE\_NORM, SCORE\_P50, SCORE\_ECART\_TYPE}
* *Feedback* : {FEEDBACK\_COUNT, DELAI\_FEEDBACK\_MED, FEEDBACK\_48H\_PCT}
* *Flow* : {CYCLE\_TIME\_TACHE\_MED, LEAD\_TIME\_TACHE\_MED, TAUX\_TACHES\_A\_L\_HEURE}
* *États* : `kpi_state_count` par domaine (Tache/Projet/Ua/Competence)
* *Distribution* : `kpi_percentiles` pour progression, score, délais

---

## 17) Exemples d’implémentation (squelettes SQL)

> Les squelettes ci‑dessous montrent la logique d’insertion dans le cache. À adapter à vos tables sources.

### 17.1 `kpi_value` (valeur agrégée)

```sql
INSERT INTO kpi_value (date_id, period_code, scope_id, target_id, metric_id,
                       value_number, numerator_value, denominator_value, sample_size)
VALUES (?, 'Week', ?, ?, (SELECT id FROM kpi_metric WHERE code = 'PROGRESSION_MOY'),
        ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE
  value_number = VALUES(value_number),
  numerator_value = VALUES(numerator_value),
  denominator_value = VALUES(denominator_value),
  sample_size = VALUES(sample_size),
  computed_at = CURRENT_TIMESTAMP;
```

### 17.2 `kpi_state_count` (comptage par état)

```sql
INSERT INTO kpi_state_count (date_id, period_code, scope_id, target_id, state_id, state_count_value)
SELECT ?, 'Week', ?, ?, s.id, ?
FROM kpi_state s
WHERE s.domain = 'Tache' AND s.state_code = 'En_cours'
ON DUPLICATE KEY UPDATE
  state_count_value = VALUES(state_count_value);
```

### 17.3 `kpi_percentiles` (distribution)

```sql
INSERT INTO kpi_percentiles (date_id, period_code, scope_id, target_id, metric_id,
                             percentile_50, percentile_75, percentile_90,
                             min_observed_value, max_observed_value, sample_size)
VALUES (?, 'Month', ?, ?, (SELECT id FROM kpi_metric WHERE code = 'CYCLE_TIME_TACHE_MED'),
        ?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE
  percentile_50 = VALUES(percentile_50),
  percentile_75 = VALUES(percentile_75),
  percentile_90 = VALUES(percentile_90),
  min_observed_value = VALUES(min_observed_value),
  max_observed_value = VALUES(max_observed_value),
  sample_size = VALUES(sample_size);
```

### 17.4 `kpi_plan_gap` (plan vs réalisé)

```sql
INSERT INTO kpi_plan_gap (date_id, period_code, scope_id, target_id, expected_count, done_count)
VALUES (?, 'Week', ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE
  expected_count = VALUES(expected_count),
  done_count = VALUES(done_count);
```

---

## 18) Tuiles de tableau de bord (cache `kpi_tile_cache`)

Par **scope × target × period** (typiquement `Week`), calculer et stocker :

* `progression_moyenne` ← PROGRESSION\_MOY
* `score_normalise_moyen` ← SCORE\_NORM
* `pourcentage_competences_acquises` ← PCT\_COMP\_ACQUISES
* `pourcentage_ua_couvertes` ← UA\_COVERAGE\_PCT
* `pourcentage_inactives_7j` ← INACTIVITE\_7J
* `delai_median_jours` ← CYCLE\_TIME\_TACHE\_MED
* `delai_median_feedback_heures` ← DELAI\_FEEDBACK\_MED
* `commentaires_7j` ← FEEDBACK\_COUNT (fenêtre 7 jours)

---

## 19) Contrôles qualité du cache

* **Fraîcheur** : champs `computed_at` / `updated_at` récents
* **Cohérence** : `0 ≤ value_number ≤ 100` pour les % ; `denominator_value > 0` si ratio
* **Échantillon** : `sample_size` != 0 pour percentiles/médianes
* **Manquants** : backfill hebdo/mois à partir du quotidien

---

## 20) Roadmap d’implémentation (suggestion rapide)

1. **Créer dictionnaire métriques** dans `kpi_metric` avec les codes ci‑dessus (unit\_code, aggregation\_type, higher\_is\_better, category\_code).
2. **Batch quotidien** : alimenter `kpi_value` (adoption, progression, scores) + `kpi_state_count` (états) + `kpi_percentiles` (délais/scores).
3. **Batch hebdo/mois** : `kpi_plan_gap` + consolidations.
4. **Remplir `kpi_tile_cache`** pour les vues critiques (home dashboards).
5. **Alerting** : requêtes seuils (ex. `FEEDBACK_48H_PCT < 80%`, `INACTIVITE_7J > 20%`).

---

### Fin — v1

Liste prête pour itérations : ajout/suppression de KPI, affinement des formules selon la granularité réelle des données sources et des besoins métier.
