---
name: plugin-dev
description: Gouverne, conçoit, crée et raffine les skills, les règles (.agents/rules/, AGENTS.md) et les standards normatifs selon les patterns officiels d'Anthropic, et maintient la conformité SemVer.
version: 1.1.0
inspiration:
  source: "anthropic/claude-plugins-official"
  plugin: "plugin-dev"
  pattern: "skill scaffolding, trigger tuning & continuous refinement"
---

# 1. Mission
Ce méta-skill assure la gouvernance technique complète de l'écosystème IA : la conception, la création et le raffinement continu des **Skills** partagés (`.agents/skills/`), des **Règles d'or et directives permanentes** (`.agents/rules/`, `AGENTS.md`) et des **Standards normatifs** (`.agents/standards/critical-rules.md`). Il applique systématiquement les motifs de référence d'Anthropic (`feature-dev`, `claude-code-setup`, `pr-review-toolkit`), veille à la non-contradiction des règles, documente les nouveaux flux de travail et consigne les rétro-actions sous forme de fiches d'incidents YAML.

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Demande de création d'un nouveau skill ("Crée un skill pour gérer les tests automatisés", "Ajoute un skill concevoir-architecture").
  - Raffinement ou correction d'un skill existant suite à un retour d'expérience ("Modifie le skill feature-dev pour interdire X").
  - Création, mise à jour ou audit d'une **Règle** comportementale ("Ajoute une règle pour afficher les skills utilisés", "Modifie la règle critique sur les migrations dans critical-rules.md", "Mets à jour AGENTS.md").
  - Documentation d'un nouveau flux de travail dans `references/workflows/`.
  - Audit de conformité des compétences, cohérence des directives et contrôle SemVer.
- **Quand NE PAS l'activer :**
  - Développement applicatif métier (PHP, Blade, JS) ➔ Utiliser `feature-dev`.
  - Initialisation d'agent pour un projet ➔ Utiliser `project-setup`.
  - Documentation d'un package applicatif ➔ Utiliser `context-generator`.

