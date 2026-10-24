# Référence : Motifs de Conception des Plugins & Skills Anthropic
 
Ce document consigne les bonnes pratiques et patterns issus du dépôt officiel d'Anthropic (`claude-plugins-official`) et des standards ouverts `agentskills.io`. Il sert de guide pour concevoir et faire évoluer les skills du Hub.

---

## 1. Les 5 Motifs de Référence Anthropic

### 1.1 Motif `feature-dev` (Développement Séquencé & Sécurisé)
- **Objectif :** Guider l'implémentation d'une fonctionnalité complète à travers plusieurs couches.
- **Principe clé :** Interdiction d'écrire du code applicatif avant d'avoir clarifié le besoin et vérifié les contraintes architecturales.
- **Workflow typique :** Exploration ➔ Clarification ➔ Conception des contrats ➔ Implémentation chirurgicale ➔ Validation de non-régression.
- **Adaptation Solicode LMS :** Vérification systématique des en-têtes de lock Gapp (`ESSARRAJ Fouad`), orientation vers `custom/` pour Blade, et préservation des classes `Base/`.

### 1.2 Motif `claude-code-setup` (Audit & Diagnostic Read-Only)
- **Objectif :** Examiner un dépôt ou un projet pour cartographier la stack et recommander les automatisations adéquates.
- **Principe clé :** Opération 100 % en lecture seule. Aucune altération de fichier durant l'audit.
- **Adaptation Solicode LMS :** Utilisé dans les skills `agent-setup` et `analyser-et-planifier` pour auditer le projet ou les cahiers des charges sans risque.

### 1.3 Motif `plugin-dev` (Méta-Gouvernance des Skills)
- **Objectif :** Créer, tester et auditer de nouveaux plugins et compétences pour les agents.
- **Principe clé :** Utilisation d'un gabarit normé en rubriques ordonnées, définition de triggers positifs/négatifs, et validation déterministe par scripts.
- **Adaptation Solicode LMS :** Modèle de base pour le skill `skill-architect`.

### 1.4 Motif `pr-review-toolkit` (Contrôle Multi-Perspectives Consolidé)
- **Objectif :** Contrôler la qualité sous plusieurs angles (sécurité, tests, typage, simplification).
- **Principe clé :** Transformer les vérifications complexes en checklists binaires (PASS/FAIL) claires et mesurables.
- **Adaptation Solicode LMS :** Intégré dans la Rubrique #8 (Contrôle Qualité) de chaque skill.

### 1.5 Motif `code-simplifier` (Refactorisation sans Changement Fonctionnel)
- **Objectif :** Réduire la dette technique et le code mort sans jamais altérer le comportement externe.

---

## 2. Standardisation de la Métadonnée d'Inspiration

Chaque skill créé ou mis à jour dans le Hub doit obligatoirement déclarer sa filiation dans son frontmatter YAML :

```yaml
inspiration:
  source: "anthropic/claude-plugins-official" # ou "agentskills.io" ou "solicode-pedagogy"
  plugin: "feature-dev"                        # nom du plugin ou modèle de référence
  pattern: "5-phase implementation & progressive disclosure"
```

Cette traçabilité garantit la clarté architecturale et l'alignement sur les standards de l'industrie.
