Parfait. Voilà une **conception générique, performante et extensible** pour stocker les KPI prêts à l’affichage. L’idée : un **schéma en étoile** avec des **dimensions** (date, scope, cible) et des **faits** (valeurs de métriques, histogrammes d’états, percentiles, plan vs réalisé). Tout est polymorphique (type + id), donc **aucune dépendance forte** à vos tables métier.

# 1) Dimensions

```sql
-- 📅 Dimension temps (grain = jour)
CREATE TABLE kpi_time (
  date_id DATE PRIMARY KEY,              -- ex: '2025-08-22' (heure locale Africa/Casablanca)
  y SMALLINT NOT NULL,
  m TINYINT  NOT NULL,
  d TINYINT  NOT NULL,
  iso_week TINYINT NOT NULL,
  iso_year SMALLINT NOT NULL,
  month_label CHAR(7) GENERATED ALWAYS AS (DATE_FORMAT(date_id, '%Y-%m')) STORED
);

-- 🔎 Dimension "scope" = le point de vue (groupe, formateur, session, apprenant, global…)
CREATE TABLE kpi_scope (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  scope_type ENUM('GLOBAL','SESSION','GROUPE','SOUS_GROUPE','FORMATEUR','APPRENANT') NOT NULL,
  ref_id BIGINT NULL,                    -- id dans la table métier correspondante (optionnel pour GLOBAL)
  label VARCHAR(191) NULL,               -- étiquette snapshot pour éviter un join à l’affichage
  UNIQUE KEY uq_scope(scope_type, ref_id)
) ENGINE=InnoDB;

-- 🎯 Dimension "cible" = l’objet pédagogique agrégé (module, UA, compétence, micro-compétence, référentiel…)
CREATE TABLE kpi_target (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  target_type ENUM('GLOBAL','MODULE','UA','COMPETENCE','MICRO_COMPETENCE','REFERENTIEL') NOT NULL,
  ref_id BIGINT NULL,
  label VARCHAR(191) NULL,
  UNIQUE KEY uq_target(target_type, ref_id)
) ENGINE=InnoDB;

-- 📐 Dictionnaire de métriques (extensible)
CREATE TABLE kpi_metric (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(64) NOT NULL UNIQUE,      -- ex: PROGRESSION_MOY, SCORE_NORM, PCT_ACQUIS, INACTIVITE_7J, DELAI_MED_J, FEEDBACK_MED_H...
  label VARCHAR(191) NOT NULL,
  unit ENUM('%','points','nb','jours','heures','ratio','score') NOT NULL,
  agg ENUM('AVG','SUM','COUNT','MEDIAN','PERCENTILE','PCT') NOT NULL,
  higher_is_better TINYINT(1) NOT NULL DEFAULT 1,
  category ENUM('PROGRESSION','MAITRISE','RYTHME','ACTIVITE','COUVERTURE','ETAT','FEEDBACK','QUALITE') NOT NULL,
  target_levels SET('GLOBAL','MODULE','UA','COMPETENCE','MICRO_COMPETENCE') NOT NULL,
  description TEXT NULL
) ENGINE=InnoDB;
```

# 2) Tables de faits (snapshots agrégés)

### 2.1. Valeurs numéraires de KPI (générique)

```sql
-- 🌟 Table générique pour TOUTES les métriques “simples”
CREATE TABLE kpi_value (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  date_id DATE NOT NULL,
  period ENUM('D','W','M','R') NOT NULL DEFAULT 'D', -- Jour / Semaine / Mois / Rolling(7/30j) selon votre calcul
  scope_id BIGINT NOT NULL,
  target_id BIGINT NOT NULL,
  metric_id BIGINT NOT NULL,
  -- Valeurs
  value_num DECIMAL(12,4) NULL,           -- valeur déjà prête (ex: 73.25 %)
  numerator BIGINT NULL,                   -- pour les ratios (%/couverture)
  denominator BIGINT NULL,
  sample_size INT NULL,                    -- taille d'échantillon utile pour afficher la fiabilité
  extra_json JSON NULL,                    -- marge d'extension (ex: bornes de confiance)
  computed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_val (date_id, period, scope_id, target_id, metric_id),
  KEY idx_fast (scope_id, target_id, date_id, metric_id),
  CONSTRAINT fk_val_time   FOREIGN KEY (date_id)  REFERENCES kpi_time(date_id),
  CONSTRAINT fk_val_scope  FOREIGN KEY (scope_id) REFERENCES kpi_scope(id),
  CONSTRAINT fk_val_target FOREIGN KEY (target_id) REFERENCES kpi_target(id),
  CONSTRAINT fk_val_metric FOREIGN KEY (metric_id) REFERENCES kpi_metric(id)
) ENGINE=InnoDB;
```

