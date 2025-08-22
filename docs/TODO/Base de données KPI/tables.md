Parfait Fouad 👍

Voici **l’article complet corrigé**, où j’ai remplacé toutes les colonnes “dangereuses” (`year`, `month`, `day`, `unit`, `category`, `code`, `count`, `min`, `max`) par des noms sûrs (`year_number`, `month_number`, `day_number`, `unit_code`, `category_code`, `state_code`, `state_count_value`, `min_observed_value`, `max_observed_value`).

---

# 📌 Schéma SQL (version finale safe MySQL)

```sql
-- 📅 Dimension temps
CREATE TABLE kpi_time (
  date_id DATE PRIMARY KEY,                           -- ex: '2025-08-22'
  year_number SMALLINT NOT NULL,                      -- année (2025)
  month_number TINYINT NOT NULL,                      -- mois (1..12)
  day_number TINYINT NOT NULL,                        -- jour du mois
  iso_week_number TINYINT NOT NULL,                   -- numéro de semaine ISO
  iso_year_number SMALLINT NOT NULL,                  -- année ISO
  month_label CHAR(7) GENERATED ALWAYS AS (DATE_FORMAT(date_id, '%Y-%m')) STORED
) ENGINE=InnoDB;

-- 🔎 Dimension "scope" (population observée : groupe, formateur, etc.)
CREATE TABLE kpi_scope (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  scope_type VARCHAR(32) NOT NULL,                    -- ex: Global, Session, Groupe, Formateur, Apprenant
  reference_id BIGINT NULL,
  label VARCHAR(191) NULL,
  UNIQUE KEY uq_scope(scope_type, reference_id),
  KEY idx_scope (scope_type, reference_id)
) ENGINE=InnoDB;

-- 🎯 Dimension "cible"
CREATE TABLE kpi_target (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  target_type VARCHAR(32) NOT NULL,                   -- ex: Module, Ua, Competence
  reference_id BIGINT NULL,
  label VARCHAR(191) NULL,
  UNIQUE KEY uq_target(target_type, reference_id),
  KEY idx_target (target_type, reference_id)
) ENGINE=InnoDB;

-- 📐 Dictionnaire des métriques
CREATE TABLE kpi_metric (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(64) NOT NULL UNIQUE,
  label VARCHAR(191) NOT NULL,
  unit_code VARCHAR(32) NOT NULL,                     -- ex: Pourcentage, Points, Nombre
  aggregation_type VARCHAR(32) NOT NULL,              -- Moyenne, Somme, Nombre, Mediane
  higher_is_better TINYINT(1) NOT NULL DEFAULT 1,
  category_code VARCHAR(32) NOT NULL,                 -- ex: Progression, Maitrise, Rythme
  target_levels VARCHAR(191) NOT NULL,
  description TEXT NULL
) ENGINE=InnoDB;

-- 🧭 Etats génériques
CREATE TABLE kpi_state (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  domain VARCHAR(64) NOT NULL,
  state_code VARCHAR(32) NOT NULL,                    -- ex: A_faire, En_cours, Termine
  label VARCHAR(191) NOT NULL,
  ordre INT NOT NULL,
  color VARCHAR(16) NULL,
  UNIQUE KEY uq_state (domain, state_code),
  KEY idx_state (domain, ordre)
) ENGINE=InnoDB;

-- 🌟 Valeurs agrégées
CREATE TABLE kpi_value (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  date_id DATE NOT NULL,
  period_code VARCHAR(16) NOT NULL,                   -- Jour, Semaine, Mois
  scope_id BIGINT NOT NULL,
  target_id BIGINT NOT NULL,
  metric_id BIGINT NOT NULL,
  value_number DECIMAL(12,4) NULL,
  numerator_value BIGINT NULL,
  denominator_value BIGINT NULL,
  sample_size INT NULL,
  extra_data_json JSON NULL,
  computed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_val (date_id, period_code, scope_id, target_id, metric_id),
  KEY idx_fast (scope_id, target_id, date_id, metric_id, period_code)
) ENGINE=InnoDB;

-- 🧮 Comptage par état
CREATE TABLE kpi_state_count (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  date_id DATE NOT NULL,
  period_code VARCHAR(16) NOT NULL,
  scope_id BIGINT NOT NULL,
  target_id BIGINT NOT NULL,
  state_id BIGINT NOT NULL,
  state_count_value INT NOT NULL DEFAULT 0,
  UNIQUE KEY uq_hist (date_id, period_code, scope_id, target_id, state_id),
  KEY idx_hist (scope_id, target_id, date_id, state_id, period_code)
) ENGINE=InnoDB;

-- ⏱️ Percentiles
CREATE TABLE kpi_percentiles (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  date_id DATE NOT NULL,
  period_code VARCHAR(16) NOT NULL,
  scope_id BIGINT NOT NULL,
  target_id BIGINT NOT NULL,
  metric_id BIGINT NOT NULL,
  percentile_50 DECIMAL(12,4) NULL,
  percentile_75 DECIMAL(12,4) NULL,
  percentile_90 DECIMAL(12,4) NULL,
  min_observed_value DECIMAL(12,4) NULL,
  max_observed_value DECIMAL(12,4) NULL,
  sample_size INT NULL,
  UNIQUE KEY uq_pct (date_id, period_code, scope_id, target_id, metric_id),
  KEY idx_pct (scope_id, target_id, date_id, metric_id, period_code)
) ENGINE=InnoDB;

-- 📊 Plan vs réalisé
CREATE TABLE kpi_plan_gap (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  date_id DATE NOT NULL,
  period_code VARCHAR(16) NOT NULL,
  scope_id BIGINT NOT NULL,
  target_id BIGINT NOT NULL,
  expected_count INT NOT NULL DEFAULT 0,
  done_count INT NOT NULL DEFAULT 0,
  gap_value INT AS (done_count - expected_count) STORED,
  coverage_percentage DECIMAL(9,4) AS (
    CASE WHEN expected_count=0 THEN NULL
         ELSE (100.0 * done_count / expected_count) END) STORED,
  UNIQUE KEY uq_plan (date_id, period_code, scope_id, target_id),
  KEY idx_plan (scope_id, target_id, date_id, period_code)
) ENGINE=InnoDB;

-- 🚀 Cache des tuiles
CREATE TABLE kpi_tile_cache (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  date_id DATE NOT NULL,
  period_code VARCHAR(16) NOT NULL,
  scope_id BIGINT NOT NULL,
  target_id BIGINT NOT NULL,
  progression_moyenne DECIMAL(7,3) NULL,
  score_normalise_moyen DECIMAL(7,3) NULL,
  pourcentage_competences_acquises DECIMAL(7,3) NULL,
  pourcentage_ua_couvertes DECIMAL(7,3) NULL,
  pourcentage_inactives_7j DECIMAL(7,3) NULL,
  delai_median_jours DECIMAL(7,3) NULL,
  delai_median_feedback_heures DECIMAL(7,3) NULL,
  commentaires_7j INT NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
              ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_tile (date_id, period_code, scope_id, target_id),
  KEY idx_tile (scope_id, target_id, date_id, period_code)
) ENGINE=InnoDB;
```

