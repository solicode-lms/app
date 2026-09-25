# Capacité : Comportement JavaScript Gapp (crud-js)

## 📌 Contexte
Les vues Blade générées par Gapp intègrent des scripts JavaScript utilitaires (crud-js) qui dynamisent l'interface utilisateur. Ces scripts s'appuient sur des classes CSS spécifiques pour attacher des comportements événementiels (AJAX, modales) aux éléments HTML.

## 🛠️ La classe `showIndex`
- **Objectif** : La classe CSS `showIndex` indique au système crud-js que la page de destination du lien (le `href`) doit être chargée et affichée **à l'intérieur d'une fenêtre modale** (modal Bootstrap), plutôt que de provoquer une navigation classique ou de recharger la page entière.
- **Comportement technique** : Au clic sur un élément portant la classe `showIndex`, le comportement par défaut du lien est annulé (`e.preventDefault()`). Une requête AJAX est envoyée à l'URL cible, et la réponse HTML est injectée dynamiquement dans le conteneur de la modale.
- **Cas d'usage courant** : 
  - Afficher les détails d'un enregistrement sans quitter la liste (index).
  - Ouvrir des formulaires ou des actions annexes dans un contexte visuel superposé.
- **Comment désactiver ce comportement ?** : Si l'objectif est d'ouvrir le lien dans un nouvel onglet (ex: `target="_blank"`) ou de naviguer de manière standard, il est **impératif de retirer** la classe `showIndex` de l'élément HTML (`<a>` ou `<button>`).
