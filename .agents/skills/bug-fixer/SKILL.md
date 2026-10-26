---
name: bug-fixer
description: Diagnostique méthodiquement les erreurs de logs laravel.log, exceptions métier BlException et pannes TableUI, et applique des correctifs chirurgicaux non régressifs.
version: 1.0.0
inspiration:
  source: "anthropic/claude-plugins-official"
  plugin: "pr-review-toolkit"
  pattern: "systematic root-cause analysis & non-breaking defect repair"
---

# 1. Mission
Ce skill intervient lors de tout dysfonctionnement, régression ou incident survenu dans Solicode LMS. Il applique une démarche d'investigation rigoureuse : inspection des journaux d'erreurs (`storage/logs/laravel.log`), reconstitution de la pile d'appels à travers les couches (Route ➔ Contrôleur ➔ Service ➔ Modèle / Composant `TableUI`), identification de la cause racine et application d'un correctif chirurgical minimal sans altérer les classes générées `Base/` ni casser l'expérience utilisateur.

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Signalement d'un bug ou d'une erreur 500/422 ("J'ai une erreur 500 quand je filtre les apprenants", "Le formulaire ne se valide pas").
  - Déclenchement inattendu d'une exception métier `BlException`.
  - Dysfonctionnement d'un tableau `TableUI.js`, d'une modale `ModalUI.js` ou d'un dropdown dynamique `data-target-dynamic-dropdown`.
  - Échec de tests automatisés ou incohérences de données.
- **Quand NE PAS l'activer :**
  - Ajout d'une nouvelle fonctionnalité métier ➔ Utiliser `feature-dev`.
  - Création de nouvelles tables ou modèles ➔ Utiliser `domain-scaffolder`.
  - Conception de règles globales d'agent ➔ Utiliser `plugin-dev`.

# 3. Entrées & Ressources à Charger
- **Priorité 1 :** `storage/logs/laravel.log` (Trace d'exécution et exceptions récentes).
- **Priorité 2 :** `.agents/rules/critical-rules.md` (Garde-fous architecturaux, verrous Gapp).
- **Priorité 3 :** Code source incriminé dans `modules/Pkg[Nom]/` (Service enfant, Contrôleur, FormRequest ou vue).
- **Priorité 4 :** `.agents/references/frontend/tableui-framework.md` (si l'erreur concerne l'interface ou les requêtes AJAX).
- **Priorité 5 :** Fiche d'issue dans `issues/<SousDossier>/` si signalée (régie par [.agents/rules/gestion-issues.md](file:///c:/AppServer/solicode-lms/.agents/rules/gestion-issues.md)).

# 4. Contraintes & Règles Inviolables
- **Chargement Préalable de `[NomPkg]_IA_CONTEXT.md` :** Dès que le module concerné par l'erreur est identifié, l'agent DOIT impérativement charger son fichier `modules/Pkg[Nom]/Pkg[Nom]_IA_CONTEXT.md` pour appréhender les relations et services avant d'analyser le code en détail.
- **Diagnostic sur Preuve :** Ne jamais deviner la cause d'une panne sans avoir préalablement lu la trace réelle dans `storage/logs/laravel.log` ou le code source exact.
- **Préservation des Fichiers `Base/` :** Interdiction d'éditer une classe dans `Base/` pour corriger un bug. Si un bug est localisé dans une méthode `Base`, surcharger la méthode dans la classe enfant PHP correspondante.
- **Préservation des Vues Blade Protégées :** Si une vue contient le verrou `{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}`, appliquer le correctif dans le sous-dossier `custom/` de la vue.
- **Fiche d'Incident YAML :** Toute anomalie structurelle corrigée doit être consignée sous forme de fiche dans `.agents/tests/feedbacks/INC-[NUM].yaml`.

# 5. Procédure Pas-à-Pas (Workflow de Diagnostic & Résolution)
1. **Étape 1 : Localisation & Lecture des Logs :**
   - Consulter la fin de `storage/logs/laravel.log` pour extraire le message d'exception exact, le fichier et le numéro de ligne.
2. **Étape 2 : Remontée de la Chaîne d'Appel :**
   - Identifier le composant défaillant : Route, FormRequest, Contrôleur, Service ou Modèle Eloquent.
   - S'il s'agit d'une exception BLL (`throw new BlException(...)`), vérifier pourquoi la condition métier n'a pas été satisfaite.
   - S'il s'agit d'une erreur AJAX `TableUI`, inspecter la payload JSON retournée (code HTTP 422 ou 500).
3. **Étape 3 : Isolation de la Cause Racine :**
   - Distinguer une erreur de données (ex: champ null non géré), une rupture de relation Eloquent ou une incohérence de paramètres de vue (`scopeVariables`).
4. **Étape 4 : Application du Correctif Chirurgical :**
   - Modifier uniquement les lignes strictement nécessaires dans la classe enfant ou le composant Blade `custom/`.
   - Ne jamais introduire de dépendances non maîtrisées.
5. **Étape 5 : Traçabilité de l'Incident & Clôture d'Issue :**
   - Créer ou actualiser la fiche YAML d'incident pour enrichir la mémoire collective de l'agent.
   - Si l'anomalie est associée à une issue, appliquer la procédure de clôture selon la règle [.agents/rules/gestion-issues.md](file:///c:/AppServer/solicode-lms/.agents/rules/gestion-issues.md).

# 6. Gestion des Ambiguïtés & Décisions
- Si le fichier `laravel.log` ne contient pas d'erreur récente : demander au développeur de reproduire l'action et réexaminer le log.
- Si le correctif nécessite une modification de structure de base de données : proposer une migration corrective avec `domain-scaffolder`, sans jamais exécuter `migrate` en aveugle.

# 7. Format de Sortie & Livrables
- Analyse synthétique : Cause racine identifiée (fichier, ligne, mécanisme en cause).
- Diff de code clair et chirurgical appliqué aux fichiers enfants.
- Fiche de retour d'expérience dans `.agents/tests/feedbacks/INC-xxx.yaml`.

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] La trace d'erreur dans laravel.log a-t-elle été formellement identifiée avant toute modification ?
- [ ] Le correctif évite-t-il soigneusement d'altérer les fichiers dans Base/ ?
- [ ] Le comportement fonctionnel nominal reste-t-il préservé sans effets de bord ?
- [ ] Une fiche d'incident a-t-elle été documentée pour pérenniser l'apprentissage ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Modifier la classe Base pour fixer un bug | Croyance que le bug vient du framework | Surcharger la méthode incriminée dans la classe enfant |
| Écraser une vue Blade verrouillée | Non-respect du lock ESSARRAJ Fouad | Utiliser le sous-dossier `custom/` de la vue |
| Supprimer du code fonctionnel par confusion | Manque d'analyse de la trace | Isoler précisément la ligne d'exception sans toucher au reste |

# 10. Exemples Concrets (Few-Shot)
**Exemple d'invocation :** *"Quand je clique sur Filtrer par groupe dans Formateur, j'ai une erreur 500."*
**Comportement :** L'agent inspecte `laravel.log`, découvre que la relation `groupes` n'était pas eager-loadée dans `FormateurService`, ajoute la relation dans `$index_with_relations` de `FormateurService.php`, teste le flux, et crée `INC-002-eager-load-groupes.yaml`.
