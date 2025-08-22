Top, voici les **KPI concrets** à afficher au formateur pour suivre le développement des compétences (vue filtrable par groupe / sous-groupe / module / formateur).

### 1) Progression & couverture

* **Progression moyenne du module (%)** : moyenne de `progression_cache` dans `realisation_modules`.&#x20;
* **Progression moyenne par compétence / micro-compétence (%)** : moyenne de `progression_cache` dans `realisation_competences` et `realisation_micro_competences`. &#x20;
* **Couverture du référentiel** : `% micro_competences démarrées` = (micro-comp. avec `date_debut` non NULL) / (total des `micro_competences` liées au module). &#x20;
* **UA réalisées / planifiées** : nb d’`realisation_uas` en état final ÷ nb d’UA planifiées via `alignement_uas` pour la session. &#x20;

### 2) Maîtrise & notes

* **Score normalisé (sur 100)** : `100 * note_cache / NULLIF(bareme_cache,0)` au niveau module, compétence et micro-compétence.  &#x20;
* **Compétences “acquises” (%)** : part des `realisation_competences` dont l’état (`etat_realisation_competence_id`) pointe vers un `etat_realisation_competences` de rang terminal (`ordre` max). &#x20;

### 3) Délais & rythme

* **Temps d’acquisition (médiane, j)** : médiane de `date_fin - date_debut` pour micro-compétences (et compétences). &#x20;
* **Écart au plan UA** : UA “attendues à date” (via `alignement_uas`) vs UA effectivement en état “terminé” (`etat_realisation_uas.ordre`). &#x20;

### 4) Activité & feedback

* **Inactivité** : `% micro-compétences sans `dernier\_update` <X jours>`.&#x20;
* **Livrables fournis (%)** : part des micro-compétences avec `lien_livrable` non NULL.&#x20;
* **Feedback (volume / réactivité)** : nb de `commentaire_realisation_taches` sur la période (proxy d’accompagnement) et délai médian entre deux commentaires.&#x20;

### 5) Répartition des états (vue “feu tricolore”)

* **Par micro-compétence / UA / module** : histogramme par `etat_* (code, ordre)` pour visualiser “À faire → En cours → Validé”.  &#x20;

### 6) Découpages utiles pour le formateur

* **Par groupe / sous-groupe / formateur** via `apprenant_groupe`, `apprenant_sous_groupe`, `formateur_groupe`.  &#x20;

---

## Exemples SQL (directement exploitables)

**A. Progression moyenne par module et par groupe**

```sql
SELECT g.id AS groupe_id, m.id AS module_id,
       AVG(rm.progression_cache) AS progression_moy
FROM realisation_modules rm
JOIN apprenant_groupe ag ON ag.apprenant_id = rm.apprenant_id
JOIN groupes g ON g.id = ag.groupe_id
JOIN modules m ON m.id = rm.module_id
GROUP BY g.id, m.id;
```

&#x20;&#x20;

**B. % de compétences “acquises” (état final) par module**

```sql
WITH etat_final AS (
  SELECT MAX(ordre) AS ord_max FROM etat_realisation_competences
)
SELECT rc.realisation_module_id,
       100.0 * SUM(CASE WHEN erc.ordre = (SELECT ord_max FROM etat_final) THEN 1 ELSE 0 END)
       / NULLIF(COUNT(*),0) AS pct_acquises
FROM realisation_competences rc
LEFT JOIN etat_realisation_competences erc
       ON erc.id = rc.etat_realisation_competence_id
GROUP BY rc.realisation_module_id;
```

&#x20;

**C. Couverture UA (réalisées / planifiées) par session**

```sql
SELECT sf.id AS session_id,
       100.0 * SUM(CASE WHEN erua.ordre = max_ordre.max_ord THEN 1 ELSE 0 END)
       / NULLIF(COUNT(au.id),0) AS pct_ua_couvertes
FROM alignement_uas au
JOIN session_formations sf ON sf.id = au.session_formation_id
LEFT JOIN realisation_uas rua ON rua.unite_apprentissage_id = au.unite_apprentissage_id
LEFT JOIN etat_realisation_uas erua ON erua.id = rua.etat_realisation_ua_id
CROSS JOIN (SELECT MAX(ordre) AS max_ord FROM etat_realisation_uas) AS max_ordre
GROUP BY sf.id;
```

&#x20;&#x20;

**D. Micro-compétences inactives (>7 jours)**

```sql
SELECT rc.realisation_competence_id,
       COUNT(*) AS nb_inactives
FROM realisation_micro_competences rc
WHERE rc.dernier_update IS NULL
   OR rc.dernier_update < NOW() - INTERVAL 7 DAY
GROUP BY rc.realisation_competence_id;
```



---

### Recommandations d’affichage (rapide)

* Un **résumé en tuiles** : progression moyenne, % acquises, couverture UA, inactivité, score normalisé. (1 ligne / module).  &#x20;
* Un **heatmap par micro-compétence** (apprenants en colonne, micro-compétences en ligne) colorée par `etat.*.ordre`. &#x20;
* Un **timeline “début → fin”** pour détecter les lenteurs (dates de début/fin).&#x20;

Si tu veux, je te fournis la **requête complète** pour une page dashboard (avec filtres groupe / sous-groupe / module) et les **endpoints** Laravel/Eloquent adaptés.
