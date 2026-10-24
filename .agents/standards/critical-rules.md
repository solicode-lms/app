---
trigger: always_on
priority: 1
---

# 🛑 Règles Critiques Inviolables (Golden Rules)

Ces règles sont **INVIOLABLES**. Elles garantissent la stabilité, la sécurité et la maintenabilité des applications propulsées par Gapp et le socle multi-couches.

## 1. Intégrité du Code & Base de Données
- **Aucune Commande Destructive :** Interdiction absolue de proposer ou d'exécuter des commandes destructives (`DROP TABLE`, `rm -rf`, `migrate:fresh`) sans avertissement formel et validation explicite du développeur.
- **Respect du Code Existant :** Ne jamais renommer de classes/méthodes publiques ou supprimer du code fonctionnel sans justification validée.
- **Migrations (LIGNE ROUGE ABSOLUE) :**
  - Toute nouvelle table principale DOIT comporter une colonne `$table->string('reference')->unique();`.
  - **L'AGENT A L'INTERDICTION FORMELLE D'EXÉCUTER `php artisan migrate` OU `migrate:rollback`.** L'agent doit uniquement générer le code du fichier de migration, puis demander au développeur humain de lancer la commande lui-même.

## 2. Le Générateur Gapp (Intégrité & Découpage)
- **Fichiers Protégés (`Base/`) :**
  - Ne **JAMAIS** modifier un fichier situé dans un dossier `Base/` ou dont le nom commence par `Base` (ex: `BaseFiliere.php`, `BaseFiliereService.php`). Ces fichiers sont 100 % générés et écrasés par Gapp.
  - **Classes Enfants PHP (`[Model].php`, `[Model]Service.php`) :** L'agent y injecte le code métier. Si un fichier enfant PHP contient la mention `// Ce fichier est maintenu par ESSARRAJ Fouad`, l'agent **doit la supprimer** pour y insérer son code métier.
  - **Fichiers Vues (Blade) :** Si un fichier Blade (`.blade.php`) contient la mention `{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}`, l'agent a l'**INTERDICTION** de le modifier directement. Il doit utiliser le sous-dossier `custom/` (ex: `custom/_table.blade.php`), ou documenter une issue dans `cahiers-charges/PkgGapp/` pour enrichir le générateur Gapp.
- **Dossier `db_schemas/` (LIGNE ROUGE) :** Ne **JAMAIS** lire ni modifier de fichiers dans le dossier `db_schemas/`. Ce dossier est exclusivement réservé au moteur interne de Gapp.
- **Attributs `$fillable` :** Ne jamais surcharger `$fillable` manuellement dans les classes enfants Eloquent : la liste est gérée par Gapp dans les classes `Base/`.
- **Méthodes CRUD dans les Contrôleurs :** Ne jamais surcharger entièrement `create()`, `edit()` ou `show()`. Utiliser le pattern Template Method : surcharger `dataForCreateView()`, `dataForEditView()`, `dataForShowView()`.

## 3. Architecture 3-Tiers & Couche Service
- **Contrôleurs Fins :** Les contrôleurs héritent d'`AdminController` (ou `PublicController`). Ils ne gèrent que la requête HTTP, l'autorisation, la validation via `FormRequest` et la réponse.
- **Toute modification de données passe par le Service :** Interdiction d'appeler `$model->save()`, `$model->update()`, ou `$model->delete()` directement dans les contrôleurs. Toujours appeler la méthode correspondante du Service (`$service->create()`, `$service->update()`).
- **Relations ManyToMany :** Interdiction d'appeler `$model->relation()->sync()`. Passer le tableau d'IDs dans les données envoyées au Service.
- **Exceptions Métier BLL :** Pour interrompre un traitement suite à une règle non respectée, lancer `throw new BlException("Message...");`. Ne pas faire de redirections HTTP manuelles.

## 4. Framework Frontend (TableUI & FilterUI)
- Ne jamais réécrire du code HTML brut ou des écouteurs jQuery ad-hoc pour les tableaux et filtres. Toujours s'appuyer sur l'infrastructure `TableUI.js`, `FilterUI.js`, `data-target-dynamic-dropdown` et `ModalUI.js`.

## 5. Priorité Absolue au Contexte Local
- En cas de divergence entre une règle générale du Hub et une convention spécifique déclarée dans `.agents/local/`, la convention locale prévaut toujours.

## 6. Affichage Systématique des Skills Utilisés (Règle d'Or de Communication)
- À la fin de chaque réponse ou tour de discussion, l'agent DOIT TOUJOURS afficher la liste des skills mobilisés de façon compacte (sur une ligne) :
  - Format : `> 🛠️ **Skills utilisés :** [nom-du-skill](file://...) · [autre-skill](file://...)`
  - Si aucun skill n'est requis : `> 🛠️ **Skills utilisés :** Aucun`
