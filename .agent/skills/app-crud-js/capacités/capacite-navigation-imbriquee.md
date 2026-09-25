# Capacité : Navigation Imbriquée et Modales Superposées (crud-js)

Cette capacité documente comment le micro-framework JavaScript de Gapp gère l'ouverture de modales à l'intérieur d'autres modales (navigation imbriquée), sans jamais recharger la page.

## Le Principe des `CrudModalManager` Emboîtés

Dans l'architecture de Gapp, chaque page ou vue partielle (ex: `_index.blade.php`) déclare son propre gestionnaire d'états en ajoutant une configuration dans `window.crudModalManagersConfig`.

Lorsqu'un clic est intercepté (par exemple via `.showIndex`), voici la chaîne d'événements :
1. **Création d'une Modale** : L'action (ex: `ShowIndexAction`) demande à son instance de `ModalUI` d'ouvrir une modale (`iziModal`). Cette modale reçoit un ID unique lié au gestionnaire courant (ex: `affectation-dynamic-modal`) et est injectée directement à la racine du `body`.
2. **Chargement AJAX** : Le contenu HTML de la vue ciblée est téléchargé via `Fetch/$.get`.
3. **Évaluation des Scripts (Clé de Voûte)** : Le contenu téléchargé est injecté dans la modale. Ensuite, le framework **évalue et exécute les balises `<script>`** contenues dans cette réponse HTML (via `executeScripts()`).
4. **Instanciation du Sous-Manager** : L'exécution du script ajoute la configuration du nouveau composant (ex: `question`) à `crudModalManagersConfig`. Immédiatement après, l'appel à `InitUIManagers.init()` repère cette nouvelle configuration et instancie un TOUT NOUVEAU `CrudModalManager` exclusif à cette vue `question`.

## La Superposition des Modales (`ModalUI.js`)

Si l'utilisateur navigue encore plus profondément (ex: clique sur "Modifier" ou `.editEntity` dans la liste des questions qui est DÉJÀ dans une modale) :
1. C'est le **nouveau** `CrudModalManager` de la question qui intercepte le clic.
2. Il appelle son propre `ModalUI.showLoading()`.
3. Ce `ModalUI` génère un nouveau conteneur de modale (ex: `question-dynamic-modal`) et le place sur le `body`.
4. **Détection du Parent** : Le système détecte la présence de la première modale (`affectation-dynamic-modal`), la considère comme `parentModal`, et lui applique une couleur grisée (`#6c757d`) en modifiant son header.
5. La nouvelle modale apparaît **par-dessus** l'ancienne.

## Restauration et Rafraîchissement (`handleClose`)

Lorsqu'une modale enfant se ferme (par exemple, après soumission réussie d'un formulaire d'édition) :
1. Le gestionnaire `ModalUI` appelle `restoreParentModal()` pour rendre sa couleur d'origine (et son statut plein écran) à la modale parente (ex: la liste d'index).
2. Il déclenche immédiatement `this.indexUI.tableUI.loadListAction.loadEntities()`.
3. Cela rafraîchit la grille de données de la modale parente avec les nouvelles données serveur, offrant une expérience fluide sans le moindre rechargement de page.

## Astuces pour le Développement
- Si vous créez une vue personnalisée (non générée par Gapp) qui doit ouvrir des modales CRUD natives, **vous DEVEZ envelopper votre contenu dans un conteneur (`<div id="nom-crud" class="crud">`) et simuler une configuration basique** dans `window.crudModalManagersConfig` pour que le framework attache ses écouteurs d'événements.

## ⚠️ Règle Critique : Le Paramètre `showIndex`

Lorsque vous créez manuellement un lien (avec la classe `.showIndex`) pour ouvrir la liste d'une entité dans une modale enfant, **il est OBLIGATOIRE d'ajouter le paramètre `'showIndex' => 1`** dans la fonction `route()` !

### ✅ Bon usage :
```blade
<a href="{{ route('questions.index', ['qcm_id' => $qcm->id, 'showIndex' => 1]) }}" class="showIndex">
    Gérer les questions
</a>
```

### ❌ Mauvais usage (Cause de bugs de redirection) :
```blade
<!-- 🚨 Attention : Il manque 'showIndex' => 1 -->
<a href="{{ route('questions.index', ['qcm_id' => $qcm->id]) }}" class="showIndex">
```

**Pourquoi est-ce crucial ?** 
Dans les `BaseControllers` générés par Gapp, la méthode `index(Request $request)` vérifie la présence de `$request['showIndex']` :
- Si `showIndex=1` est présent, le contrôleur retourne **`_index.blade.php`**, qui contient les scripts de configuration JS (le fameux `window.crudModalManagersConfig.push`) et le conteneur principal (`<div id="model-crud">`).
- S'il est absent, le contrôleur retourne uniquement **`_table.blade.php`**. 
- Le tableau s'affichera bien visuellement, mais **le gestionnaire CRUD JavaScript ne sera JAMAIS instancié**. Par conséquent, les clics sur les actions internes (ex: `.editEntity`, `.deleteEntity`) ne seront pas interceptés par AJAX, ce qui provoquera une redirection classique (rechargement de page) au lieu de l'ouverture fluide d'une sous-modale.
