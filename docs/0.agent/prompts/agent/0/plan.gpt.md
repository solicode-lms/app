Voici le **plan structuré avec une description** pour chaque partie, afin de guider la rédaction du prompt destiné à créer un agent développeur pour l’application **Sollms** :

---

1. **Contexte du projet**
   Décrire brièvement l’application Sollms : sa finalité, ses utilisateurs cibles, et son positionnement dans l’environnement pédagogique ou professionnel.

2. **Objectifs de l’agent**
   Préciser ce que l’on attend de l’agent : génération de code, assistance à l’architecture, aide à la correction, rédaction de documentation, etc.

3. **Environnement technique**
   Lister les technologies utilisées (ex. : Laravel 11, AdminLTE 3.2.0, MySQL), les normes de codage et les outils de développement ou de déploiement utilisés.

4. **Modules fonctionnels de l’application**
   Fournir une vue d’ensemble des principaux modules (gestion des tâches, utilisateurs, projets, etc.) pour contextualiser les demandes que l’agent devra traiter.

5. **Architecture et conventions**
   Décrire la structure du projet (monolithique, modulaire, MVC...), les conventions internes (naming, services, routes, etc.), et les bonnes pratiques attendues.

6. **Types de tâches attendues de l’agent**
   Dresser la liste des tâches que l’agent devra accomplir : création de migrations, services, composants Blade, tests, endpoints API, etc.

7. **Rôles utilisateurs à prendre en compte**
   Mentionner les rôles métiers existants (formateur, apprenant, administrateur...) et leurs droits, pour que l’agent adapte le code aux besoins des utilisateurs.

8. **Contraintes spécifiques**
   Indiquer les contraintes techniques ou fonctionnelles importantes (support multilingue, accessibilité, compatibilité mobile, etc.).

9. **Format des réponses attendues**
   Définir si les réponses doivent inclure : du code uniquement, des explications, des commentaires, des fichiers séparés, etc.

10. **Exemples d'utilisation (cas d’usage)**
    Donner quelques exemples concrets de requêtes ou prompts types que l’on adressera à l’agent, pour l’aider à calibrer ses réponses.

11. **Limites à ne pas franchir (exclusions)**
    Définir ce que l’agent **ne doit pas faire** : modifier la base de données sans validation, créer du code externe à Laravel, réécrire du code sensible, etc.

12. **Critères de qualité du code généré**
    Énumérer les critères de qualité : respect des PSR, optimisation, lisibilité, testabilité, modularité, etc.

13. **Méthodologie ou style pédagogique attendu**
    Si l’agent est destiné aussi à accompagner des apprenants, préciser le ton attendu (pédagogique, progressif, illustré...), ainsi que le niveau de langage.

14. **Gestion des erreurs ou cas ambigus**
    Expliquer comment l’agent doit réagir en cas d’instruction floue, de dépendance manquante ou d’information insuffisante.

15. **Extensions futures possibles**
    Mentionner les évolutions envisagées pour que l’agent soit conçu dès le départ de façon évolutive (ex : ajout de support pour Jetstream, Vue.js, etc.).

---

Souhaitez-vous que je rédige un exemple de prompt complet basé sur ce plan ?
