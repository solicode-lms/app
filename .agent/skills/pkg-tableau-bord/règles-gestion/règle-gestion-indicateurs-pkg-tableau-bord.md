# Règle de Gestion : Spécification des Indicateurs (Sprints 1 & 2)

Ce document détaille la modélisation mathématique, les seuils d'alerte et les algorithmes des indicateurs développés dans le module `PkgTableauBord`.
Les indicateurs des sprints ultérieurs (3 à 9) seront intégrés de façon itérative.

---

## ⏱️ Indicateur 01 : Démarrage du Travail ($T_{\text{amorçage}}$) — Sprint 1

### 1. Fiche Synthétique
| Paramètre | Valeur |
| :--- | :--- |
| **N° & Symbole** | **01** — $T_{\text{amorçage}}$ |
| **Nom de l'Indicateur** | Temps moyen de mise au travail (Apprenants présents) |
| **Axe Pédagogique** | H1 — Régulation de l'Apprenant, Rythme de Démarrage & Détection Précoce |
| **Unité** | Minutes / Heures (ex: `1h15`, `45 min`, ou `'--'` si en attente) |
| **Seuil Didactique Groupe (SLA)** | Optimal $\le 3{,}0\,\text{h}$ ($180\text{ min}$), Alerte $> 3{,}0\,\text{h}$ |
| **Seuil Individuel Apprenant** | Optimal $\le 1{,}5\,\text{h}$ ($90\text{ min}$) |
| **Créneaux Officiels de Séance** | S1 : 09:00 - 11:30 (150 min) \| S2 : 11:30 - 14:00 (150 min) \| S3 : 14:30 - 17:00 (150 min) |

### 2. Modélisation Didactique & Gestion des Absences

#### A. Règle de Présence en Séance de Lancement
- Le formateur lance officiellement le projet à une date $D_{\text{lancement}}$ (`cache_date_lancement` ou `date_debut`).
- Le système calcule l'heure de fin de la séance correspondante : $D_{\text{fin\_séance}} = \text{determinerFinSeanceLancement}(D_{\text{lancement}})$.
- **Critère de présence** : Pour être considéré comme présent à la séance de lancement, l'apprenant doit activer sa première tâche (statut `En cours` / `IN_PROGRESS`) **avant ou à** $D_{\text{fin\_séance}}$.
- L'horodatage précis de cette mise en cours est extrait par priorité :
  1. Dans `historique_realisation_taches` (changement `En cours` ou `IN_PROGRESS` horodaté à la seconde).
  2. Dans la date de début (`dateDebut`) de la tâche si elle contient une heure précise différente de minuit.

#### B. Catégorisation des Réalisations
Pour chaque réalisation de projet, trois états didactiques sont possibles :
1. **Présent Amorcé** : Première tâche passée en cours $\le D_{\text{fin\_séance}}$ ET première soumission effectuée.
   - Son délai individuel $T_{\text{amorçage}}(i) = \text{date\_premiere\_soumission}(i) - D_{\text{lancement}}$ est calculé en minutes.
   - Ce délai est **intégré** dans la moyenne collective de la séance.
2. **Démarrage avec Absence** (`demarrage_avec_absence`) : Première tâche passée en cours $> D_{\text{fin\_séance}}$.
   - L'apprenant a démarré le projet en retard suite à une absence lors du créneau initial.
   - Son délai individuel est affiché avec la mention *"Hors moyenne (Post-séance)"*, mais il est **strictement exclu de la moyenne collective** du groupe pour ne pas fausser l'évaluation pédagogique de la séance.
3. **Non Amorcé / En Attente** (`non_amorce`) : Aucune tâche soumise (ou aucune tâche passée en cours).

### 3. Formules Mathématiques

#### A. Délai Individuel (Micro — Apprenant $i$) :
$$T_{\text{amorçage}}(i) = \text{cache\_date\_premiere\_soumission}(i) - D_{\text{lancement}}$$

#### B. Moyenne Collective sur Présents (Méso — Session de Formation / Groupe) :
Soit $P$ l'ensemble des apprenants présents ayant amorcé le projet en séance :
$$P = \{ i \in \text{Apprenants} \mid \text{date\_en\_cours}(i) \le D_{\text{fin\_séance}} \land T_{\text{amorçage}}(i) \neq \text{null} \}$$

