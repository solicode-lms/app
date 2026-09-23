# 📘 Tutoriel : Base de données KPI pour le suivi des compétences

## 1. Objectif

👉 Permettre d’afficher **rapidement** au formateur des indicateurs sur l’évolution des apprenants : progression, compétences acquises, couverture des UA, délais, feedback…

⚡ Idée clé : au lieu de recalculer à chaque affichage, on **pré-calcule** et on enregistre les valeurs sous forme de **snapshots KPI**.

---

## 2. Structure de la base

### a) Dimensions (qui ? quoi ? quand ?)

* **kpi\_time** : dimension temps (jour, semaine, mois…).
* **kpi\_scope** : à quel niveau on regarde (un groupe, un formateur, un apprenant, global).
* **kpi\_target** : sur quel objet pédagogique on mesure (un module, une UA, une compétence).
* **kpi\_metric** : dictionnaire des indicateurs disponibles (progression, % acquis, délais…).

### b) Tables de faits (valeurs)

* **kpi\_value** : valeurs numériques des indicateurs (progression moyenne = 73%).
* **kpi\_state\_count** : histogrammes (nb en “À faire”, nb en “Validé”…).
* **kpi\_percentiles** : temps médians/p90 (ex. délai d’acquisition).
* **kpi\_plan\_gap** : plan vs réalisé (ex. UA attendues 12, faites 10 → 83%).

---

## 3. Exemple concret

### Cas : Groupe “Dev Web 2025”, Module “Backend PHP”

1. **Dimensions remplies :**

```sql
-- Jour suivi
INSERT INTO kpi_time (date_id, y, m, d, iso_week, iso_year)
VALUES ('2025-08-22', 2025, 8, 22, 34, 2025);

-- Scope = groupe
INSERT INTO kpi_scope (scope_type, ref_id, label)
VALUES ('GROUPE', 101, 'Dev Web 2025');

-- Target = module
INSERT INTO kpi_target (target_type, ref_id, label)
VALUES ('MODULE', 12, 'Backend PHP');
```

2. **Indicateurs insérés :**

```sql
-- Progression moyenne du module
INSERT INTO kpi_value (date_id, period, scope_id, target_id, metric_id, value_num, numerator, denominator)
VALUES ('2025-08-22', 'D', 1, 1, 1, 73.5, 147, 200);

-- % de compétences acquises
INSERT INTO kpi_value (date_id, period, scope_id, target_id, metric_id, value_num, numerator, denominator)
VALUES ('2025-08-22', 'D', 1, 1, 3, 65.0, 26, 40);

-- Histogramme des états (20 en TODO, 15 en DOING, 65 en DONE)
INSERT INTO kpi_state_count (date_id, period, scope_id, target_id, domain, state_code, state_ordre, count)
VALUES 
 ('2025-08-22','D',1,1,'COMPETENCE','TODO',1,20),
 ('2025-08-22','D',1,1,'COMPETENCE','DOING',2,15),
 ('2025-08-22','D',1,1,'COMPETENCE','DONE',3,65);
```

---

## 4. Requêtes d’affichage

### a) Progression moyenne (tuile)

```sql
SELECT m.label AS indicateur, v.value_num, m.unit
FROM kpi_value v
JOIN kpi_metric m ON v.metric_id = m.id
WHERE v.scope_id=1 AND v.target_id=1
  AND v.metric_id=1 -- progression moyenne
  AND v.date_id='2025-08-22';
```

➡️ Résultat : `Progression moyenne | 73.5 %`

---

### b) Histogramme par état

```sql
SELECT state_code, count
FROM kpi_state_count
WHERE scope_id=1 AND target_id=1
  AND date_id='2025-08-22'
ORDER BY state_ordre;
```

➡️ Résultat :

* TODO : 20
* DOING : 15
* DONE : 65

---

### c) % compétences acquises

```sql
SELECT value_num AS pct_acquises
FROM kpi_value
WHERE scope_id=1 AND target_id=1
  AND metric_id=3
  AND date_id='2025-08-22';
```

➡️ Résultat : `65 %`

---

## 5. Visualisation possible

Le formateur verrait une **page tableau de bord** :

📊 **Backend PHP – Groupe Dev Web 2025 (22/08/2025)**

* Progression moyenne : **73.5 %**
* % compétences acquises : **65 %**
* Histogramme : 🟥 20 | 🟨 15 | 🟩 65

---

## 6. Avantages

* **Générique** : tu peux ajouter facilement de nouveaux indicateurs dans `kpi_metric`.
* **Rapide** : lecture immédiate (pas de recalcul live).
* **Multi-niveau** : groupe, formateur, apprenant, module, UA…
* **Extensible** : possible d’ajouter des indicateurs qualitatifs (feedback, délais).

---

👉 Fouad, veux-tu que je te prépare un **schéma visuel (diagramme entité-association)** pour illustrer la base à montrer à tes apprenants, ou bien tu préfères un **tuto en mode pas-à-pas Laravel migration + seed** ?
