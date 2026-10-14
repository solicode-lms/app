Oui, cette approche est plus pertinente pour SoliLMS. Vous souhaitez mesurer si la planification des Unités d'Apprentissage (UA) permet de préparer les évaluations nécessaires au calcul des notes de fin d'année.

Je vous recommande donc de remplacer le TDBE par un indicateur lié à la couverture des UA dans la planification des formations (`SessionFormation`).

## 1. Indicateur 09 — Taux de couverture de planification des UA

INDICATEUR INSTITUTIONNEL

# Taux de couverture de planification évaluative des UA

Code : TCP-UA

Unité : %

Objectif : mesurer la proportion des Unités d'Apprentissage prévues dans le programme qui sont couvertes par une planification de formation incluant les évaluations nécessaires.

Source des données : programme de formation, Unités d'Apprentissage et `SessionFormation`.

Utilité : anticiper les UA pour lesquelles une évaluation ou une note risque de manquer à la fin de l'année.

## 2. Comment calculer cet indicateur ?

Chaque programme comporte un ensemble d'UA. Pour obtenir les notes du Contrôle Continu, les évaluations prévues pour ces UA doivent être organisées dans les sessions de formation.

La formule proposée est :

\\[ \boxed{ TCP\text{-}UA = \frac{ \text{Nombre d'UA couvertes par une planification évaluative} }{ \text{Nombre total d'UA obligatoires du programme} } \times 100 } \\]

Une UA est considérée comme couverte par une planification évaluative si elle possède au minimum une `SessionFormation` pertinente, prévue pour le bon groupe et la bonne année de formation, avec l'évaluation nécessaire à la production de la note N2 planifiée.

Il faut compter chaque UA une seule fois, même si plusieurs sessions lui sont consacrées.

### Exemple concret

Un programme comporte 20 UA obligatoires.

| Situation                                   | Nombre d'UA |
| ------------------------------------------- | ----------- |
| UA obligatoires dans le programme           | 20          |
| UA disposant d'une planification évaluative | 17          |
| UA sans planification évaluative            | 3           |

Le taux est :

\\[ TCP\text{-}UA=\frac{17}{20}\times100 =\boxed{85\\%} \\]

Le responsable de formation sait que 85 % des UA disposent d'une planification permettant de préparer leur évaluation. Les trois UA restantes constituent des points à traiter avant la fin de l'année.

## 3. Pourquoi cet indicateur est utile pour les notes de fin d'année

Le mécanisme que vous souhaitez suivre est le suivant :

Programme de formation

Liste des UA obligatoires

Planification des formations

Chaque UA est associée aux sessions prévues

Planification des évaluations

Une évaluation N2 est programmée pour chaque UA concernée

Réalisation des évaluations et saisie des notes

Les notes N2 sont enregistrées dans SoliLMS

Calcul de la note de fin d'année

Les notes disponibles sont agrégées selon les règles de l'établissement

Cet indicateur intervient donc en amont : il permet de repérer les lacunes dans la planification avant qu'elles ne deviennent des problèmes de disponibilité des notes.

## 4. Attention : la planification ne garantit pas que les notes seront obtenues

Il faut distinguer trois niveaux de suivi.

| Niveau       | Indicateur                          | Question                                                           |
| ------------ | ----------------------------------- | ------------------------------------------------------------------ |
| Prévisionnel | TCP-UA                              | Les UA et leurs évaluations sont-elles planifiées ?                |
| Réalisation  | Taux de réalisation des évaluations | Les évaluations planifiées ont-elles effectivement été réalisées ? |
| Résultat     | Taux de complétude des notes        | Toutes les notes attendues ont-elles été saisies ?                 |

Par exemple, une UA peut être correctement planifiée dans `SessionFormation`, mais la séance peut être annulée ou la note peut ne pas être saisie. Ainsi, un TCP-UA de 100 % indique une couverture complète de la planification évaluative, mais ne prouve pas que toutes les notes seront disponibles à la fin de l'année.

Pour cette raison, je recommande de garder le TCP-UA comme indicateur principal de couverture prévisionnelle, puis de suivre la réalisation des évaluations et la complétude des notes pour confirmer le résultat.

Il faut aussi planifier séparément l'évaluation N3 du projet fil rouge si elle entre dans le calcul final des notes.

## 5. Intégration dans SoliLMS

Dans votre application Laravel, le calcul peut être effectué à partir des données existantes, sous réserve de vérifier les relations réelles entre les modèles :

- le programme définit les UA obligatoires ;
- `SessionFormation` indique les UA planifiées pour un groupe et une période ;
- la planification doit identifier les évaluations N2 nécessaires ;
- les évaluations réalisées et les notes saisies sont ensuite suivies séparément.

Le tableau de bord du responsable pourrait afficher le taux global, le nombre d'UA couvertes et la liste des UA non planifiées.

Ma recommandation finale : adoptez le Taux de couverture de planification évaluative des UA (TCP-UA) pour mesurer si le programme est correctement planifié afin de préparer les notes de fin d'année. C'est un indicateur de prévention et de pilotage institutionnel, plus utile qu'un taux de conformité arithmétique qui reste toujours à 100 %.