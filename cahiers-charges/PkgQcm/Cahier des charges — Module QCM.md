# Cahier des charges — Module QCM

## 1. Module

**Nom :** `PkgQCM`

**Objectif :** permettre au formateur de gérer une banque de questions et des QCM, d'affecter un QCM à un groupe, de faire passer le QCM aux apprenants et, après validation, d'utiliser la note obtenue pour alimenter la réalisation d'une UA.

---

## 2. Périmètre

Le module couvre :

* banque de questions ;
* classement des questions par UA ;
* création et modification des QCM ;
* import de QCM en `JSON` ou `CSV` ;
* sélection de questions depuis la banque ;
* association d'un QCM à plusieurs UA ;
* une UA principale par question ;
* barème des questions ;
* durée limitée ou libre ;
* affectation d'un QCM à un groupe ;
* création d'une réalisation QCM pour chaque apprenant concerné ;
* passation et correction automatique ;
* une seule tentative ;
* calcul d'une note par UA ;
* validation par le formateur ;
* transfert de la note vers la réalisation de l'UA.

Le module ne gère pas la définition des UA, des projets, des apprenants ou des réalisations d'UA.

---

## 3. Acteurs

### Formateur

Peut :

* gérer la banque de questions ;
* créer un QCM ;
* importer un QCM ;
* associer des questions aux UA ;
* affecter un QCM à un groupe ;
* consulter les réalisations QCM ;
* valider les résultats ;
* autoriser le transfert des notes vers les réalisations d'UA.

### Apprenant

Peut :

* consulter ses réalisations QCM ;
* passer un QCM ;
* répondre aux questions ;
* obtenir son résultat selon les règles de visibilité.

---

## 4. Banque de questions

La banque centralise les questions disponibles.

Une question appartient à une **UA principale**.

```text
Banque de questions
 ├── UA.122.11
 │    ├── Question 1
 │    ├── Question 2
 │    └── Question 3
 ├── UA.122.12
 │    ├── Question 4
 │    └── Question 5
 └── UA.122.21
      └── Question 6
```

L'application doit permettre au formateur :

* d'ajouter une question ;
* de modifier une question ;
* de supprimer ou désactiver une question ;
* de rechercher des questions ;
* de filtrer les questions par UA ;
* de sélectionner des questions pour construire un QCM.

---

## 5. QCM

Un QCM est composé de questions provenant de la banque.

Un QCM peut évaluer plusieurs UA :

```text
QCM
 ├── Question → UA.122.11
 ├── Question → UA.122.11
 ├── Question → UA.122.12
 └── Question → UA.122.21
```

Une question évalue **une seule UA principale**.

Ainsi :

```text
QCM
   ↓
plusieurs questions
   ↓
plusieurs UA
```

Le formateur peut également fournir/importer les questions d'un QCM par UA dans un fichier `JSON` ou `CSV`.

---

## 6. Barème

Chaque question possède une note.

**Valeur par défaut : `1`.**

Le QCM peut donc être composé, par exemple, de :

```text
UA.122.11
  Question 1 → 1 point
  Question 2 → 1 point
  Question 3 → 2 points

UA.122.12
  Question 4 → 1 point
  Question 5 → 1 point
```

La note obtenue pour une UA est calculée selon le **barème de cette UA**.

Principe :

```text
Points obtenus sur les questions de l'UA
             ↓
        normalisation
             ↓
Note de l'UA selon son barème
```

Le barème de référence de l'UA est celui défini dans `PkgCompetences`.

---

## 7. Import JSON / CSV

Le formateur peut importer un QCM depuis :

* `JSON` ;
* `CSV`.

Le fichier doit permettre d'identifier, pour chaque question :

* le contenu de la question ;
* ses propositions ;
* la ou les réponses correctes selon le type de question retenu ;
* l'UA principale ;
* la note de la question, avec `1` par défaut.

L'import doit vérifier :

* la structure du fichier ;
* les données obligatoires ;
* l'existence des UA ;
* la cohérence des questions ;
* la validité des réponses ;
* le barème.

Une erreur d'import doit être signalée avant création du QCM.

---

## 8. Affectation à un groupe

Le formateur peut affecter un QCM à un groupe.

```text
Formateur
   ↓
QCM
   ↓
Groupe
   ↓
Apprenants concernés
```

L'affectation crée pour chaque apprenant concerné une :

**`RealisationQCM`**

La réalisation QCM représente le travail d'un apprenant sur un QCM donné.

---

## 9. Réalisation QCM

`RealisationQCM` doit permettre de suivre au minimum :

* apprenant ;
* QCM ;
* date de début ;
* date de fin ;
* état ;
* score obtenu ;
* résultat par UA ;
* validation du formateur.

Cycle fonctionnel :

```text
Affectation
   ↓
RealisationQCM créée
   ↓
Passation
   ↓
Correction
   ↓
Résultat
   ↓
Validation formateur
   ↓
Transfert éventuel
```

---

## 10. Passation

L'apprenant peut passer une réalisation QCM.

Deux modes sont possibles :

```text
Durée limitée
Durée libre
```

En durée limitée, la durée configurée doit être respectée.

En durée libre, aucune limite de temps n'est appliquée.

### Tentatives

Une réalisation QCM autorise :

**une seule tentative.**

