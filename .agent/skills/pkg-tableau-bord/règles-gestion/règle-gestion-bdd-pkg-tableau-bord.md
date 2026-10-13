# Règle de Gestion : Modèle de Données PkgTableauBord

## 1. Vue d'Ensemble & Périmètre Architectural

Le module `PkgTableauBord` s'appuie sur une double architecture :
1. **L'exploitation des données transactionnelles** des modules métiers existants (`PkgRealisationTache`, `PkgApprentissage`, `PkgCreationProjet`, `PkgCompetences`).
2. **Des tables et colonnes d'enrichissement dédiées** permettant des calculs statistiques instantanés et la traçabilité des indicateurs de recherche de l'Annexe M.

---

## 2. Tables Dédiées du Module PkgTableauBord

### A. `log_activite_formateurs`
Historise l'activité administrative et logistique des formateurs avec verrou d'inactivité à 5 minutes pour calculer $C_{\text{logistique}}$.
- `id` : Bigint unsigned (PK).
- `formateur_id` : Bigint unsigned (FK $\rightarrow$ `formateurs.id`).
- `type_activite` : Enum / Varchar (`PILOTAGE_KANBAN`, `GESTION_FILE_FIFO`, `EXPORT_BORDEREAU`).
- `duree_secondes` : Int unsigned (secondes d'interaction active).
- `date_activite` : Date.
- `semaine_annee` : Varchar(10) (ex: `2026-W42`, indexé).
- `reference` : Varchar (unique).

### B. `remediations`
Consigne les interventions d'étayage et de déblocage du formateur après le Live Coding des pairs ou en post-gating.
- `id` : Bigint unsigned (PK).
- `realisation_tache_id` : Bigint unsigned (FK $\rightarrow$ `realisation_taches.id`).
- `formateur_id` : Bigint unsigned (FK $\rightarrow$ `formateurs.id`).
- `type_remediation` : Varchar(50) (`INDIVIDUELLE`, `TRINOME_SOUTIEN`, `ENVIRONNEMENT_DEV`).
- `diagnostic` : Text (cause identifiée du blocage).
- `est_resolu` : Boolean (default false).
- `reference` : Varchar (unique).

### C. `bordereaux_officiels`
Archive et certifie la génération automatique des notes de Contrôle Continu (CC sur 20) et d'Examen de Fin de Module (EFM sur 40).
- `id` : Bigint unsigned (PK).
- `module_id` : Bigint unsigned (FK $\rightarrow$ `modules.id`).
- `groupe_id` : Bigint unsigned (FK $\rightarrow$ `groupes.id`).
- `annee_formation_id` : Bigint unsigned (FK $\rightarrow$ `annee_formations.id`).
- `nb_apprenants` : Int unsigned.
- `taux_conformite_calcul` : Decimal(5,2).
- `statut` : Varchar(30) (`VALIDE_SANS_ANOMALIE`, `DIVERGENCE_DETECTEE`).
- `genere_par_user_id` : Bigint unsigned (FK $\rightarrow$ `users.id`).
- `reference` : Varchar (unique).

---

## 3. Enrichissements des Tables SoliLMS Existantes

Pour rendre possible les calculs sans requêtes d'historique coûteuses :

### Table `affectation_projets`
- `date_lancement` : Timestamp NULL (Activation en direct du sprint par le formateur pour $T_{\text{amorçage}}$).
- `date_fin_live_coding_collectif` : Timestamp NULL (Fin de la séance au tableau pour le seuil des $+10$ min).

### Table `realisation_taches`
- `date_premiere_soumission` : Timestamp NULL (Premier passage vers `TO_APPROVE` pour $T_{\text{amorçage}}$).
- `date_entree_file` : Timestamp NULL (Entrée dans la file FIFO au statut `READY_FOR_LIVE_CODING`).
- `date_debut_live_coding` : Timestamp NULL (Prise en charge formateur `IN_LIVE_CODING`).
- `delai_attente_minutes` : Int unsigned NULL (Calculé pour $T_{\text{file}}$).
- `nombre_revisions` : Int unsigned DEFAULT 0 (Incrémenté à chaque rejet pour $F_{\text{révision}}$).
- `session_live_coding_id` : Bigint unsigned NULL (Regroupement des passages 4x4).

### Table `realisation_ua_prototypes`
- `nombre_tentatives` : Int unsigned DEFAULT 0 (Sessions de Live Coding Prototype subies).
- `est_valide_premier_essai` : Boolean NULL (Validation directe à la tentative 1 pour $R_{\text{gating}}$).

### Table `realisation_modules`
- `note_cc_officielle` : Decimal(4,2) NULL (Moyenne des prototypes N2 sur 20).
- `note_efm_officielle` : Decimal(4,2) NULL (Application de la règle 65/35 sur 40).
- `statut_docimologique` : Varchar(30) DEFAULT 'EN_COURS'.

### Table `workflow_taches`
- Nouvel état : `BESOIN_REVISION` (*"Besoin de révision (+10 min)"*, couleur danger/rose).
