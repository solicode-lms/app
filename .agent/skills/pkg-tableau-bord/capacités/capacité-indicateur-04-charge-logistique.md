# Capacité : Indicateur 04 — Charge Logistique & Administrative ($C_{\text{logistique}}$)

## 1. Fiche Synthétique

| Paramètre | Spécification Didactique & Technique |
| :--- | :--- |
| **Numéro & Symbole** | **Indicateur 04** — $C_{\text{logistique}}$ |
| **Nom Officiel** | Charge administrative et logistique réelle du formateur |
| **Axe Pédagogique** | H3 — Préservation du temps pédagogique et rationalisation bureaucratique |
| **Méthode de Calcul** | Standard Action-Cost Model (déterministe par acte de gestion réel) |
| **Capacité Hebdomadaire** | Référentiel de **26 heures par semaine** (et non 35h) |
| **Plafond Didactique** | $N_{\text{semaines}} \times 3\text{h}$ par session ($3\text{h/semaine}$ max recommandée) |
| **Formatage d'Affichage** | Si $< 60\text{ min}$ : `X min` (ex : `26 min`, `0 min`) ; si $\ge 60\text{ min}$ : `XhYY` (ex : `1h15`) |
| **Service / Trait** | `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur04Trait` |
| **Route & Page** | `admin.PkgTableauBord.indicateurs.charge-logistique` (`indicateurs/charge-logistique.blade.php`) |
| **Composant KPI** | `PkgTableauBord::components.kpi-charge-logistique` |

---

## 2. Principes Fondamentaux & Barème Unitaire

### A. Sanctuarisation de l'Observation Pédagogique (CRITIQUE)
- **Temps de gestion pure vs Temps pédagogique** : Le suivi individuel des réalisations des apprenants (`RealisationTache`) relève de l'observation et de l'accompagnement pédagogique en classe, et est **strictement exclu de la charge administrative** (coût = **0 min**).
- **Suivi opérationnel administratif** : Évalué au niveau de l'entité `Tache` du projet, étalonné à **2 min / tâche** (gestion administrative de suivi de session, hors pédagogie).

### B. Barème Officiel par Acte Administratif
Le décompte s'effectue exclusivement sur les actes réels enregistrés en base :
1. **Création de la Session / Projet** : 15 min / session.
2. **Mobilisation d'Unités d'Apprentissage (UA)** : 1 min / UA mobilisée.
3. **Conception des Tâches** : 3 min / tâche créée dans le projet.
4. **Affectation au Groupe** : 3 min / affectation de groupe.
5. **Suivi Administratif de Session** : 2 min / tâche créée.
6. **Validation Administrative** : 1 min / tâche validée dans la session (au niveau de l'entité `Tache`, non par `RealisationTache`).
7. **Feedbacks Administratifs** : 1 min / tâche ayant reçu au moins un feedback (au niveau de `Tache`).
8. **Consultation Tableau de bord / Kanban** : 0 min (sanctuaire didactique).
9. **Saisie des Notes / Bordereaux** : 0 min (automatisé par le LMS).

### C. Calcul par Session de Formation
- La durée est calculée entre `date_debut` et `date_fin` de l'affectation (`AffectationProjet`). En l'absence de dates, la valeur par défaut est de **2 semaines**.
- Capacité totale formateur sur la session : $\text{Capacité} = N_{\text{semaines}} \times 26\text{h}$.
- Disponibilité tutorat direct :
  $$\text{Disponibilité} = \frac{\text{Capacité} - C_{\text{logistique}}}{\text{Capacité}} \times 100\%$$

---

## 3. Seuils d'Alerte & Badges

- **Vert (Logistique optimisée)** : $C_{\text{logistique}} \le N_{\text{semaines}} \times 2{,}5\text{h}$
- **Orange (Charge notable)** : $N_{\text{semaines}} \times 2{,}5\text{h} < C_{\text{logistique}} \le N_{\text{semaines}} \times 5{,}0\text{h}$
- **Rouge (Surcharge administrative)** : $C_{\text{logistique}} > N_{\text{semaines}} \times 5{,}0\text{h}$

---

## 4. Composants & Ergonomie 100% Inline

- **Carte KPI** (`PkgTableauBord::components.kpi-charge-logistique`) :
  - Formateur : Total d'heures/minutes administratives de la session, pourcentage de la capacité hebdomadaire et disponibilité tutorat.
  - Responsable : Charge moyenne d'équipe et diagnostic de surcharge.
- **Page de Détail** (`admin.PkgTableauBord.indicateurs.charge-logistique`) :
  - Décomposition exacte des coûts par acte administratif dans une table détaillée.
  - Jauge de répartition entre temps administratif et disponibilité pédagogique.
  - Benchmark comparatif des formateurs par session.