Une tentative terminée ne peut pas être recommencée.

---

## 11. Correction

Après la passation :

* les réponses sont enregistrées ;
* les réponses sont corrigées automatiquement ;
* les points sont calculés ;
* le résultat global est calculé ;
* une note est calculée pour chaque UA.

Exemple :

```text
QCM
 ├── UA.122.11 → 8 points obtenus / 10
 ├── UA.122.12 → 5 points obtenus / 5
 └── UA.122.21 → 6 points obtenus / 8
```

Puis :

```text
Résultat QCM
 ├── Note UA.122.11
 ├── Note UA.122.12
 └── Note UA.122.21
```

---

## 12. Validation

Le résultat automatique n'est pas directement transféré vers la réalisation d'UA.

Le cycle est obligatoire :

```text
Correction automatique
        ↓
Résultat QCM
        ↓
Validation formateur
        ↓
Note validée
```

Le formateur doit valider la réalisation QCM avant tout transfert.

---

## 13. Transfert vers la réalisation d'UA

Après validation de `RealisationQCM`, la note d'une UA peut être transférée vers la réalisation correspondante.

Cible :

```text
PkgApprentissage
└── RealisationUAProjet
```

Principe :

```text
RealisationQCM
      ↓
Résultat UA
      ↓
Validation formateur
      ↓
RealisationUAProjet
      ↓
note de réalisation UA
```

Le transfert doit identifier la réalisation UA correspondant à :

```text
Apprenant
+
UA
+
Projet concerné
```

Le module QCM ne crée pas de nouvelle structure concurrente à `RealisationUAProjet`.

---

## 14. Modules concernés

| Responsabilité         | Module                                        |
| ---------------------- | --------------------------------------------- |
| Formateur              | `PkgFormation`                                |
| Groupe / apprenant     | `PkgApprenants`                               |
| UA et barème UA        | `PkgCompetences`                              |
| Banque de questions    | `PkgQCM`                                      |
| QCM                    | `PkgQCM`                                      |
| Passation              | `PkgQCM`                                      |
| Réalisation QCM        | `PkgQCM`                                      |
| Correction / résultats | `PkgQCM`                                      |
| Validation QCM         | `PkgQCM`                                      |
| Réalisation UA         | `PkgApprentissage`                            |
| Réalisation UA Projet  | `PkgApprentissage`                            |
| Projet                 | `PkgCreationProjet` / `PkgRealisationProjets` |

---

## 15. Objets métier principaux

```text
PkgQCM
├── BanqueQuestion
├── QuestionQCM
├── PropositionQCM
├── QCM
├── QCMQuestion
├── RealisationQCM
├── ReponseQCM
└── ResultatUAQCM
```

Relations externes principales :

```text
QuestionQCM → UniteApprentissage
QCM → Formateur
RealisationQCM → QCM
RealisationQCM → Apprenant
RealisationQCM → ResultatUAQCM
ResultatUAQCM → UniteApprentissage
ResultatUAQCM → RealisationUAProjet
```

---

## 16. Règles métier essentielles

**RM01.** Une question appartient à une UA principale.

**RM02.** Un QCM peut évaluer plusieurs UA.

**RM03.** Une question possède une note, `1` par défaut.

**RM04.** La note d'une UA est calculée à partir des questions rattachées à cette UA et selon le barème de l'UA.

**RM05.** Une question peut être réutilisée dans plusieurs QCM.

**RM06.** Un QCM peut être affecté à un groupe.

**RM07.** Une affectation crée une `RealisationQCM` pour chaque apprenant concerné.

**RM08.** Une `RealisationQCM` ne peut être passée qu'une seule fois.

**RM09.** Une réalisation QCM doit être corrigée avant validation.

**RM10.** Seul le formateur valide le résultat.

**RM11.** Une note ne peut être transférée vers une réalisation UA qu'après validation du formateur.

**RM12.** Le module QCM ne modifie pas la définition d'une UA.

**RM13.** Le module QCM ne remplace pas `RealisationUAProjet`.

**RM14.** Les fichiers JSON/CSV importés doivent être validés avant création.

---

## 17. Traçabilité

```text
Besoin
 ↓
QCM
 ↓
Question
 ↓
UA principale
 ↓
Réponse apprenant
 ↓
Résultat UA
 ↓
Validation formateur
 ↓
RealisationUAProjet
```

La traçabilité doit permettre de retrouver comment chaque note d'UA a été obtenue.

---

## 18. Critère d'acceptation principal

Le scénario suivant doit fonctionner :

```text
Formateur
→ crée/import un QCM
→ sélectionne des questions de plusieurs UA
→ affecte le QCM à un groupe
→ SoliLMS crée les RealisationQCM
→ apprenant passe une seule tentative
→ correction automatique
→ calcul d'une note par UA
→ formateur valide
→ note validée transférable vers RealisationUAProjet
```

## 19. Points restant à préciser

Avant le modèle objet définitif, il reste notamment à fixer :

* types exacts de questions ;
* règles pour réponses multiples ;
* comportement en cas d'absence de réponse ;
* visibilité du résultat pour l'apprenant ;
* comportement lorsqu'une `RealisationUAProjet` possède déjà une note ;
* règles exactes de conversion des points vers le barème de l'UA.
