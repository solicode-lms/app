# Contexte IA du Module : PkgTableauBord

## 1. Responsabilité Métier
Le package **PkgTableauBord** centralise les métriques d'apprentissage, le suivi de progression et la traçabilité officielle de Solicode LMS :
1. **Les 9 Indicateurs Décisionnels :** Calcul et affichage des indicateurs de pilotage pédagogique (amorçage, gating, révision, charge, file d'attente, remédiation, TRVS, couverture des compétences, planification des UA).
2. **Dispositif de Remédiation :** Suivi personnalisé des apprenants en difficulté (`Remediation`).
3. **Bordereaux Officiels :** Production des bordereaux académiques OFPPT (`BordereauxOfficiel`).
4. **Journaux d'Activité :** Traçabilité des actions des encadrants (`LogActiviteFormateur`).

---

## 2. Modèles & Relations Principales

### `BordereauxOfficiel` (Table : `bordereaux_officiels`)
- **Relations :** `anneeFormation`, `filiere`, `module`.
- **Rôle :** Génération et archivage des bordereaux officiels de notes.

### `Remediation` (Table : `remediations`)
- **Relations :** `apprenant`, `formateur`, `competence`.
- **Rôle :** Déclenchement et validation des parcours de soutien technique.

### `LogActiviteFormateur` (Table : `log_activite_formateurs`)
- **Relations :** `formateur`, `user`.
- **Rôle :** Historique des consultations, évaluations et interventions.

---

## 3. Le Référentiel des 9 Indicateurs Clés

| N° | Indicateur | Rôle & Mode de Calcul |
| :---: | :--- | :--- |
| **01** | **Amorçage** | Mesure le délai moyen et le pourcentage d'apprenants ayant débuté leur première tâche après affectation. |
| **02** | **Gating** | Contrôle le respect strict des prérequis avant passage à l'étape suivante. |
| **03** | **Révision** | Fréquence et temps de traitement des demandes de révision de code par le formateur. |
| **04** | **Charge Logistique** | Répartition des apprenants actifs et des tâches par encadrant pour éviter la saturation. |
| **05** | **File d'Attente** | Nombre d'apprenants ayant soumis un livrable ou un Live Coding en attente de validation formateur. |
| **06** | **Remédiation** | Suivi du taux d'apprenants orientés vers un plan de soutien technique après échec d'évaluation. |
| **07** | **TRVS** | Taux de Réalisation et de Validation des Séances d'apprentissage. |
| **08** | **Couverture Compétences** | Pourcentage de micro-compétences évaluées et validées par groupe et par filière. |
| **09** | **Planification UA** | Taux de couverture temporelle et complétion des Unités d'Apprentissage planifiées. |

---

## 4. Particularités UI & Filtres
- **Tableaux de bord dynamiques :** Intégration avec `PkgWidgets` pour l'injection des cards statistiques sur la page d'accueil d'administration et l'espace formateur.
