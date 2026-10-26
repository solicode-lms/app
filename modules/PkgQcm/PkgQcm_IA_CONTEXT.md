# Contexte IA du Module : PkgQcm

## 1. Responsabilité Métier
Le package **PkgQcm** gère la conception, la banque de questions et le paramétrage des évaluations sous forme de questionnaires à choix multiples :
1. **Gestion des Questionnaires :** Définition des QCM (`Qcm`), durée limite, barème et rattachement à un module ou projet.
2. **Banque de Questions & Propositions :** Questions à choix unique ou multiple (`Question`) et propositions de réponses (`PropositionReponse`) avec pondération.
3. **Affectation :** Rattachement d'un QCM à un projet pédagogique (`AffectationQcmProjet`).
4. **Suivi des Réalisations :** Enregistrement des sessions d'examen (`RealisationQcm`) et des réponses individuelles (`ReponseQcm`).

---

## 2. Modèles & Relations Principales

### `Qcm` (Table : `qcms`)
- **Relations :** `questions`, `affectationQcmProjets`, `formateur`.

### `Question` (Table : `questions`)
- **Relations :** `qcm`, `propositionReponses`, `chapitre`.

### `PropositionReponse` (Table : `proposition_reponses`)
- **Champs clés :** `texte`, `est_correcte`, `valeur`, `question_id`.

### `RealisationQcm` (Table : `realisation_qcms`)
- **Relations :** `qcm`, `apprenant`, `etatRealisationQcm`, `reponseQcms`.

---

## 3. Services & Logique Métier
- **Calcul des Notes :** Calcul automatique de la note globale selon les réponses cochées et synchronisation asynchrone via Observer et `JobManager`.
