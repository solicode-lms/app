# Capacité : Interception AJAX dans le Framework CRUD JS

## 📌 Contexte
Dans l'architecture générée par Gapp, le framework situé dans `resources/js/crud/` prend le contrôle du cycle de vie des navigations standards. 

## ⚙️ Mécanisme d'interception
1. **Écouteurs d'événements globaux** : Le framework attache des écouteurs sur le DOM, principalement sur les clics de liens (`<a>`) et les soumissions de formulaires (`<form>`).
2. **e.preventDefault()** : Si l'élément cliqué possède l'une des classes surveillées par le framework, l'action native du navigateur est annulée.
3. **Requête Asynchrone** :
   - Pour un lien : Le script lit l'attribut `href` et lance une requête `GET` en AJAX vers cette URL.
   - Pour un formulaire : Le script lit l'attribut `action`, récupère les données via `FormData`, et lance une requête `POST`/`PUT`/`DELETE` en AJAX.
4. **Traitement de la réponse** : La réponse HTTP (HTML partiel ou complet, ou JSON) est récupérée pour être soit injectée dans une zone de la page (ou une modale), soit traitée pour afficher un toast/alerte de succès ou d'erreur.

## ⚠️ Conséquences
- L'URL dans la barre de navigation du navigateur n'est souvent pas modifiée, ce qui garde l'utilisateur sur une "Single Page Application" (SPA) partielle.
- Si vous avez besoin d'une véritable redirection ou d'ouvrir un lien dans un nouvel onglet, ce mécanisme doit être désactivé en retirant les classes déclencheuses.
