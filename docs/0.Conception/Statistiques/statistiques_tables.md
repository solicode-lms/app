Top, voici **où stocker chaque statistique** (table/colonnes exactes) avec ton schéma `kpi_*`.
Format des lignes ci-dessous :

* **Table → Clés** `(date_id, period_code, scope_id, target_id, …)` → **Colonnes valeur** → *Remarques (metric/state)*

> Rappels
> • `metric_id = (SELECT id FROM kpi_metric WHERE code = '…')`
> • Pour les comptages par état, on utilise `kpi_state_count` avec `state_id = (SELECT id FROM kpi_state WHERE domain='…' AND state_code='…')`
> • Périodes recommandées : `Day`, `Week`, `Month`

---

# 1) Adoption & activité (plateforme)

* **USERS\_ACTIFS** → `kpi_value` `(… , metric_id=USERS_ACTIFS)` → **value\_number** → *Nombre d’utilisateurs distincts*
* **APPRENANTS\_ACTIFS** → `kpi_value` `(… , metric_id=APPRENANTS_ACTIFS)` → **value\_number**
* **FORMATEURS\_ACTIFS** → `kpi_value` `(… , metric_id=FORMATEURS_ACTIFS)` → **value\_number**
* **TAUX\_APPRENANTS\_ACTIFS** → `kpi_value` `(… , metric_id=TAUX_APPRENANTS_ACTIFS)` → **value\_number**, **numerator\_value**, **denominator\_value** → *% = num/den*
* **CONNEXIONS\_TOTAL** → `kpi_value` `(… , metric_id=CONNEXIONS_TOTAL)` → **value\_number**
* **CONNEXION\_P50 / CONNEXION\_P90** → `kpi_percentiles` `(… , metric_id=CONNEXION_SESSIONS)` → **percentile\_50 / percentile\_90**, **sample\_size** → *1 seul metric “CONNEXION\_SESSIONS”, on lit P50/P90*
* **DUREE\_ACTIVE\_MOY** → `kpi_value` `(… , metric_id=DUREE_ACTIVE_MOY)` → **value\_number** → *heures moyennes actives / user*

---

# 2) Progression (chapitres / UAs / compétences)

* **PROGRESSION\_MOY** → `kpi_value` `(… , metric_id=PROGRESSION_MOY)` → **value\_number**, *(optionnel: numerator/denominator)*
* **PROGRESSION\_P50 / PROGRESSION\_P90** → `kpi_percentiles` `(… , metric_id=PROGRESSION)` → **percentile\_50 / percentile\_90**, **sample\_size**
* **VITESSE\_PROGRESSION** → `kpi_value` `(… , metric_id=VITESSE_PROGRESSION)` → **value\_number** → *% par semaine*
* **CHAPITRES\_VALIDES** → `kpi_value` `(… , metric_id=CHAPITRES_VALIDES)` → **value\_number**
* **UAS\_VALIDEES** → `kpi_value` `(… , metric_id=UAS_VALIDEES)` → **value\_number**
* **COMP\_VALIDEES** → `kpi_value` `(… , metric_id=COMP_VALIDEES)` → **value\_number**
* **PCT\_COMP\_ACQUISES** → `kpi_value` `(… , metric_id=PCT_COMP_ACQUISES)` → **value\_number**, **numerator\_value**, **denominator\_value**

---

# 3) Couverture du plan (Plan vs Réalisé)

* **UA\_PLAN\_COUNT** → `kpi_plan_gap` `(… , target=Ua)` → **expected\_count**
* **UA\_DONE\_COUNT** → `kpi_plan_gap` → **done\_count**
* **UA\_PLAN\_GAP** → `kpi_plan_gap` → **gap\_value**
* **UA\_COVERAGE\_PCT** → `kpi_plan_gap` → **coverage\_percentage**
  *(optionnel miroir)* `kpi_value` `(… , metric_id=UA_COVERAGE_PCT)` → **value\_number**