---

# 📂 CSV d’initialisation

### `kpi_metric.csv`

```csv
code,label,unit_code,aggregation_type,higher_is_better,category_code,target_levels,description
PROGRESSION_MOY,Progression moyenne,Pourcentage,Moyenne,1,Progression,"Module,Competence,Micro_competence","Progression moyenne sur la période"
SCORE_NORM,Score normalisé,Points,Moyenne,1,Maitrise,"Module,Competence,Micro_competence","Score rapporté sur 100"
PCT_ACQUIS,% Competences acquises,Pourcentage,Pourcentage,1,Maitrise,"Module,Competence","Pourcentage de compétences validées"
PCT_COUVERTURE_UA,% UA realisees / plan,Pourcentage,Pourcentage,1,Couverture,"Ua,Module","Couverture du plan UA"
INACTIVITE_7J,% micro-comp inactives 7j,Pourcentage,Pourcentage,0,Activite,"Competence,Micro_competence","Inactivité sur 7 jours"
DELAI_ACQUIS_MICRO,Delai median acquisition micro,Jours,Mediane,0,Rythme,"Micro_competence","Temps médian pour acquérir une micro-compétence"
DELAI_FEEDBACK,Delai median feedback,Heures,Mediane,0,Feedback,"Module,Competence,Micro_competence","Temps médian de feedback"
```

### `kpi_state.csv`

```csv
domain,state_code,label,ordre,color
Competence,A_faire,A faire,1,red
Competence,En_cours,En cours,2,orange
Competence,Valide,Validée,3,green
Ua,A_faire,A faire,1,red
Ua,En_cours,En cours,2,orange
Ua,Valide,Validée,3,green
Tache,A_faire,A faire,1,red
Tache,En_cours,En cours,2,orange
Tache,Terminee,Terminée,3,green
Commande,Nouvelle,Nouvelle commande,1,blue
Commande,Payee,Payée,2,green
Commande,Annulee,Annulée,99,gray
```

---

✅ Maintenant, aucun champ n’entre en conflit avec MySQL.
👉 Veux-tu que je transforme cette version en **migrations Laravel prêtes à l’emploi** (`php artisan make:migration`), avec les bons types et index ?
