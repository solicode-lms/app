J’ai comparé les deux listes en prenant **`MatriculeEtudiant`** comme identifiant de comparaison.

Résultat : **7 nouveaux apprenants** sont présents dans la Liste 2 et absents de la Liste 1. La Liste 1 contient 100 apprenants, contre 107 dans la Liste 2.

| # | Matricule         | Nom       | Prénom   | Sexe | Formation          | Code   |
| - | ----------------- | --------- | -------- | ---- | ------------------ | ------ |
| 1 | **2007090200374** | MRINI     | BADR     | H    | Développeur Web    | DWB101 |
| 2 | **2008050800263** | ZAROUAL   | AHMED    | H    | Développeur Web    | DWB104 |
| 3 | **2000081700178** | EL MOUDEN | IMANE    | F    | Développeur Web    | DWB101 |
| 4 | **2006032500343** | ASAAS     | SOUKAYNA | F    | Développeur Mobile | DMB101 |
| 5 | **2009022700145** | ACHERKOUK | BAKR     | H    | Développeur Web    | DWB101 |
| 6 | **2007090300358** | LAKHBAL   | ZOUBAIR  | H    | Développeur Web    | DWB104 |
| 7 | **2006071300396** | ASSBAI    | DOUAE    | F    | Développeur Web    | DWB103 |

Les nouveaux enregistrements apparaissent dans la Liste 2 avec des dates d’inscription du **07/09/2026**.   

### À ajouter dans la base

```text
2007090200374 | MRINI       | BADR     | DWB101
2008050800263 | ZAROUAL     | AHMED    | DWB104
2000081700178 | EL MOUDEN   | IMANE    | DWB101
2006032500343 | ASAAS       | SOUKAYNA | DMB101
2009022700145 | ACHERKOUK   | BAKR     | DWB101
2007090300358 | LAKHBAL     | ZOUBAIR  | DWB104
2006071300396 | ASSBAI      | DOUAE    | DWB103
```

**Conclusion : il faut ajouter exactement ces 7 apprenants.**
