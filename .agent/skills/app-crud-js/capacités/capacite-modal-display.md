# Capacité : Gestion des Modales dans le Framework CRUD JS

## 📌 Contexte
Dans l'architecture Gapp, la majorité des actions (Créer, Éditer, Voir, Supprimer, Actions personnalisées) ne s'ouvrent pas sur de nouvelles pages entières, mais à l'intérieur de **Modales Bootstrap** (fenêtres superposées).

## 🔑 Les classes de déclenchement (Triggers)
Le framework JS décide d'ouvrir la réponse HTTP dans une modale si l'élément déclencheur (lien `<a>` ou bouton) possède l'une de ces classes magiques :
- `showIndex` : Historiquement utilisée pour afficher un contenu de type liste/index ou une vue d'information dans une modale.
- `showEntity` : Utilisée pour afficher les détails d'un enregistrement (mode "Show").
- `editEntity` : Utilisée pour afficher un formulaire d'édition dans la modale.
- `createEntity` : Utilisée pour afficher un formulaire de création dans la modale.

## ⚙️ Mécanisme d'injection
1. **Requête** : L'utilisateur clique sur le lien, par exemple `<a href="..." class="showIndex">`.
2. **Réponse** : Le serveur Laravel traite la route et renvoie du HTML (la vue Blade partielle).
3. **Injection** : Le framework JS prend ce HTML, l'insère dans la zone `div.modal-body` d'une structure modale globale pré-existante dans le DOM, puis appelle `$('#maModal').modal('show')` pour l'afficher à l'écran.

## 🛠️ Interventions possibles
- **Forcer la modale** : Si vous ajoutez un nouveau lien et que vous souhaitez qu'il s'ouvre dans la fenêtre flottante, ajoutez-lui simplement la classe `showIndex` (ou une classe équivalente reconnue par le framework).
- **Désactiver la modale** : Si le lien possède la classe `showIndex` par défaut (généré par Gapp) et que vous souhaitez l'ouvrir normalement ou dans un nouvel onglet (`target="_blank"`), **supprimez la classe** `showIndex` de la balise HTML. Sans cette classe, le framework JS ignorera le lien et le comportement par défaut du navigateur s'exécutera.
