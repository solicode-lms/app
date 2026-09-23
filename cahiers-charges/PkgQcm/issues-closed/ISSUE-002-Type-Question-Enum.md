# Issue : Typage du champ 'type' de Question (ISSUE-002) [FERMÉ]

## Contexte
Actuellement, dans le diagramme de classe (`pkg_qcm_classes.mmd`), l'attribut `type` de l'entité `Question` est défini comme un simple `string`.
Cependant, pour garantir l'intégrité des données et limiter les choix possibles (ex: `choix_unique`, `choix_multiple`, `vrai_faux`), il serait plus approprié d'utiliser une énumération (Enum).

## Skills Requis pour la Réalisation
- `app-model` : Pour créer l'Enum `QuestionTypeEnum` en PHP, déterminer son emplacement dans l'architecture, et ajouter le cast Enum dans le modèle Eloquent `Question`. (Note : la base de données n'est pas modifiée).
- `app-blade` : Pour modifier le formulaire de création/édition de la question afin de remplacer le champ texte libre par une liste déroulante (`select`) basée sur l'Enum. L'architecture des vues Gapp (et le dossier `custom/`) devra être respectée selon la capacité du skill.

## Modifications Demandées dans le Modèle (À réaliser dans le code)

1. **Création d'un Enum `QuestionTypeEnum`** :
   - Définir une énumération en PHP reprenant les types de questions supportés (ex: `ChoixUnique`, `ChoixMultiple`, `VraiFaux`).
   - L'emplacement de cet Enum doit être déterminé par le skill `app-model`.

2. **Mise à jour de l'entité `Question`** :
   - La table en base de données conserve son type `string`.
   - Utilisation du cast Enum dans le modèle Eloquent `Question` pour faire la conversion string <-> Enum automatiquement.

3. **Mise à jour de la Présentation (Vues Blade)** :
   - Modifier le champ `type` dans les formulaires d'édition/création (dossier `custom/forms/` de Gapp) pour qu'il affiche dynamiquement les valeurs de l'Enum sous forme de menu déroulant.

## Actions Post-Développement
Une fois ces modifications implémentées dans le code source :
1. Le diagramme de classe `pkg_qcm_classes.mmd` devra être mis à jour pour remplacer `+type: string` par `+type: enum`.
2. Ce ticket pourra être fermé.
