Voici l'ordre de création des tables pour le package **PkgRealisationTache**, en tenant compte des relations définies :

1. **EtatRealisationTache** (car il est référencé par RealisationTache)
2. **LabelRealisationTache** (indépendant mais utilisé dans Tache)
3. **PrioriteTache** (utilisé dans Tache)
4. **Tache** (car il est référencé par plusieurs entités, notamment RealisationTache et DependanceTache)
5. **TypeDependanceTache** (car utilisé dans DependanceTache)
6. **DependanceTache** (car il fait référence à Tache et TypeDependanceTache)
7. **RealisationTache** (car il fait référence à Tache, EtatRealisationTache et RealisationProjet)
8. **HistoriqueRealitionTache** (car il dépend de RealisationTache)
9. **CommentaireRealisationTache** (car il fait référence à RealisationTache, Apprenant et Formateur)

Cet ordre permet de respecter les relations entre les tables, en commençant par celles qui ne dépendent de rien et en progressant vers celles qui ont des clés étrangères.