* **PROJETS\_PLAN\_COUNT / PROJETS\_DONE\_COUNT / PROJETS\_COVERAGE\_PCT** → idem ci-dessus avec `target_type='Projet'`
* **COUVERTURE\_MODULE\_PCT** → `kpi_value` `(… , metric_id=COUVERTURE_MODULE_PCT)` → **value\_number**, **numerator\_value**, **denominator\_value**
  *ou calcul indirect depuis `kpi_plan_gap` agrégé au niveau Module*

---

# 4) Maîtrise & scores

* **SCORE\_NORM** → `kpi_value` `(… , metric_id=SCORE_NORM)` → **value\_number**
* **SCORE\_P50 / SCORE\_P90** → `kpi_percentiles` `(… , metric_id=SCORE)` → **percentile\_50 / percentile\_90**
* **SCORE\_ECART\_TYPE** → `kpi_value` `(… , metric_id=SCORE_ECART_TYPE)` → **value\_number**
* **MAITRISE\_COMP\_INDEX** → `kpi_value` `(… , metric_id=MAITRISE_COMP_INDEX)` → **value\_number**

---

# 5) Feedback & qualité d’accompagnement

* **FEEDBACK\_COUNT** → `kpi_value` `(… , metric_id=FEEDBACK_COUNT)` → **value\_number**
* **FEEDBACK\_PAR\_APPRENANT** → `kpi_value` `(… , metric_id=FEEDBACK_PAR_APPRENANT)` → **value\_number** *(ou % via num/den)*
* **DELAI\_FEEDBACK\_MED** → `kpi_percentiles` `(… , metric_id=DELAI_FEEDBACK)` → **percentile\_50**, **min/max**, **sample\_size**
* **FEEDBACK\_48H\_PCT** → `kpi_value` `(… , metric_id=FEEDBACK_48H_PCT)` → **value\_number**, **numerator\_value**, **denominator\_value**
* **TAUX\_REVISION\_APRES\_FB** → `kpi_value` `(… , metric_id=TAUX_REVISION_APRES_FB)` → **value\_number**, **numerator\_value**, **denominator\_value**
* **FEEDBACK\_POS\_NEG\_RATIO** → `kpi_value` `(… , metric_id=FEEDBACK_POS_NEG_RATIO)` → **value\_number**

---

# 6) Rythme & délais (flow)

* **DELAI\_ACQUIS\_MICRO\_MED** → `kpi_percentiles` `(… , metric_id=DELAI_ACQUIS_MICRO)` → **percentile\_50**, **sample\_size**
* **CYCLE\_TIME\_TACHE\_MED** → `kpi_percentiles` `(… , metric_id=CYCLE_TIME_TACHE)` → **percentile\_50**, **p75/p90**, **sample\_size**
* **LEAD\_TIME\_TACHE\_MED** → `kpi_percentiles` `(… , metric_id=LEAD_TIME_TACHE)` → **percentile\_50**, **p75/p90**
* **TEMPS\_EN\_ETAT\_MOY** → `kpi_value` `(… , metric_id=TEMPS_EN_ETAT_MOY)` → **value\_number**, **extra\_data\_json** `{ "domain":"Tache|Projet|Ua|Competence", "state_code":"En_cours" }`
* **TAUX\_TACHES\_A\_L\_HEURE** → `kpi_value` `(… , metric_id=TAUX_TACHES_A_L_HEURE)` → **value\_number**, **numerator\_value**, **denominator\_value**

---

# 7) Projets & tâches – Comptages par état (snapshot)

*(par domaine `Projet` / `Tache` via `kpi_state`)*

