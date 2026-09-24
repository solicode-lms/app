# Issue : Champs manquants pour la gestion de l'évaluation QCM -> UA Prototype (EVOL-002)

## Contexte
Afin de respecter les règles de gestion d'évaluation des QCM validés par le formateur, les notes doivent être remontées vers les réalisations des prototypes de l'Unité d'Apprentissage (UA).
Cependant, la structure actuelle de la base de données ne supporte pas complètement ces règles métier. Il est donc nécessaire d'ajouter de nouveaux champs pour mémoriser la note issue du QCM et pour configurer le mode de remontée de cette note.

## Modifications Demandées par Composant

### 1. Base de données (Migrations) & Métadonnées
- **Entité `RealisationUaPrototype`** :
  - Ajouter la colonne `note_qcm` (type float ou decimal, nullable).
  - Ajouter la colonne `barem_qcm` (type float ou decimal, nullable).
- **Entité `AffectationQcmProjet`** :
  - Ajouter la colonne `saise_automatique_note_qcm` (type boolean, défaut : `false`).
- Mettre à jour le fichier de configuration Gapp pour que ces champs soient pris en compte (notamment dans les `$fillable` des modèles Base).

### 2. Logique Métier (Services)
- **Cascade de Notes** : Lors de la soumission/validation d'une `RealisationQcm`, calculer la note.
- Enregistrer cette note dans la `RealisationUaPrototype` liée via `note_qcm` et `barem_qcm`.
- Si l'`AffectationQcmProjet` associée a `saise_automatique_note_qcm = true`, la note calculée doit également être affectée à l'attribut global `note` de la `RealisationUaPrototype`.

## Skills Requis pour la Réalisation
Pour développer cette issue, l'agent devra impérativement utiliser les skills suivants :
- `app-migration` : Pour créer les migrations de modification des deux tables.
- `sys-gapp` : Pour ajuster les métadonnées de l'application et regénérer les modèles.
- `app-model` : Pour vérifier que les attributs sont bien ajoutés dans les modèles (`fillable`).
- `app-service` / `pkg-qcm` : Pour implémenter la logique de transfert des notes dans les hooks métier.

## Actions Post-Développement
Une fois ces modifications codées et intégrées dans la base de données de l'application :
1. Le diagramme de classe Mermaid de PkgQcm et PkgApprentissage devra être mis à jour pour refléter ces nouvelles colonnes.
2. Ce ticket pourra ensuite être clôturé.
