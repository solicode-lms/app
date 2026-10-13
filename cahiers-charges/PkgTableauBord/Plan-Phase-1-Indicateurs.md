# Plan d'Action — Phase 1 : Spécification des Indicateurs & Audit SoliLMS

## 🎯 Objectif de la Phase
Déterminer la modélisation, le mode de calcul et les prérequis techniques de chaque indicateur de tableau de bord dans Solicode LMS. Conformément aux directives, **chaque indicateur fait l'objet d'un fichier de spécification unitaire** dans le dossier `indicateurs/`, détaillant son calcul mathématique, les packages SoliLMS impactés et les modifications requises dans le système existant.

---

## 🔍 1. Méthodologie d'Analyse (Utilisation des Skills Métier)

Pour garantir la compatibilité avec la version actuelle de Solicode LMS sans présomption erronée :
1. **Audit de l'existant via les skills `pkg-*`** :
   - `pkg-realisation-tache` : Audit des transitions et statuts de tâches actuels (`A faire`, `En cours`, `En validation`, `Terminé`).
   - `pkg-apprentissage` : Audit du modèle de notation, des Unités d'Apprentissage (UA) et des cascades de calcul.
   - `pkg-creation-projet` : Audit des affectations de projets, sprints et prototypes.
   - `pkg-qcm` & `pkg-passer-qcm` : Audit des évaluations formatives.
   - `app-db-savoir` : Exploration des tables et colonnes réelles dans `db.sql`.
2. **Identification des adaptations requises dans SoliLMS** :
   - Ajout de nouveaux états dans les machines d'états (ex: état `Besoin de révision` pour les tâches bloquées à $+10$ min post-Live Coding).
   - Ajout d'horodatages critiques (ex: timestamp d'arrivée dans la file FIFO, horodatage de début de soutenance Live Coding).
   - Ajout de colonnes d'archivage ou de calcul d'agrégats.

---

## 📑 2. Liste des Fichiers Unitaires d'Indicateurs à Produire

Un dossier dédié `cahiers-charges/PkgTableauBord/indicateurs/` est créé. Chaque fichier respecte la structure standard :

```
cahiers-charges/PkgTableauBord/indicateurs/
├── ind-01-temps-amorcage.md
├── ind-02-taux-gating-premier-essai.md
├── ind-03-boucles-revision.md
├── ind-04-charge-logistique.md
├── ind-05-delai-file-attente.md
├── ind-06-taux-intervention-remediation.md
├── ind-07-integrite-ia-pearson.md
├── ind-08-couverture-pedagogique.md
└── ind-09-conformite-conversion-65-35.md
```

### Détail des 9 indicateurs opérationnels :

| Fichier | Indicateur | Axe & Hypothèse | Packages SoliLMS Ciblés | Adaptation Clé à Déterminer |
| :--- | :--- | :---: | :--- | :--- |
| `ind-01-temps-amorcage.md` | $T_{\text{amorçage}}$ | H1 (Apprenant) | `PkgRealisationTache`, `PkgFormation` | Horodatage activation sprint $\rightarrow$ 1ère tâche Niveau 1 *En validation*. |
| `ind-02-taux-gating-premier-essai.md` | $R_{\text{gating}}$ | H1 (Apprenant) | `PkgRealisationTache`, `PkgApprentissage` | Traçabilité du rang d'essai (`essai = 1`) lors du Live Coding Prototype ($N2$). |
| `ind-03-boucles-revision.md` | $F_{\text{révision}}$ | H1 (Apprenant) | `PkgRealisationTache` | Comptabilisation des transitions d'historique vers *Révision nécessaire*. |
| `ind-04-charge-logistique.md` | $C_{\text{logistique}}$ | H2 (Formateur) | `PkgAutorisation`, `PkgTableauBord` | Mesure du temps passé sur interfaces de pilotage (avec seuil d'inactivité 5 min). |
| `ind-05-delai-file-attente.md` | $T_{\text{file}}$ | H2 (Formateur) | `PkgRealisationTache` | Timestamp FIFO entrée file $\rightarrow$ début effectif Live Coding formateur. |
| `ind-06-taux-intervention-remediation.md` | $T_{\text{intervention}}$ | H2 (Formateur) | `PkgRealisationTache` | Détection automatique $+10$ min post-Live Coding et statut *Besoin de révision*. |
| `ind-07-integrite-ia-pearson.md` | $r$ (Pearson) | H3 (Institution) | `PkgApprentissage`, `PkgProjets` | Corrélation statistique notes $N2$ (surveillé 65%) vs $N3$ (projet IA 35%). |
| `ind-08-couverture-pedagogique.md` | $\text{ICP}$ | H3 (Institution) | `PkgCompetences`, `PkgFormation` | Pourcentage de micro-compétences validées sur la cohorte vs référentiel. |
| `ind-09-conformite-conversion-65-35.md` | $\tau_{\text{conformité}}$ | H3 (Institution) | `PkgApprentissage` | Algorithme de génération des bordereaux CC (sur 20) et EFM (sur 40). |

---

## 📋 3. Structure Type d'une Fiche Indicateur

Chaque fichier `ind-XX-[nom].md` devra obligatoirement contenir :
1. **Fiche d'identité** : Symbole, Unité, Objectif didactique / managérial, Acteur cible.
2. **Formule mathématique exacte** : Modélisation formelle issue de l'Annexe M.
3. **Audit de l'existant SoliLMS** :
   - Tables et champs existants utilisés (vérifiés via `app-db-savoir` et skills `pkg-*`).
   - Écarts constatés (champs absents, transitions d'états manquantes).
4. **Modifications à apporter à SoliLMS** :
   - Modifications BDD nécessaires (migrations à prévoir pour le Sprint 1).
   - Modifications Métier (services à enrichir, listeners d'événements).
5. **Niveaux d'agrégation** :
   - Formule au niveau Individuel (Apprenant).
   - Formule au niveau Groupe / Classe (Formateur).
   - Formule au niveau Établissement (Responsable).
6. **Seuils d'Alerte & Déclencheurs d'Action** : Définition des seuils vert / orange / rouge et actions directes associées.

---

## ✅ 4. Livrables & Validation de la Phase 1
- [ ] Dossier `indicateurs/` créé.
- [ ] 9 fichiers de spécification unitaire rédigés et validés avec les skills `pkg-*`.
- [ ] Matrice de synthèse des impacts consolidée pour le Sprint 1 (BDD / Migrations).
