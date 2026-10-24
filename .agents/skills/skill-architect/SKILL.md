---
name: skill-architect
description: Conçoit, crée ou raffine des skills IA en s'inspirant des modèles officiels d'Anthropic, gère les flux de travail et maintient la conformité SemVer.
version: 1.0.0
inspiration:
  source: "anthropic/claude-plugins-official"
  plugin: "plugin-dev"
  pattern: "skill scaffolding, trigger tuning & continuous refinement"
---

# 1. Mission
Ce méta-skill assure la gouvernance technique, la conception, la création et le raffinement continu de tous les Skills du Hub Global (`skills-workspace`). Il applique systématiquement les motifs de référence d'Anthropic (`feature-dev`, `claude-code-setup`, `pr-review-toolkit`), veille à la traçabilité des inspirations, documente les nouveaux flux de travail et enregistre les corrections sous forme de fiches d'incidents YAML.

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Demande de création d'un nouveau skill ("Crée un skill pour gérer les tests automatisés", "Ajoute un skill concevoir-architecture").
  - Raffinement ou correction d'un skill existant suite à un retour du développeur ("Modifie le skill implementer-fonctionnalite pour interdire X").
  - Documentation d'un nouveau flux de travail dans `references/workflows/`.
  - Audit de conformité des skills et vérification des versions SemVer.
- **Quand NE PAS l'activer :**
  - Développement applicatif métier (PHP, Blade, JS) ➔ Utiliser `implementer-fonctionnalite`.
  - Initialisation d'agent pour un projet ➔ Utiliser `agent-setup`.
  - Documentation d'un package applicatif ➔ Utiliser `documenter-package`.

# 3. Entrées & Ressources à Charger
- **Priorité 1 :** `.agents/references/anthropic/anthropic-skill-patterns.md` (Motifs officiels Anthropic).
- **Priorité 2 :** `D:\solicode-lms\skills-workspace\templates/generic-skill.md` (Gabarit en 10 rubriques).
- **Priorité 3 :** `D:\solicode-lms\skills-workspace\catalog.yaml` (Catalogue officiel des compétences).
- **Priorité 4 :** `D:\solicode-lms\skills-workspace\templates/workflow-blueprint.md` (Modèle de flux).

# 4. Contraintes & Règles Inviolables
- **Bloc `inspiration` Obligatoire :** Tout skill créé ou modifié DOIT impérativement comporter la métadonnée `inspiration:` dans son frontmatter YAML avec sa source, son plugin de référence et son pattern.
- **Respect Strict des 10 Rubriques :** Aucun skill ne peut être publié s'il omet l'une des 10 rubriques normées.
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

# 6. Gestion des Ambiguïtés & Décisions
- Si le motif Anthropic n'est pas évident : adopter par défaut le motif `feature-dev` (workflow multi-phases sécurisé).
- Si la règle demandée est exclusive à Solicode LMS : ne pas modifier le skill générique, mais orienter l'ajout vers `.agents/local/project-conventions.md`.

# 7. Format de Sortie & Livrables
- Le fichier `SKILL.md` complet ou son diff clair.
- La mise à jour de `catalog.yaml` et `CHANGELOG.md`.
- Le résultat d'exécution du script `validate-skills.js` (0 erreur).

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] Le frontmatter YAML contient-il le bloc inspiration complet (source, plugin, pattern) ?
- [ ] La description est-elle à la 3ème personne et précise-t-elle quand activer et quand ne pas activer ?
- [ ] Les 10 rubriques normées sont-elles toutes présentes et renseignées ?
- [ ] Le script node scripts/validate-skills.js passe-t-il avec succès ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Oubli de la métadonnée d'inspiration | Inattention | Ajouter systématiquement le bloc inspiration dans le frontmatter |
| Description vague ("Expert pour tout faire") | Mauvais cadrage | Remplacer par des verbes d'action et des cas limites explicites |

# 10. Exemples Concrets (Few-Shot)
**Exemple d'invocation :** *"Crée le skill diagnostiquer-et-corriger inspiré de pr-review-toolkit."*
**Comportement :** L'agent charge `anthropic-skill-patterns.md`, extrait le pattern de revue consolidée, rédige les 10 rubriques avec `inspiration: { plugin: "pr-review-toolkit" }`, exécute `validate-skills.js`, et enregistre la compétence dans `catalog.yaml`.
