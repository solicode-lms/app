---
name: plugin-dev
description: Gouverne, conçoit, crée et raffine les skills, les règles (.agents/rules/) et les standards normatifs selon les patterns officiels d'Anthropic, et maintient la conformité SemVer.
version: 1.3.0
inspiration:
  source: "anthropic/claude-plugins-official"
  plugin: "plugin-dev"
  pattern: "skill scaffolding, trigger tuning, resource encapsulation & continuous refinement"
---

# 1. Mission
Ce méta-skill assure la gouvernance technique complète de l'écosystème IA : la conception, la création et le raffinement continu des **Skills** partagés (`.agents/skills/`), des **Règles d'or et directives permanentes** (`.agents/rules/`), ainsi que la **capitalisation continue des connaissances de package** (`[NomPkg]_IA_CONTEXT.md`).

**Principes Fondateurs Inviolables :**
- **Single Source of Truth (SSOT) :** Toute règle, spécification ou directive doit résider dans un unique emplacement faisant autorité. La duplication d'instructions à travers plusieurs fichiers est formellement interdite.
- **DRY (Don't Repeat Yourself) :** Ne jamais réécrire ou dupliquer une consigne existante. Tout skill ou document doit exploiter la règle existante par simple référence ou lien.
- Il applique systématiquement les motifs de référence d'Anthropic (`feature-dev`, `claude-code-setup`, `pr-review-toolkit`), veille à la non-contradiction des règles, documente les nouveaux flux de travail et consigne les rétro-actions sous forme de fiches d'incidents YAML.

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - **Fin de conversation / Rétrospective de session :** Invoqué par `/plugin-dev` pour proposer un plan d'enrichissement du fichier `[NomPkg]_IA_CONTEXT.md` du package traité et des compétences mobilisées, en y consignant les découvertes, pièges et mécaniques apprises durant la session.
  - Demande de création d'un nouveau skill ("Crée un skill pour gérer les tests automatisés", "Ajoute un skill concevoir-architecture").
  - Raffinement ou correction d'un skill existant suite à un retour d'expérience ("Modifie le skill feature-dev pour interdire X").
  - Création, mise à jour ou audit d'une **Règle** comportementale ("Ajoute une règle pour afficher les skills utilisés", "Modifie la règle critique sur les migrations dans critical-rules.md").
  - Documentation d'un nouveau flux de travail.
  - Audit de conformité des compétences, cohérence des directives, élimination des duplications (DRY/SSOT) et contrôle SemVer.
- **Quand NE PAS l'activer :**
  - Rédaction ou simplification de tutoriels ➔ Utiliser `tutorial-writer`.
  - Développement de composants ou de styles Jekyll ➔ Utiliser `dev-jekyll`.
  - Synchronisation du socle technique partagé (Core Theme) ➔ Utiliser `sync-theme`.
  - Initialisation d'agent ou diagnostic projet ➔ Utiliser `project-setup`.

# 3. Entrées & Ressources à Charger
- **Priorité 1 :** `.agents/skills/plugin-dev/resources/anthropic-skill-patterns.md` (Motifs officiels Anthropic encapsulés).
- **Priorité 2 :** Fichiers de règles :
  - `.agents/rules/critical-rules.md` (Règles critiques invariables).
  - `.agents/rules/*.md` (Directives d'exécution et de style).
- **Priorité 3 :** `.agents/templates/generic-skill.md` (Gabarit en 10 rubriques) et `.agents/templates/workflow-blueprint.md` (Modèle de flux).
- **Priorité 4 :** Registre `catalog.yaml` et `CHANGELOG.md` du Hub Global.

# 4. Contraintes & Règles Inviolables
- **Principes Cardinaux SSOT (Single Source of Truth) & DRY :**
  - Toute convention, directive ou spécification doit exister en un seul et unique endroit.
  - Les règles comportementales résident dans `.agents/rules/[nom].md`. Les compétences ne doivent jamais dupliquer le texte de ces règles mais les référencer directement.
- **Format Normé des Fichiers de Contexte Package :**
  - Tout fichier de contexte de package doit obligatoirement être nommé `modules/[NomPkg]/[NomPkg]_IA_CONTEXT.md` (ex: `PkgRealisationTache_IA_CONTEXT.md`).
- **Minimalisme Strict de `.agents/references/` (Encapsulation Mono-Compétence) :**
  - Le répertoire transversal `.agents/references/` doit contenir le **strict minimum** de fichiers, réservé exclusivement aux conventions et standards partagés par au moins 2 compétences.
  - Tout fichier méthodologique, guide, catalogue ou template utilisé par une seule compétence DOIT OBLIGATOIREMENT être déplacé et encapsulé dans `<skill>/resources/` (principe de divulgation progressive d'Anthropic).
- **Bloc `inspiration` Obligatoire :** Tout skill créé ou modifié DOIT impérativement comporter la métadonnée `inspiration:` dans son frontmatter YAML avec sa source, son plugin de référence et son pattern.
- **Respect Strict des 10 Rubriques :** Aucun skill ne peut être publié s'il omet l'une des 10 rubriques normées.
- **Hiérarchie et Non-Contradiction des Règles :**
  - Une règle locale (`.agents/rules/`) prime sur une recommandation générale, mais ne doit jamais violer une règle critique de sécurité (`critical-rules.md`) sans arbitrage explicite.
  - Chaque règle doit être concise, univoque et comporter un déclencheur explicite (`trigger: always_on` ou conditionnel).
- **Validation Automatisée :** Toute modification de skill doit être validée par `node scripts/validate-skills.js`.
- **Fiche d'Incident YAML :** Toute correction suite à un bug de l'agent doit générer une fiche dans `tests/feedbacks/INC-xxx.yaml`.

# 5. Procédure Pas-à-Pas (Workflow de Conception & Raffinement)

### Parcours A : Création d'un Nouveau Skill
1. **Étape 1 : Analyse du Besoin & Motif Anthropic :**
   - Consulter `resources/anthropic-skill-patterns.md` pour sélectionner le motif le plus proche (`feature-dev`, `setup`, `review`, etc.).
2. **Étape 2 : Rédaction du `SKILL.md` :**
   - Remplir le gabarit en 10 rubriques.
   - Formuler la description à la 3ème personne en précisant clairement les cas d'activation et d'exclusion.
   - Remplir le bloc YAML `inspiration`.
   - Encapsuler les éventuelles ressources d'accompagnement propres au skill dans son sous-dossier `resources/`.
3. **Étape 3 : Contrôle Déterministe :**
   - Exécuter `node scripts/validate-skills.js`.
4. **Étape 4 : Enregistrement dans le Catalogue :**
   - Ajouter l'entrée dans `catalog.yaml` et documenter dans `CHANGELOG.md`.

### Parcours B : Raffinement d'un Skill Existant
1. **Étape 1 : Diagnostic & Fiche YAML :**
   - Créer `tests/feedbacks/INC-[NUM].yaml` avec le contexte, le comportement observé et la correction visée.
2. **Étape 2 : Correction Chirurgicale :**
   - Ajouter la règle dans la Rubrique #4 (Contraintes) ou un test binaire dans la Rubrique #8 (Checklist).
3. **Étape 3 : Incrément SemVer :**
   - Mettre à jour la version (ex: 1.0.1) et consigner dans `CHANGELOG.md`.

### Parcours C : Création ou Modification d'une Règle (Rules)
1. **Étape 1 : Qualification du Niveau de Règle :**
   - Règle globale d'ingénierie / sécurité ➔ Ajouter dans `.agents/rules/critical-rules.md` ou `.agents/rules/general-principles.md`.
   - Règle comportementale d'agent projet ➔ Ajouter dans `.agents/rules/[nom-de-la-regle].md`.
2. **Étape 2 : Rédaction Concise & Inviolable :**
   - Formuler des impératifs clairs ("L'agent doit...", "Interdiction formelle de...").
   - Donner un exemple direct de format attendu.
   - Respecter le principe SSOT : si la règle existe déjà, ne jamais la dupliquer.
3. **Étape 3 : Synchronisation & Notification :**
   - Répercuter la modification dans le Hub ou le projet local et consigner l'évolution dans `CHANGELOG.md`.

### Parcours D : Capitalisation Continue en Fin de Conversation (`/plugin-dev`)
1. **Étape 1 : Rétrospective & Identification des Découvertes :**
   - Identifier le ou les packages traités au cours de la session récente.
   - Extraire les mécaniques spécifiques que l'agent a dû investiguer ou déduire :
     - Tables et modèles impliqués, relations et hooks métier.
     - Particularités du `ViewStateService` ou des filtres de la table.
     - Services temporels, jobs ou observers nouvellement introduits.
     - Pièges ou edge cases résolus (ex: calculs faussés par omission de filtres parents).
2. **Étape 2 : Évaluation des Écarts Documentaires :**
   - Comparer ces découvertes avec le contenu existant de `modules/[NomPkg]/[NomPkg]_IA_CONTEXT.md`.
   - Vérifier si des compétences (`feature-dev`, `bug-fixer`) ou règles bénéficieraient d'une directive additionnelle.
3. **Étape 3 : Proposition de Plan d'Enrichissement :**
   - Soumettre au concepteur un plan clair et structuré détaillant les ajouts proposés :
     - Pour `[NomPkg]_IA_CONTEXT.md` (nouveaux services, filtres clés, section Pièges & Recommandations).
     - Pour les compétences si applicable.
4. **Étape 4 : Exécution après Validation :**
   - Dès validation, enrichir le fichier `[NomPkg]_IA_CONTEXT.md` afin que toute future session de maintenance dispose immédiatement de ce savoir capitalisé.

# 6. Gestion des Ambiguïtés & Décisions
- **Arbitrage Emplacement des Ressources (Skill vs Agent) :**
  - Si la ressource est exploitée exclusivement par un skill particulier (ex: gabarits éditoriaux d'un rédacteur, catalogue technique propre) ➔ Emplacement obligatoire : `<skill>/resources/[fichier].md` (chargée à la demande dans la section 3 du skill).
  - Si la ressource définit un standard d'ingénierie transverse partagé par 2 compétences ou plus ➔ Emplacement : `.agents/references/conventions/` ou `.agents/references/architecture/`.
- **Arbitrage Skill vs Règle :**
  - Si l'instruction est une contrainte permanente ou un garde-fou transversal (ex: affichage des compétences, style de réponse, interdiction de migration) ➔ Créer une **Règle** (`.agents/rules/`).
  - Si l'instruction décrit une séquence outillée avec déclenchement à la demande, entrées, logique pas-à-pas et livrables ➔ Créer un **Skill** (`SKILL.md`).
- Si la règle demandée est exclusive à Solicode LMS : ne pas modifier le standard du Hub global, mais l'inscrire dans `.agents/rules/` et `.agents/references/conventions/project-conventions.md`.

# 7. Format de Sortie & Livrables
- Le fichier `SKILL.md` complet ou le fichier de règle (`.agents/rules/*.md`).
- La mise à jour de `catalog.yaml` et `CHANGELOG.md`.
- Le résultat d'exécution du script `validate-skills.js` (0 erreur).

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] Le frontmatter YAML du skill contient-il le bloc inspiration complet (source, plugin, pattern) ?
- [ ] La description est-elle à la 3ème personne et précise-t-elle quand activer et quand ne pas activer ?
- [ ] Les 10 rubriques normées sont-elles toutes présentes et renseignées ?
- [ ] Le principe SSOT (Single Source of Truth) et la règle DRY sont-ils respectés (aucune consigne dupliquée entre fichiers) ?
- [ ] Le répertoire `.agents/references/` est-il minimal et exempt de toute ressource mono-compétence ?
- [ ] Les ressources mono-compétence sont-elles rigoureusement encapsulées dans `<skill>/resources/` ?
- [ ] S'il s'agit d'une règle, est-elle concise, non contradictoire avec critical-rules et munie d'un exemple clair ?
- [ ] Le script node scripts/validate-skills.js passe-t-il avec succès ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Déposer une ressource spécifique au niveau Agent | Manque de granularité dans l'encapsulation | Déplacer le fichier sous `<skill>/resources/` et référencer en ressource interne |
| Créer un skill pour une simple règle passive | Confusion entre règle et flux outillé | Basculer l'instruction vers .agents/rules/ |
| Oubli de la métadonnée d'inspiration | Inattention | Ajouter systématiquement le bloc inspiration dans le frontmatter |
| Règle locale en conflit avec le standard Hub | Manque d'audit préalable | Auditer .agents/rules/critical-rules.md avant de formaliser la règle |

# 10. Exemples Concrets (Few-Shot)
**Exemple d'invocation A (Skill) :** *"Crée le skill bug-fixer inspiré de pr-review-toolkit."*
**Comportement :** L'agent charge `anthropic-skill-patterns.md`, extrait le pattern de revue consolidée, rédige les 10 rubriques avec `inspiration: { plugin: "pr-review-toolkit" }`, isole les fiches de templates dans `bug-fixer/resources/`, exécute `validate-skills.js`, et enregistre la compétence dans `catalog.yaml`.

**Exemple d'invocation B (Règle) :** *"Ajoute une règle imposant l'affichage des skills utilisés à chaque fin de message."*
**Comportement :** L'agent applique le Parcours C de `plugin-dev`, rédige `.agents/rules/affichage-skills.md`, met à jour `.agents/rules/critical-rules.md`, et applique immédiatement le format requis.
