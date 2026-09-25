# Cahier des charges — Module QCM

## 1. Description Générale
**Nom :** `PkgQCM`
**Objectif :** Gérer une banque de questions, composer des QCM, affecter ces QCM à des projets, automatiser la correction, et remonter dynamiquement la note vers la réalisation de l'Unité d'Apprentissage (UA) du prototype.

## 2. Acteurs & Périmètre
- **Formateur** : Gère la banque de questions, crée/importe les QCM, définit le paramétrage d'insertion (automatique ou manuel), affecte le QCM via `AffectationQcmProjet`, et valide les résultats.
- **Apprenant** : Passe le QCM (une seule tentative) et consulte son résultat.

## 3. Banque de Questions
Les questions sont centralisées et réutilisables. 
- Une **Question** appartient à une seule **Unité d'Apprentissage (UA)**.
- Une question possède un barème (note par défaut à `1`).

## 4. QCM & Affectation
- Un QCM regroupe des questions (potentiellement de plusieurs UA) via `QuestionQcm`.
- Il peut avoir une durée libre ou limitée.
- Il est affecté à un projet via `AffectationQcmProjet`.
- **Règle** : L'affectation crée automatiquement une `RealisationQcm` pour chaque apprenant du projet concerné.

## 5. Passation & Correction
- **Passation** : Une seule tentative par apprenant.
- **Correction** : Automatique. Le système calcule les points obtenus pour chaque UA présente dans le QCM.
- Les réponses de l'apprenant sont tracées dans `ReponseQcm`. L'état d'avancement est géré via `EtatRealisationQcm`.

## 6. Validation et Transfert de Note
La note globale d'une UA est calculée dynamiquement à partir des réponses (`ReponseQcm`) et selon le barème de l'UA.
- Le cycle de validation est obligatoire : `Correction automatique → Résultat calculé → Validation Formateur`.
- Lors de l'affectation du QCM, une option de configuration détermine le mode d'intégration (`is_insertion_automatique` dans `AffectationQcmProjet`) :
  - **Insertion Automatique** : Après validation, la note est transférée directement dans le champ `note_qcm` de la `RealisationUaPrototype`.
  - **Saisie Manuelle** : Le formateur utilise le résultat QCM comme outil d'aide à la décision pour ajuster lui-même la note globale dans `RealisationUaPrototype`.

## 7. Import
Importation possible de QCM par fichiers `JSON` ou `CSV`.
Validation stricte de la structure, des UA, et des barèmes avant la création en base.

## 8. Objets métier (Reflétant le diagramme)
```text
PkgQcm
├── Qcm
├── Question
├── Proposition
├── QuestionQcm
├── RealisationQcm
├── ReponseQcm
├── AffectationQcmProjet
└── EtatRealisationQcm
```
**Relations Clés :**
- `QuestionQcm` → `Question` (Banque)
- `AffectationQcmProjet` → `Qcm` et `AffectationProjet`
- `RealisationQcm` → `EtatRealisationQcm` et `Apprenant`
- `ReponseQcm` → `RealisationUaPrototype` (lien dynamique pour le calcul de la note)

## 9. Règles métier essentielles
- **RM01.** Une question appartient à 1 seule UA principale.
- **RM02.** Un QCM peut évaluer plusieurs UA.
- **RM03.** Le score d'une UA est calculé à partir des questions de l'UA présentes dans le QCM.
- **RM04.** Une réalisation QCM autorise une seule tentative.
- **RM05.** Une réalisation QCM doit être corrigée automatiquement, puis validée par le formateur.
- **RM06.** L'insertion de la note QCM dans `RealisationUaPrototype` dépend du flag d'affectation (`is_insertion_automatique`).
**RM07.** Le module QCM ne modifie pas la définition d'une UA existante et vient en complément de la réalisation des prototypes.