### 2.2. Histogrammes d’états (feu tricolore)

```sql
-- 🧮 Répartition par état (À faire / En cours / Validé…), pour vues “barres empilées”
CREATE TABLE kpi_state_count (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  date_id DATE NOT NULL,
  period ENUM('D','W','M') NOT NULL DEFAULT 'D',
  scope_id BIGINT NOT NULL,
  target_id BIGINT NOT NULL,
  domain ENUM('MODULE','UA','COMPETENCE','MICRO_COMPETENCE') NOT NULL,
  state_code VARCHAR(32) NOT NULL,        -- ex: TODO / DOING / DONE
  state_ordre INT NOT NULL,               -- permet de trier les états
  count INT NOT NULL DEFAULT 0,
  UNIQUE KEY uq_hist (date_id, period, scope_id, target_id, domain, state_code),
  KEY idx_hist (scope_id, target_id, date_id, domain, state_ordre),
  CONSTRAINT fk_hist_time   FOREIGN KEY (date_id)  REFERENCES kpi_time(date_id),
  CONSTRAINT fk_hist_scope  FOREIGN KEY (scope_id) REFERENCES kpi_scope(id),
  CONSTRAINT fk_hist_target FOREIGN KEY (target_id) REFERENCES kpi_target(id)
) ENGINE=InnoDB;
```

### 2.3. Percentiles / médianes (délais d’acquisition, délais de feedback)

```sql
-- ⏱️ Percentiles pour les temps (médiane p50, p75, p90…)
CREATE TABLE kpi_percentiles (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  date_id DATE NOT NULL,
  period ENUM('D','W','M') NOT NULL DEFAULT 'D',
  scope_id BIGINT NOT NULL,
  target_id BIGINT NOT NULL,
  measure_code ENUM('DELAI_ACQUIS_MICRO','DELAI_ACQUIS_COMP','DELAI_FEEDBACK') NOT NULL,
  p50 DECIMAL(12,4) NULL,
  p75 DECIMAL(12,4) NULL,
  p90 DECIMAL(12,4) NULL,
  sample_size INT NULL,
  UNIQUE KEY uq_pct (date_id, period, scope_id, target_id, measure_code),
  KEY idx_pct (scope_id, target_id, date_id, measure_code),
  CONSTRAINT fk_pct_time   FOREIGN KEY (date_id)  REFERENCES kpi_time(date_id),
  CONSTRAINT fk_pct_scope  FOREIGN KEY (scope_id) REFERENCES kpi_scope(id),
  CONSTRAINT fk_pct_target FOREIGN KEY (target_id) REFERENCES kpi_target(id)
) ENGINE=InnoDB;
```

### 2.4. Plan vs réalisé (UA attendues vs terminées)

```sql
-- 📊 Écart au plan (utile pour le pilotage hebdo)
CREATE TABLE kpi_plan_gap (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  date_id DATE NOT NULL,
  period ENUM('W','M') NOT NULL DEFAULT 'W',
  scope_id BIGINT NOT NULL,
  target_id BIGINT NOT NULL,              -- typiquement UA ou MODULE
  expected_count INT NOT NULL DEFAULT 0,  -- attendues à date
  done_count INT NOT NULL DEFAULT 0,      -- réellement en état final
  gap INT AS (done_count - expected_count) STORED,
  coverage_pct DECIMAL(9,4) AS (CASE WHEN expected_count=0 THEN NULL
                                     ELSE (100.0 * done_count / expected_count) END) STORED,
  UNIQUE KEY uq_plan (date_id, period, scope_id, target_id),
  KEY idx_plan (scope_id, target_id, date_id),
  CONSTRAINT fk_plan_time   FOREIGN KEY (date_id)  REFERENCES kpi_time(date_id),
  CONSTRAINT fk_plan_scope  FOREIGN KEY (scope_id) REFERENCES kpi_scope(id),
  CONSTRAINT fk_plan_target FOREIGN KEY (target_id) REFERENCES kpi_target(id)
) ENGINE=InnoDB;
```

