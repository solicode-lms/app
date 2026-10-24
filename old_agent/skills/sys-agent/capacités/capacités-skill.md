# Capacité : Gestion des Skills

## 1. Structure Obligatoire

Un Skill valide doit respecter la structure suivante :
- **Dossier** : `.agent/skills/[nom-du-skill]/`.
- **Définition** : Fichier `SKILL.md` à la racine du dossier.
- **Ressources** : Dossier `resources/` contenant les templates, scripts, ou documentations spécifiques.

## 2. Validation & Standards

### Nommage du Skill
- **Format** : `kebab-case`.
- **Sémantique** : **DOIT** correspondre à un **Rôle Humain** ou un **Expert Métier** (ex: `analyste-uml`, `architecte-sie` `designer-ui`).
- **Interdiction** : Ne pas utiliser de verbes d'action ou de noms de processus (réservés aux Workflows).

### Contenu du Skill (`SKILL.md`)
- **En-tête YAML** : Doit contenir `name` et `description`.
- **Sections** :
  - `🎯 Objectif & Périmètre`
  - `📥 Entrées / 📤 Sorties` (Format liste ou définition, PAS de tableau Markdown complexe).
  - `🔄 Algorithme d'Exécution` (Étapes claires et séquentielles).
  - `⚠️ Règles d'Or` (Contraintes strictes).
- **Langue** : Français strict.
- **Chemins Relatifs Obligatoires (CRITIQUE)** : Ne JAMAIS inclure de chemins absolus (ex : `file:///c:/...`). Utiliser exclusivement des chemins relatifs par rapport au workspace ou au dossier du skill pour garantir la portabilité entre machines.

### Création d'Artefacts
- **RÈGLE CRITIQUE** : Tout artefact généré par l'agent, et en particulier le plan d'implémentation (`implementation_plan.md`), **DOIT ÊTRE RÉDIGÉ EN FRANÇAIS**.
- **Templates** : Utiliser `template-skill.md` (situé dans `.agent/skills/expert-agent/resources/`) comme base.

### Capacités (Savoir-Faire) dans `capacités/`
- **PRINCIPE** : Les fichiers de savoir-faire (Procédures, Standards, Checklists) doivent être stockés dans le dossier `capacités/`.
- **Indépendance** : Ces fichiers **DOIVENT être génériques** et **indépendants des livrables spécifiques**.
- **Interdiction de Couplage** : 
  - ❌ **NE PAS** nommer les fichiers d'après les livrables (ex: `capacité-analyse.md`, `capacité-use-case-v1.md`)
  - ✅ **UTILISER** des noms qui décrivent le **type de savoir-faire** (ex: `capacité-analyse-besoin.md`, `capacité-diagramme-uml.md`)
- **Réutilisabilité** : Une capacité doit pouvoir être invoquée par plusieurs actions.
- **Structure Recommandée** :
  - `capacité-format-[nom].md` : Pour les formats techniques
  - `capacité-methode-[nom].md` : Pour les méthodes d'analyse ou de conception
- **Exemple de Bonne Pratique** :
  - Au lieu de : `spec-analyse.md` dans `resources/`
  - Utiliser : `capacité-analyse-fichiers.md` dans `capacités/`

## 3. Algorithme de Refactoring

1. **Analyse** : Lire le `SKILL.md` existant.
2. **Comparaison** : Vérifier l'écart avec `template-skill.md`.
3. **Mise à niveau** :
   - Réorganiser les sections.
   - S'assurer que les modèles mentaux (Algorithme) sont clairs.
   - Vérifier que les ressources sont bien dans le dossier `resources/`.
4. **Validation** : Confirmer que le skill est complet et fonctionnel.
