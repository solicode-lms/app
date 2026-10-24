# Capacité : Indicateur 01 — Démarrage du Travail ($T_{\text{amorçage}}$)

## 1. Fiche Synthétique

| Paramètre | Spécification Didactique & Technique |
| :--- | :--- |
| **Numéro & Symbole** | **Indicateur 01** — $T_{\text{amorçage}}$ |
| **Nom Officiel** | Temps moyen de mise au travail (Apprenants présents) |
| **Axe Pédagogique** | H1 — Régulation de l'Apprenant, Rythme de Démarrage & Détection Précoce |
| **Unité & Format** | Minutes / Heures (ex : `45 min`, `1h15`, ou `'--'` si en attente) |
| **Seuil Groupe (SLA)** | Optimal $\le 3{,}0\,\text{h}$ ($180\text{ min}$), Alerte $> 3{,}0\,\text{h}$ |
| **Seuil Individuel** | Optimal $\le 1{,}5\,\text{h}$ ($90\text{ min}$) |
| **Créneaux Officiels** | S1 : 09:00 - 11:30 (150 min) \| S2 : 11:30 - 14:00 (150 min) \| S3 : 14:30 - 17:00 (150 min) |
| **Service / Trait** | `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur01Trait` |
| **Route & Page** | `admin.PkgTableauBord.indicateurs.amorcage` (`indicateurs/amorcage.blade.php`) |
| **Composant KPI** | `PkgTableauBord::components.kpi-amorcage` |

---

## 2. Modélisation Didactique & Gestion des Absences

### A. Règle de Présence en Séance de Lancement
1. Le formateur lance officiellement le projet à une date $D_{\text{lancement}}$ (`cache_date_lancement` ou `date_debut` de l'affectation).
2. Le système calcule l'heure exacte de fin de la séance correspondante :
   $$D_{\text{fin\_séance}} = \text{ParametrePedagogiqueService::determinerFinSeanceLancement}(D_{\text{lancement}})$$
3. **Critère de présence** : Pour être considéré comme **présent à la séance de lancement**, l'apprenant doit activer sa première tâche (passage à l'état `En cours` / `IN_PROGRESS`) **avant ou à** $D_{\text{fin\_séance}}$.
4. L'horodatage précis de cette mise en cours est extrait par priorité :
   - Directement dans `cache_date_lancement` de `RealisationProjet` s'il est renseigné.
   - S'il est `null`, dans `historique_realisation_taches` (changement d'état vers `IN_PROGRESS` horodaté à la seconde via `determinerDatePremiereTacheEnCours`).

### B. Catégorisation Didactique des Réalisations
- **Présent Amorcé** : Première tâche passée en cours $\le D_{\text{fin\_séance}}$ ET première soumission effectuée.
  - Délai individuel $T_{\text{amorçage}}(i) = \text{date\_premiere\_soumission}(i) - D_{\text{lancement}}$.
  - **Intégré** dans la moyenne collective de la séance.
- **Démarrage avec Absence** (`demarrage_avec_absence`) : Première tâche passée en cours $> D_{\text{fin\_séance}}$.
  - L'apprenant a démarré le projet en retard suite à une absence lors du créneau initial.
  - Son délai individuel est affiché avec la mention *"Hors moyenne (Post-séance)"*, mais il est **strictement exclu de la moyenne collective** du groupe pour ne pas fausser l'évaluation pédagogique de la séance.
- **Non Amorcé / En Attente** (`non_amorce`) : Aucune tâche soumise (ou aucune tâche passée en cours).

---

## 3. Formules Mathématiques

### Micro (Apprenant individuel $i$) :
$$T_{\text{amorçage}}(i) = \text{cache\_date\_premiere\_soumission}(i) - D_{\text{lancement}}$$

### Méso (Promotion / Groupe / Session) :
Soit $P$ l'ensemble des apprenants présents ayant amorcé le projet en séance :
$$P = \{ i \in \text{Apprenants} \mid \text{date\_en\_cours}(i) \le D_{\text{fin\_séance}} \land T_{\text{amorçage}}(i) \neq \text{null} \}$$

$$\overline{T}_{\text{amorçage}} = \begin{cases} 
\frac{1}{|P|} \sum_{i \in P} T_{\text{amorçage}}(i) & \text{si } |P| > 0 \\
\text{null} & \text{si } |P| = 0 \text{ (Projet au démarrage / en attente)}
\end{cases}$$

> [!IMPORTANT]
> **Principe de Clarté / Zéro Fallback Magique** :
> Si $|P| = 0$, le délai affiché vaut impérativement `'--'`, le statut est `'En attente'` et le badge est neutre (`bg-slate-50 text-slate-700 border border-slate-200`). Il est formellement interdit d'injecter une valeur par défaut arbitraire (ex : 1h45) !

### Médiane Globale :
Calculée sur la distribution ordonnée des délais des présents pour fournir une mesure robuste insensible aux valeurs extrêmes.

---

## 4. Composants & Ergonomie 100% Inline

- **Carte KPI** (`PkgTableauBord::components.kpi-amorcage`) :
  - Apprenant : Délai personnel formaté + badge de rythme face à la moyenne de la classe.
  - Formateur : Délai moyen des présents, nombre d'amorcés en séance, badge d'alerte des démarrages avec absence, lien direct vers la page de détail.
  - Responsable : Délai moyen macroscopique, taux global d'amorçage, benchmark multi-sessions.
- **Page de Détail** (`admin.PkgTableauBord.indicateurs.amorcage`) :
  - 5 cartes métriques clés (Délai moyen, Amorcés en séance, Démarrages avec absence, Record réactivité, Non amorcés).
  - Histogramme de distribution temporelle (5 tranches didactiques).
  - Roster interactif des apprenants sous Alpine.js (recherche textuelle instantanée, filtrage par tranche).
  - Benchmark comparatif des sessions de formation N2.
