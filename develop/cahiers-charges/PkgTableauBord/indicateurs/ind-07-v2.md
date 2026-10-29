Vous avez raison : il faut mesurer la réalisation effective des projets de sprint, et non comparer les notes N2 et N3.

## 1. Indicateur 07 — Impact du Live Coding sur la réalisation des projets

FICHE D'IDENTITÉ

# Taux de réalisation validée par sprint

Code : TRVS

Indicateur de résultat

Objectif : mesurer la proportion des réalisations de projets de sprint qui atteignent l'état « Validé », afin d'étudier l'évolution de la capacité des apprenants à mener leurs projets à terme après les activités de Live Coding.

Principe : les projets peuvent être réalisés avec l'IA ou sans l'IA. L'indicateur mesure le résultat de la réalisation, pas l'outil utilisé ni la note obtenue au Prototype.

## 2. Que doit-on compter ?

Pour chaque sprint, on relève le nombre de réalisations de projet qui ont atteint l'état `Validé`.

| État de la réalisation            | Comptabilisée comme validée ? |
| --------------------------------- | ----------------------------- |
| Validé                            | Oui                           |
| En cours                          | Non                           |
| Terminé, en attente de validation | Non                           |
| Non terminé                       | Non                           |

Le comptage doit porter sur les réalisations attendues dans le sprint, avec une règle cohérente pour identifier chaque réalisation et éviter les doublons.

## 3. Comment calculer l'indicateur ?

Il est préférable de mesurer à la fois le nombre de projets validés et leur proportion, car les sprints peuvent avoir des nombres de projets attendus différents.

Nombre de réalisations validées :

\\[ N\_{\text{validées}}= \text{Nombre de réalisations dont l'état est « Validé »} \\]

Taux de réalisation validée par sprint :

\\[ \boxed{ TRVS= \frac{N\_{\text{validées}}}{N\_{\text{attendues}}} \times100 } \\]

`N_attendues` représente le nombre total de réalisations prévues et attribuées pour le sprint, hors réalisations officiellement annulées selon les règles de SoliLMS.

## 4. Comment mesurer l'évolution après le Live Coding ?

Pour étudier l'effet potentiel du Live Coding, comparez les résultats de réalisation avant et après cette activité pédagogique, en utilisant les mêmes règles de comptage.

Prenons cet exemple :

| Période              | Réalisations attendues | Réalisations validées | TRVS   |
| -------------------- | ---------------------- | --------------------- | ------ |
| Avant le Live Coding | 24                     | 15                    | 62,5 % |
| Après le Live Coding | 30                     | 24                    | 80 %   |

L'évolution observée est :

\\[ \Delta TRVS=TRVS\_{\text{après}}-TRVS\_{\text{avant}} \\]

\\[ \boxed{\Delta TRVS=80-62{,}5=+17{,}5} \\]

Le taux de réalisation validée a donc augmenté de 17,5 points de pourcentage dans cet exemple.

Cette évolution est compatible avec une amélioration de la réalisation des projets après le Live Coding. Elle ne prouve toutefois pas, à elle seule, que le Live Coding est la cause de cette amélioration : la difficulté des projets, l'expérience acquise et d'autres activités pédagogiques peuvent aussi intervenir.

## 5. Comment intégrer cela dans SoliLMS ?

Pour chaque sprint, le tableau de bord du responsable de formation pourrait afficher le nombre de réalisations validées, le nombre total de réalisations attendues et le TRVS. Un graphique montrera ensuite l'évolution de ce taux d'un sprint à l'autre.

Les trois questions de votre évaluation — localiser le code, l'expliquer et le modifier — peuvent rester des vérifications qualitatives non notées. Elles permettent de documenter la maîtrise du code, mais n'entrent pas dans le calcul du TRVS.

Le choix méthodologique à retenir : le TRVS devient votre indicateur principal de résultat. Pour parler spécifiquement de l'impact du Live Coding, il faudra comparer son évolution avec une situation de référence antérieure ou un groupe de comparaison pertinent.