$$\overline{T}_{\text{amorçage}} = \begin{cases} 
\frac{1}{|P|} \sum_{i \in P} T_{\text{amorçage}}(i) & \text{si } |P| > 0 \\
\text{null} & \text{si } |P| = 0 \text{ (Projet au démarrage / en attente)}
\end{cases}$$

- **Formatage & Statut pour $|P| = 0$ (Principe de Clarté)** :
  - Délai affiché : `'--'`
  - Statut : `'En attente'`
  - Badge : Neutre (`bg-slate-50 text-slate-700 border border-slate-200`)
  - **Interdiction formelle de substituer une valeur par défaut arbitraire (ex: 1h45) !**

#### C. Médiane Globale :
Calculée sur la distribution ordonnée des délais pour donner une lecture robuste insensible aux valeurs extrêmes.

#### D. Moyenne Macroscopique (Macro — Responsable de Formation) :
Calculée sur l'ensemble des apprenants présents de l'échantillon des sessions de formation N2 récentes, avec mise en évidence du nombre global de démarrages avec absence exclus.

---

## 🛡️ Indicateur 02 : Validation du Prototype au Premier Essai ($R_{\text{gating}}$) — Sprint 2

### 1. Fiche Synthétique
| Paramètre | Valeur |
| :--- | :--- |
| **N° & Symbole** | **02** — $R_{\text{gating}}$ |
| **Nom de l'Indicateur** | Taux de validation au gating dès le premier essai |
| **Axe Pédagogique** | H1 — Assimilation des fondamentaux et prévention de l'illusion de maîtrise |
| **Unité** | Pourcentage ($0 \le R_{\text{gating}} \le 100\%$) |
| **Seuil Vert (Excellence)** | $\ge 80\,\%$ |
| **Seuil Orange (Vigilance)** | $65\,\% \le R_{\text{gating}} < 80\,\%$ |
| **Seuil Rouge (Alerte)** | $< 65\,\%$ (Illusion d'acquis structurelle, remédiation impérative) |

### 2. Formules Mathématiques

#### A. Micro (Individuel — Apprenant $i$) :
$$\text{Gating}_1(i) = \begin{cases} 
1 & \text{si validé dès la tentative 1 (statut } \text{APPROVED} \text{ ou note } \ge 10) \\
0 & \text{si ajourné à la tentative 1 (statut } \text{NOT\_VALIDATED} \text{ / Révision)}
\end{cases}$$
- Si ajourné, l'apprenant passe en atelier de remédiation et effectue une seconde tentative ($\text{tentative} = 2$), mais son statut $\text{Gating}_1(i)$ reste définitivement fixé à $0$.

#### B. Méso (Collectif — Promotion / Groupe) :
$$R_{\text{gating}} = \frac{N_{\text{validés\_essai\_1}}}{N_{\text{évalués}}} \times 100$$
- $N_{\text{validés\_essai\_1}}$ : Effectif des apprenants ayant réussi dès la 1ère évaluation.
- $N_{\text{évalués}}$ : Effectif total ayant subi au moins une évaluation de prototype.

#### C. Macro (Établissement — Gouvernance) :
$$R_{\text{gating\_macro}} = \frac{\sum_{g=1}^{G} N_{\text{validés\_essai\_1}}(g)}{\sum_{g=1}^{G} N_{\text{évalués}}(g)} \times 100$$

### 3. Algorithme Événementiel (Temps Réel)
1. Détection de la tâche Prototype : Une tâche est de type Prototype si et seulement si sa phase d'évaluation liée est Niveau 2 (`tache->phaseEvaluation->code === 'N2'`).
2. Le formateur évalue cette tâche Prototype (`RealisationTache`).
3. Interception par `RealisationTacheJobTrait::updatedObserverJob`.
4. Incrémentation de `realisationProjet->cache_nombre_tentatives_prototype`.
5. Si `tentative == 1` :
   - Fige `realisationProjet->cache_est_valide_prototype_premier_essai = ($estValide ? true : false)`.
6. Si `tentative > 1` :
   - Maintient la valeur initiale (garantie d'idempotence docimologique).
