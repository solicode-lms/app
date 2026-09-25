# Capacité : Architecture et Checklist des Composants
**Skill Associé :** `sys-conception`

Cette capacité contient la liste exhaustive des composants de l'application Solicode LMS et les skills spécialisés associés pour les modifier. Lors de la rédaction d'une issue, le concepteur DOIT utiliser cette grille pour analyser les impacts transverses d'une évolution.

## Grille d'Analyse (Composants -> Skills)

- 🗄️ **Base de données (Migrations/Tables)** -> `app-migration`
- 🧠 **Modèles & Enums (Eloquent, Casts)** -> `app-model`
- ⚙️ **Logique Métier (Services / BaseService)** -> `app-service`
- 🕹️ **Contrôleurs (Controllers, FormRequests, Traits)** -> `app-controller`
- 🎨 **Interface Utilisateur (Formulaires, Vues Blade, Tableaux)** -> `app-blade`
- 🌐 **Traduction (Fichiers lang/fr générés par Gapp)** -> `app-lang`
- 🔍 **Filtres de recherche et de données** -> `app-blade`
- 📝 **Édition en ligne (Inline Edit, Vue et Validation)** -> `app-blade`
- 🔄 **Métadonnées du Générateur (Gapp JSON, scopes)** -> `sys-gapp`
- 📊 **Gestion des états d'affichage (ViewState)** -> `app-view-state`
- 💼 **Règles Spécifiques au Module** -> `pkg-[nom-du-module]` (ex: `pkg-qcm`)

## Règle d'usage
Lorsqu'une issue (ex: "Ajouter une note de QCM") est demandée, le concepteur parcourt chaque ligne de cette grille et se pose la question : "Cette modification impacte-t-elle ce composant ?". Si oui, le skill associé doit être listé dans la section **"Skills Requis pour la Réalisation"** de l'issue.
