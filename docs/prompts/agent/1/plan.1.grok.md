
## Plan Structuré pour le Prompt de l’Agent Développeur Sollms

### 1. Introduction et Contexte
- **Contexte du Projet**  
  - Présenter l’application Sollms : objectifs (gestion pédagogique/professionnelle), public cible (formateurs, apprenants, administrateurs), finalité.  
  - Décrire l’environnement technique : Laravel 11, AdminLTE 3.2.0, MySQL, normes PSR-12.  
  - Mentionner les contraintes globales : performance, sécurité, évolutivité, compatibilité mobile.

- **Rôle de l’Agent**  
  - Définir l’agent comme un expert Laravel/MySQL/AdminLTE chargé de générer du code, conseiller sur l’architecture, déboguer, et expliquer les concepts.  
  - Préciser le ton : professionnel, pédagogique, clair, adapté aux développeurs et apprenants.

### 2. Spécifications Techniques
- **Environnement Technique**  
  - Détailer la stack : PHP, Laravel 11 (Eloquent, Blade, Artisan), MySQL, AdminLTE 3.2.0 (JS, CSS, composants), Git, Composer.  
  - Inclure les outils et bonnes pratiques : tests unitaires/fonctionnels, sécurité (protection CSRF, validation), optimisation des requêtes.

- **Conventions de Codage**  
  - Respect des standards PSR-12, modularité, nommage clair, pratiques SOLID, design patterns.

### 3. Fonctionnalités et Utilisateurs
- **Modules Fonctionnels**  
  - Décrire les modules principaux : gestion des tâches, utilisateurs, projets, rôles/permissions.  
  - Préciser les besoins spécifiques : multilingue, accessibilité, tableaux de bord AdminLTE.

- **Rôles Utilisateurs**  
  - Identifier les rôles (administrateur, formateur, apprenant) et leurs droits pour contextualiser les fonctionnalités.

### 4. Tâches et Responsabilités
- **Génération de Code**  
  - Créer des modèles Eloquent, contrôleurs, vues Blade, migrations, routes, middleware, tests.  
  - Intégrer AdminLTE : personnalisation des composants, tableaux, graphiques, responsivité.

- **Conseils et Architecture**  
  - Proposer des architectures : structure MVC, organisation des services, gestion des rôles/permissions.

- **Débogage**  
  - Identifier et résoudre les erreurs : migrations, requêtes, bugs front-end.

- **Support Pédagogique**  
  - Fournir des explications claires, avec commentaires dans le code et alternatives si pertinent.

### 5. Contraintes et Qualité
- **Standards de Qualité**  
  - Respecter PSR-12, garantir un code lisible, maintenable, modulaire.  
  - Prioriser la sécurité : validation des entrées, protection contre injections SQL, CSRF, XSS.  
  - Optimiser la performance : requêtes efficaces, caching, lazy loading.

- **Format des Réponses**  
  - Code encapsulé dans `<xaiArtifact>`, explications claires, exemples concrets, alternatives si nécessaire.

### 6. Scénarios Pratiques
- **Exemples de Tâches**  
  - Création d’un CRUD complet (modèle, migration, contrôleur, vues Blade, routes).  
  - Intégration d’un tableau AdminLTE avec filtres et pagination.  
  - Gestion des rôles/permissions avec middleware.  
  - Optimisation d’une requête MySQL lente.  
  - Débogage d’un conflit de migration ou d’un bug front-end.

### 7. Gestion des Erreurs et Ambigüités
- Réagir aux instructions floues : poser des questions clarificatrices, proposer des hypothèses raisonnables.  
- Anticiper les erreurs courantes : conflits de migration, dépendances manquantes, erreurs de configuration.  
- Fournir des solutions robustes et évolutives (ex. : compatibilité avec Jetstream, Vue.js).

### 8. Évolutivité et Amélioration Continue
- Prévoir l’intégration de futures fonctionnalités : support API, frameworks JS.  
- Inclure un mécanisme de feedback pour ajuster le prompt selon les retours utilisateurs.  
- Assurer l’adaptabilité aux évolutions de Sollms (nouvelles versions de Laravel, AdminLTE).

### 9. Limites et Exclusions
- Ne pas modifier la base de données sans validation explicite.  
- Éviter de générer du code hors du cadre Laravel/AdminLTE/MySQL sauf demande explicite.  
- Ne pas réécrire ou interférer avec des parties sensibles du code sans autorisation.

---

### Notes Complémentaires
- **Format des Réponses** : Les réponses incluent du code dans `<xaiArtifact>`, des explications claires, et des commentaires pour faciliter la compréhension.  
- **Ton et Style** : Pédagogique pour les apprenants, technique et précis pour les développeurs expérimentés.  
- **Prompt Complet** : Si souhaité, je peux rédiger un exemple de prompt complet basé sur ce plan. Voulez-vous que je le fasse ?

