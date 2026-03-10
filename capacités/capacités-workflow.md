# Capacité : Gestion des Workflows

## 1. Structure Obligatoire

Un Workflow valide doit respecter la structure suivante :
- **Emplacement** : `.agent/workflows/[nom-du-workflow].md`.
- **Format** : Fichier Markdown avec Frontmatter YAML.

## 2. Validation & Standards

### Nommage du Workflow
- **Format** : `kebab-case`.
- **Sémantique** : **DOIT** décrire une **Phase**, une **Tâche** ou une **Action** (ex: `analyse-uml`, `init-projet`, `raffinement-agent`).
- **Interdiction** : Ne pas utiliser de noms de rôles (réservés aux Skills).

### Contenu du Workflow
- **En-tête YAML** :
  - `description`: Résumé court de l'objectif.
- **Sections** :
  - `Contexte & Flux Global` : Vue d'ensemble.
  - `Exécution` : Étapes détaillées.
  - `Critères de Qualité` : Checklist de fin.
- **Flux** : Linéaire et unidirectionnel (Pas de boucles complexes).
- **Validation Humaine** : Chaque étape critique doit avoir un point de contrôle (STOP).

### Architecture Standard : Menu Interactif avec Routage Conditionnel
Pour les workflows pédagogiques et interactifs, l'architecture standard est basée sur un routage intelligent :
`[Analyse de la Demande]` → `[Confirmation Directe OU Menu Complet]` → `[Validation Humaine]` → `[Exécution]`

### Fonctionnement Détaillé

1. **Analyse de la Demande (Obligatoire)** : Analyser le message de l'utilisateur pour détecter l'action appropriée via mots-clés.
2. **Routage Conditionnel** :
   - **Cas 1 : Action Détectée** → Afficher directement la confirmation de l'action détectée (Format : "Action détectée : X - Nom, Voulez-vous procéder ?").
   - **Cas 2 : Aucune Action Détectée** → Afficher le menu complet avec toutes les options disponibles.
3. **Validation Humaine** : STOP pour attendre la confirmation/sélection du développeur (Lettre A/B/C/D...).
4. **Exécution Conditionnelle** : Appeler l'action choisie avec les inputs appropriés.

**Avantages** :
- **Efficacité** : Réduction des étapes si l'intention est claire (pas de menu superflu)
- **Découvrabilité** : Menu complet affiché si besoin (commande seule ou demande ambiguë)
- **Pédagogique** : Idéal pour l'apprentissage (contexte Lab)
- **Contrôle** : Validation humaine TOUJOURS requise avant exécution
- **Flexibilité** : Le développeur peut toujours choisir une autre option

**Exemple de Confirmation Directe** (Cas 1) :
```
📋 Demande Identifiée

Vous souhaitez créer un nouveau skill.

Action détectée : Action A - Gérer un Skill
→ Créer ou mettre à jour un skill dans `.agent/skills/`

Voulez-vous procéder avec cette action ? (Tapez A pour confirmer, ou choisissez une autre option B/C...)
```

**Exemple de Menu Complet** (Cas 2) :
```
> Actions disponibles (Skill : nom-du-skill) :
>
> A. Nom de l'Action A
> → Description courte de ce que fait l'action
>
> B. Nom de l'Action B
> → Description courte de ce que fait l'action
>
> Quelle action souhaitez-vous exécuter ? (Tapez A, B, C...)
```

### Principes d'Interaction Workflow/Skill
- **Rôle du Workflow (Orchestrateur)** :
  - Il **NE DOIT PAS** expliquer "comment" réaliser une tâche (c'est le rôle du Skill).
  - Il **NE DOIT PAS** répéter les instructions techniques du Skill.
  - Il **DOIT** préparer et organiser les **Données d'Entrée (Inputs)** pour l'action.
  - Il **DOIT** ordonner explicitement au Skill d'exécuter l'action.
- **Appel de Skill** :
  - Chaque étape impliquant une action doit préciser :
    1. **Le SKILL Cible** : Quel expert solliciter.
    2. **L'ACTION** : Quelle capacité activer.
    3. **Les INPUTS** : Les données préparées nécessaires à l'action.

### Annotations Spéciales
- `// turbo` : Autorise l'exécution automatique d'une commande spécifique.

### Workflow de Création/Optimisation
1. **Visualiser** le processus de bout en bout (Penser "Orchestration" et non "Procédure").
2. **Suivre le Pattern** : Utiliser le modèle "Menu Interactif" défini dans le template.
3. **Utiliser** le template approprié : `template-workflow-creation.md` (Standard) ou `template-workflow-execution.md` (Skill-Exec).
4. **Simplifier** : Supprimer les étapes redondantes.
5. **Annoter** : Ajouter `// turbo` là où c'est sûr.
6. **Vérifier** : S'assurer que le workflow ne "bloque" pas l'agent dans une boucle infinie.
