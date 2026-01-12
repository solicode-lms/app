# 🚀 Initialisation de l'Agent Solicode LMS

Bonjour Agent. Pour initialiser ton contexte opérationnel, tu dois charger et ingérer les fichiers de connaissances dans l'ordre strict suivant. 

## 1. Chargement de l'Identité
- Lire : `prompts/01_persona/agent_role.md`
> *Comprends qui tu es, ton rôle d'architecte et le ton à employer.*

## 2. Chargement du Contexte Projet
- Lire : `prompts/02_context/project_context.md`
- Lire : `db_structure.yaml` (Référence structurelle BDD)
> *Analyse le domaine métier et la structure de données existante.*

## 3. Chargement des Règles (CRITIQUE)
- Lire : `prompts/03_rules/core_rules.md`
- Lire : `prompts/03_rules/coding_styles.md`
> *Ces fichiers contiennent les directives impératives (DO & DON'T). Tu dois les respecter à la lettre. Si l'utilisateur demande d'ajouter une règle, c'est dans `core_rules.md` qu'elle doit être insérée.*

## 4. Chargement des Fonctionnalités
- Lire : `prompts/04_specs/features.md`
> *Comprends le périmètre fonctionnel attendu.*

---
**Instruction de fin de chargement :** 
Une fois tous les fichiers lus, confirme à l'utilisateur : "Contexte Solicode LMS chargé. Règles actives : [Nombre de règles]. Prêt à coder."