# 3) Données de référence initiales (exemples)

```sql
INSERT INTO kpi_metric(code,label,unit,agg,higher_is_better,category,target_levels)
VALUES
('PROGRESSION_MOY','Progression moyenne','%','AVG',1,'PROGRESSION','MODULE,COMPETENCE,MICRO_COMPETENCE'),
('SCORE_NORM','Score normalisé','points','AVG',1,'MAITRISE','MODULE,COMPETENCE,MICRO_COMPETENCE'),
('PCT_ACQUIS','% Compétences acquises','%','PCT',1,'MAITRISE','MODULE,COMPETENCE'),
('PCT_COUVERTURE_UA','% UA réalisées / plan','%','PCT',1,'COUVERTURE','UA,MODULE'),
('INACTIVITE_7J','% micro-comp inactives 7j','%','PCT',0,'ACTIVITE','COMPETENCE,MICRO_COMPETENCE'),
('DELAI_ACQUIS_MICRO','Médiane délais acquisition micro','jours','MEDIAN',0,'RYTHME','MICRO_COMPETENCE'),
('DELAI_FEEDBACK','Médiane délais feedback','heures','MEDIAN',0,'FEEDBACK','MODULE,COMPETENCE,MICRO_COMPETENCE');
```

# 4) Flux de calcul (recommandé)

* **Grain**: journalier (D) + hebdo (W) + rolling 7/30 j (R) si utile.
* **Déclencheurs**:

  * **Batch nocturne** pour recalcul complet (idempotent).
  * **Jobs incrémentaux** “event-driven” (ex : changement d’état, nouvelle réalisation, commentaire ajouté) pour rafraîchir la ligne `(date_id=today, period='D')` concernée.
* **Idempotence**: upsert sur `(date_id, period, scope_id, target_id, metric_id)`.
* **Scope/Target résolution**: au calcul, mappez vos id métier vers `kpi_scope(scope_type, ref_id)` et `kpi_target(target_type, ref_id)` (création si absent).

# 5) Exemples de requêtes d’affichage

### 5.1. Tuiles rapides (si vous n’utilisez pas la table cache, lisez dans `kpi_value`)

```sql
-- Dernière semaine (period='W') pour un groupe + module
SELECT
  t.label AS target, m.code, m.label, v.value_num, m.unit
FROM kpi_value v
JOIN kpi_metric m  ON m.id = v.metric_id
JOIN kpi_target t  ON t.id = v.target_id
JOIN kpi_scope  s  ON s.id = v.scope_id
WHERE s.scope_type='GROUPE' AND s.ref_id=:groupe_id
  AND t.target_type='MODULE' AND t.ref_id=:module_id
  AND v.period='W' AND v.date_id=(SELECT MAX(date_id) FROM kpi_value WHERE period='W');
```

### 5.2. Histogramme des états (feu tricolore)

```sql
SELECT state_code, state_ordre, SUM(count) AS n
FROM kpi_state_count
WHERE scope_id=:scope_id AND target_id=:target_id
  AND period='D' AND date_id=(SELECT MAX(date_id) FROM kpi_state_count WHERE period='D')
GROUP BY state_code, state_ordre
ORDER BY state_ordre;
```

### 5.3. Courbe d’évolution (progression hebdo)

```sql
SELECT date_id, value_num AS progression_pct
FROM kpi_value v
JOIN kpi_metric m ON m.id=v.metric_id AND m.code='PROGRESSION_MOY'
WHERE v.scope_id=:scope_id AND v.target_id=:target_id AND v.period='W'
ORDER BY date_id;
```

# 6) Index & bonnes pratiques

* Indices déjà posés pour les parcours les plus fréquents.
* Gardez `label` dans les dimensions pour **éviter des JOIN** d’affichage. Rafraîchissez-le opportunément (ex : si le nom d’un groupe change).
* **Partitionnement par range de date** (optionnel) si volumétrie très élevée : `PARTITION BY RANGE (TO_DAYS(date_id))`.
* **Nettoyage**: conservez le **D** au jour J-90, **W/M** sur des périodes plus longues.
* **Contrôles**: stockez `sample_size` et, pour les %/ratios, `numerator/denominator` pour afficher des badges de fiabilité.

---

Si tu veux, je te fournis un **Seeder Laravel** pour créer ces tables + les métriques de base, et un **Job** d’agrégation (Daily + incrémental à l’évènement).
