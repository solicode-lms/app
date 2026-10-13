# Règle de Gestion : Indicateur 01 — Démarrage du Travail ($T_{\text{amorçage}}$)

Ce document détaille la modélisation mathématique, les seuils d'alerte et l'algorithme d'implémentation de l'**Indicateur 01** (Sprint 1 du module `PkgTableauBord`).
Les indicateurs des sprints ultérieurs seront intégrés de façon itérative.

---

## 1. Fiche Synthétique de l'Indicateur 01

| Paramètre | Valeur |
| :--- | :--- |
| **N° & Symbole** | **01** — $T_{\text{amorçage}}$ |
| **Nom de l'Indicateur** | Temps d'amorçage moyen |
| **Axe Pédagogique** | H1 — Régulation de l'Apprenant & Rythme de Démarrage |
| **Unité** | Minutes / Heures (ex: `1h15`) |
| **Seuil Vert (Optimal)** | $\le 1{,}5\,\text{h}$ ($90\text{ min}$) |
| **Seuil Rouge (Alerte)** | $> 3{,}0\,\text{h}$ ($180\text{ min}$) |
| **Action Dashboard Immédiate** | Assistance ciblée sur l'environnement de développement ou tutoriel d'amorce. |

---

## 2. Formules Mathématiques & Règles de Calcul

### A. Calcul Individuel (Apprenant) :
$$T_{\text{amorçage}}(i) = \text{cache\_date\_premiere\_soumission}(i) - \text{cache\_date\_lancement}$$
- Mesure la latence entre le top départ donné par le formateur et la première tâche soumise par l'apprenant.
- Converti et affiché en heures et minutes (ex: `1h15`).

### B. Calcul Collectif (Cohorte / Promotion) :
$$T_{\text{amorçage}} = \frac{1}{N} \sum_{i=1}^{N} T_{\text{amorçage}}(i)$$
- Moyenne calculée sur l'ensemble des apprenants ayant amorcé au moins une tâche.
- Ratio d'amorce : $N_{\text{amorcés}} / N_{\text{total}}$ (ex: `17 / 20 apprenants démarrés`).

### C. Calcul Macroscopique (Responsable) :
- Moyenne globale inter-cohortes et benchmark comparatif (ex: `DWB-101 (1h45)` vs `DMB-101 (1h55)`).

---

## 3. Algorithmes & Cycle de Vie Événementiel

### Déclencheur Temps Réel (JobManager) :
1. Une `RealisationTache` change d'état vers `TO_APPROVE` ou `READY_FOR_LIVE_CODING`.
2. L'observer `RealisationTacheJobTrait::updatedObserverJob` intercepte l'événement.
3. Si `$realisationProjet->cache_date_premiere_soumission` est `NULL` :
   - Fige `cache_date_premiere_soumission = now()`.
   - Synchronise `$realisationProjet->cache_date_lancement = $affectationProjet->cache_date_lancement`.
4. Si déjà renseigné : conserve la valeur initiale (règle d'idempotence stricte).

### Calcul de Secours de la Date de Lancement :
Si `cache_date_lancement` n'a pas été renseigné explicitement par le formateur :
$$\text{cache\_date\_lancement} = \min \left( \text{MIN}(\text{date\_passage\_IN\_PROGRESS}), \, \text{date\_debut à 09:00} \right)$$
