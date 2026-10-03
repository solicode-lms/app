# Capacité : viewFormGroups & viewFormFieldGroup

## Description
Ces métadonnées permettent d'organiser les champs d'un formulaire (et potentiellement de la vue Show) en groupes structurés (onglets, cards, ou sections) directement via Gapp.

## Emplacement
1. **`viewFormGroups`** (Niveau Entité / Modèle)
2. **`viewFormFieldGroup`** (Niveau Champ / Propriété)

## Format : `viewFormGroups` (Sur l'Entité)
Doit contenir un objet JSON décrivant chaque groupe avec son identifiant, son icône, son label et son ordre d'affichage.
```json
{
  "identifiant_groupe": {
    "icon": "fas fa-icon-name",
    "label": "Titre du Groupe",
    "order": 1
  }
}
```

## Format : `viewFormFieldGroup` (Sur chaque Champ)
Doit contenir une simple chaîne de caractères correspondant à l'identifiant du groupe défini au niveau de l'entité.
```json
"identifiant_groupe"
```

## Utilisation (Workflow Gapp)
1. **Interface Gapp > Entité** : Ajouter la métadonnée `viewFormGroups` (Type : JSON).
2. **Interface Gapp > Champs de l'Entité** : Pour chaque champ, ajouter la métadonnée `viewFormFieldGroup` (Type : String) en renseignant la clé du groupe (ex: `informations_principales`).
3. Gapp regénèrera les vues de formulaire (`_fields.blade.php`, etc.) en englobant les champs dans les structures UI correspondantes.