# 3. Entrées & Ressources à Charger
- **Priorité 1 :** `.agents/references/anthropic/anthropic-skill-patterns.md` (Motifs officiels Anthropic).
- **Priorité 2 :** Fichiers normatifs et de règles :
  - `.agents/standards/critical-rules.md` (Règles critiques invariables).
  - `AGENTS.md` et `.agents/rules/*.md` (Directives d'exécution et de style).
- **Priorité 3 :** `.agents/templates/generic-skill.md` (Gabarit en 10 rubriques) et `.agents/templates/workflow-blueprint.md` (Modèle de flux).
- **Priorité 4 :** Registre `catalog.yaml` et `CHANGELOG.md` du Hub Global.

# 4. Contraintes & Règles Inviolables
- **Bloc `inspiration` Obligatoire :** Tout skill créé ou modifié DOIT impérativement comporter la métadonnée `inspiration:` dans son frontmatter YAML avec sa source, son plugin de référence et son pattern.
- **Respect Strict des 10 Rubriques :** Aucun skill ne peut être publié s'il omet l'une des 10 rubriques normées.
- **Hiérarchie et Non-Contradiction des Règles :**
  - Une règle locale (`.agents/rules/`, `AGENTS.md`) prime sur une recommandation générale, mais ne doit jamais violer une règle critique de sécurité (`critical-rules.md`) sans arbitrage explicite.
  - Chaque règle doit être concise, univoque et comporter un déclencheur explicite (`trigger: always_on` ou conditionnel).
- **Validation Automatisée :** Toute modification de skill doit être validée par `node scripts/validate-skills.js`.
- **Fiche d'Incident YAML :** Toute correction suite à un bug de l'agent doit générer une fiche dans `tests/feedbacks/INC-xxx.yaml`.

# 5. Procédure Pas-à-Pas (Workflow de Conception & Raffinement)

### Parcours A : Création d'un Nouveau Skill
1. **Étape 1 : Analyse du Besoin & Motif Anthropic :**
   - Consulter `references/anthropic/anthropic-skill-patterns.md` pour sélectionner le motif le plus proche (`feature-dev`, `setup`, `review`, etc.).
2. **Étape 2 : Rédaction du `SKILL.md` :**
   - Remplir le gabarit en 10 rubriques.
   - Formuler la description à la 3ème personne en précisant clairement les cas d'activation et d'exclusion.
   - Remplir le bloc YAML `inspiration`.
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

### Parcours C : Création ou Modification d'une Règle (Rules & Standards)
1. **Étape 1 : Qualification du Niveau de Règle :**
   - Règle globale d'ingénierie / sécurité ➔ Ajouter dans `standards/critical-rules.md` ou `standards/general-principles.md`.
   - Règle de communication / comportement d'agent projet ➔ Ajouter dans `AGENTS.md` et `.agents/rules/[nom-de-la-regle].md`.
2. **Étape 2 : Rédaction Concise & Inviolable :**
   - Formuler des impératifs clairs ("L'agent doit...", "Interdiction formelle de...").
   - Donner un exemple direct de format attendu.
3. **Étape 3 : Synchronisation & Notification :**
   - Répercuter la modification dans le Hub ou le projet local et consigner l'évolution dans `CHANGELOG.md`.

# 6. Gestion des Ambiguïtés & Décisions
- **Arbitrage Skill vs Règle :**
  - Si l'instruction est une contrainte permanente ou un garde-fou transversal (ex: affichage des compétences, style de réponse, interdiction de migration) ➔ Créer une **Règle** (`.agents/rules/` ou `AGENTS.md`).
  - Si l'instruction décrit une séquence outillée avec déclenchement à la demande, entrées, logique pas-à-pas et livrables ➔ Créer un **Skill** (`SKILL.md`).
- Si la règle demandée est exclusive à Solicode LMS : ne pas modifier le standard du Hub global, mais l'inscrire dans `AGENTS.md` et `.agents/references/conventions/project-conventions.md`.

# 7. Format de Sortie & Livrables
- Le fichier `SKILL.md` complet ou le fichier de règle (`.agents/rules/*.md`, `AGENTS.md`, `critical-rules.md`).
- La mise à jour de `catalog.yaml` et `CHANGELOG.md`.
- Le résultat d'exécution du script `validate-skills.js` (0 erreur).

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] Le frontmatter YAML du skill contient-il le bloc inspiration complet (source, plugin, pattern) ?
- [ ] La description est-elle à la 3ème personne et précise-t-elle quand activer et quand ne pas activer ?
- [ ] Les 10 rubriques normées sont-elles toutes présentes et renseignées ?
- [ ] S'il s'agit d'une règle, est-elle concise, non contradictoire avec critical-rules et munie d'un exemple clair ?
- [ ] Le script node scripts/validate-skills.js passe-t-il avec succès ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Créer un skill pour une simple règle passive | Confusion entre règle et flux outillé | Basculer l'instruction vers rules/ ou AGENTS.md |
| Oubli de la métadonnée d'inspiration | Inattention | Ajouter systématiquement le bloc inspiration dans le frontmatter |
| Règle locale en conflit avec le standard Hub | Manque d'audit préalable | Auditer standards/critical-rules.md avant de formaliser la règle |

# 10. Exemples Concrets (Few-Shot)
**Exemple d'invocation A (Skill) :** *"Crée le skill bug-fixer inspiré de pr-review-toolkit."*
**Comportement :** L'agent charge `anthropic-skill-patterns.md`, extrait le pattern de revue consolidée, rédige les 10 rubriques avec `inspiration: { plugin: "pr-review-toolkit" }`, exécute `validate-skills.js`, et enregistre la compétence dans `catalog.yaml`.

**Exemple d'invocation B (Règle) :** *"Ajoute une règle imposant l'affichage des skills utilisés à chaque fin de message."*
**Comportement :** L'agent applique le Parcours C de `plugin-dev`, rédige `.agents/rules/affichage-skills.md` et `AGENTS.md`, met à jour `critical-rules.md` du Hub, et applique immédiatement le format requis.