* **TACHES\_A\_FAIRE** → `kpi_state_count` `(… , state_id(domain='Tache', state_code='A_faire'))` → **state\_count\_value**
* **TACHES\_EN\_COURS** → `kpi_state_count` `(… ,'Tache','En_cours')` → **state\_count\_value**
* **TACHES\_A\_VALIDER** → `kpi_state_count` `(… ,'Tache','A_valider')` → **state\_count\_value**
* **TACHES\_TERMINEES** → `kpi_state_count` `(… ,'Tache','Terminee')` → **state\_count\_value**
* **TACHES\_REJETEES** → `kpi_state_count` `(… ,'Tache','Rejetee')` → **state\_count\_value**
* **TACHE\_THROUGHPUT** → `kpi_value` `(… , metric_id=TACHE_THROUGHPUT)` → **value\_number** *(# terminées / période)*
* **WIP\_TACHES** → `kpi_state_count` `(… ,'Tache','En_cours')` → **state\_count\_value**
* **BACKLOG\_TACHES** → `kpi_state_count` `(… ,'Tache','A_faire')` → **state\_count\_value**
* **TAUX\_RETARD\_TACHES** → `kpi_value` `(… , metric_id=TAUX_RETARD_TACHES)` → **value\_number**, **numerator\_value**, **denominator\_value**

---

# 8) Inactivité & risques

* **INACTIVITE\_7J** → `kpi_value` `(… , metric_id=INACTIVITE_7J)` → **value\_number**, **numerator\_value**, **denominator\_value**
* **APPRENANTS\_INACTIFS\_7J** → `kpi_value` `(… , metric_id=APPRENANTS_INACTIFS_7J)` → **value\_number**
* **UA\_SANS\_FEEDBACK\_7J** → `kpi_value` `(… , metric_id=UA_SANS_FEEDBACK_7J)` → **value\_number**
* **RISK\_RETARD\_PROJET** → `kpi_value` `(… , metric_id=RISK_RETARD_PROJET)` → **value\_number**
* **RISK\_DESALIGNEMENT** → `kpi_value` `(… , metric_id=RISK_DESALIGNEMENT)` → **value\_number** *(ou 100 - UA\_COVERAGE\_PCT)*

---

# 9) Équité & hétérogénéité

* **DISPERSION\_PROGRESSION** *(IQR p75−p25)* → `kpi_value` `(… , metric_id=DISPERSION_PROGRESSION)` → **value\_number**
  *(calculé à partir des distributions, ou stocké direct)*
* **CV\_SCORE** *(écart-type/moyenne)* → `kpi_value` `(… , metric_id=CV_SCORE)` → **value\_number**
* **GAPS\_GROUPE\_MIN\_MAX** → `kpi_value` `(… , metric_id=GAPS_GROUPE_MIN_MAX)` → **value\_number**
* **TAUX\_EQUIVALENCE\_EVAL** → `kpi_value` `(… , metric_id=TAUX_EQUIVALENCE_EVAL)` → **value\_number**, **numerator\_value**, **denominator\_value**

---

# 10) Live coding & validations par niveau

* **N1\_VALIDATIONS / N2\_VALIDATIONS / N3\_VALIDATIONS** → `kpi_value` `(… , metric_id=N{1|2|3}_VALIDATIONS)` → **value\_number**
* **TAUX\_REUSSITE\_LIVE** → `kpi_value` `(… , metric_id=TAUX_REUSSITE_LIVE)` → **value\_number**, **numerator\_value**, **denominator\_value**

---

# 11) Pédagogie & effet du feedback

* **IMPACT\_FB\_SUR\_SCORE** → `kpi_value` `(… , metric_id=IMPACT_FB_SUR_SCORE)` → **value\_number**
* **IMPACT\_FB\_SUR\_DELAI** → `kpi_value` `(… , metric_id=IMPACT_FB_SUR_DELAI)` → **value\_number**
* **REMEDIATIONS\_LANCEES** → `kpi_value` `(… , metric_id=REMEDIATIONS_LANCEES)` → **value\_number**

---

# 12) Productivité formateur

* **FB\_PAR\_FORMATEUR** → `kpi_value` `(… , metric_id=FB_PAR_FORMATEUR)` → **value\_number**
* **ELEVE\_PAR\_FORMATEUR** → `kpi_value` `(… , metric_id=ELEVE_PAR_FORMATEUR)` → **value\_number**
* **CHARGE\_FB\_MED** → `kpi_percentiles` `(… , metric_id=CHARGE_FB)` → **percentile\_50**
* **DELAI\_VALIDATION\_UA\_MED** → `kpi_percentiles` `(… , metric_id=DELAI_VALIDATION_UA)` → **percentile\_50**

---

# 13) Qualité livrables & savoir-être (projets)

* **QUALITE\_CODE\_MOY / ORGANISATION\_TRAVAIL\_MOY / COMMUNICATION\_ORALE\_MOY / PERTINENCE\_FONCTIONNELLE\_MOY / INTEGRATION\_UA\_MOY / BONUS\_GROUPE\_MOY**
  → `kpi_value` `(… , metric_id=<<code>>)` → **value\_number**
  → *(détailler la rubrique dans **extra\_data\_json** si besoin, ex. `{ "rubrique":"Code" }`)*

---

# 14) Présences & corrélations (si module activé)

* **ABSENCES\_COUNT / RETARDS\_COUNT** → `kpi_value` `(… , metric_id=<<code>>)` → **value\_number**
* **CORR\_ABS\_SCORE / CORR\_ABS\_PROGRESSION** → `kpi_value` `(… , metric_id=<<code>>)` → **value\_number** *(coefficient)*

---

# 15) Tendances & prévisions (simples)

* **TENDANCE\_PROGRESSION\_28J** → `kpi_value` `(… , metric_id=TENDANCE_PROGRESSION_28J)` → **value\_number**
* **ETA\_ATTEINTE\_OBJECTIF** → `kpi_value` `(… , metric_id=ETA_ATTEINTE_OBJECTIF)` → **value\_number** *(jours restants)*
* **PREVISION\_COUVERTURE\_UA** → `kpi_value` `(… , metric_id=PREVISION_COUVERTURE_UA)` → **value\_number**

---

# 16) Raccourci tuiles (lecture)

Pour alimenter la page d’accueil, tu peux **copier** (ou recalculer) ces métriques dans `kpi_tile_cache` (une ligne par `(date_id, period_code, scope_id, target_id)`) :

* **progression\_moyenne** ← `PROGRESSION_MOY`
* **score\_normalise\_moyen** ← `SCORE_NORM`
* **pourcentage\_competences\_acquises** ← `PCT_COMP_ACQUISES`
* **pourcentage\_ua\_couvertes** ← `UA_COVERAGE_PCT`
* **pourcentage\_inactives\_7j** ← `INACTIVITE_7J`
* **delai\_median\_jours** ← `CYCLE_TIME_TACHE_MED` (ou `DELAI_ACQUIS_MICRO_MED` selon vue)
* **delai\_median\_feedback\_heures** ← `DELAI_FEEDBACK_MED`
* **commentaires\_7j** ← `FEEDBACK_COUNT` (fenêtre 7 jours)

---

## Exemples de “clé” complètes

* **Progression moyenne (semaine) d’un groupe sur un module**
  → `kpi_value`:
  `(date_id='2025-08-22', period_code='Week', scope_id=<Groupe>, target_id=<Module>, metric_id=PROGRESSION_MOY)` → `value_number=73.5`

* **Tâches en cours (jour) pour un projet**
  → `kpi_state_count`:
  `(date_id='2025-08-22', period_code='Day', scope_id=<Projet|Groupe>, target_id=<Projet>, state_id(domain='Tache','En_cours'))` → `state_count_value=15`

* **Délai médian de feedback (mois) pour un formateur**
  → `kpi_percentiles`:
  `(date_id='2025-08-31', period_code='Month', scope_id=<Formateur>, target_id=<Global|Module>, metric_id=DELAI_FEEDBACK)` → `percentile_50=24.0`

---

Si tu veux, je te fournis en plus un **CSV de `kpi_metric`** avec tous ces `code` prêts (unit\_code, aggregation\_type, category\_code) + un **Seeder Laravel** qui crée les entrées et te génère les `metric_id` automatiquement.
