---
description: Scanne un fichier pour exécuter les directives "ia :" intégrées.
---

# Workflow : Application des Directives (`/agent`)

## 1. Contexte & Objectif
**Objectif** : Scanner un fichier pour identifier, interpréter et exécuter les directives contenues dans les commentaires `ia :` (ou `IA :`, `Ia :`).
**Stratégie** : Détection automatique des tâches et des compétences requises, validation unique par le développeur, exécution groupée.

## 2. Exécution

### Étape 1 : Analyse & Détection
**Scanner le fichier cible** fourni en argument (ou demander le chemin).
1. **Lire** le contenu complet du fichier.
2. **Extraire** tous les commentaires contenant le pattern `ia :` (insensible à la casse).
3. **Pour chaque directive trouvée** :
   - Analyser le texte de la directive.
   - **Déduire** le Skill ou Workflow le plus pertinent (ex: "créer un endpoint" -> `developpeur-http`, "analyser le besoin" -> `analyste-uml`).
   - Si un Skill/Workflow est explicitement mentionné (ex: `(designer-ui)`), l'utiliser en priorité.

### Étape 2 : Planification & Validation (Instruction Unique)
**Présenter le plan d'action consolidé** au développeur pour une validation globale.
**Format attendu** :
```
📋 Directives Détectées (Fichier : [Nom du fichier])

1. Ligne [X] : "[Directive brute]"
   → Action proposée : [Description Action]
   → Skill/Workflow détecté : [Nom du Skill/Workflow]

2. Ligne [Y] : ...

Voulez-vous exécuter ce plan ? (Tapez 'oui' pour valider)
```
**STOP** : Attendre la validation explicite.

### Étape 3 : Exécution Groupée
**Si validé** :
1. **Itérer** sur chaque directive validée.
2. **Exécuter** la tâche en déléguant au Skill ou Workflow identifié.
   - Utiliser les outils adéquats (`multi_replace_file_content`, `run_command`, etc.) ou invoquer le skill via son workflow.
3. **Confirmer** la fin de chaque traitement.

### Étape 4 : Nettoyage & Clôture
**Pour chaque directive traitée avec succès** :
1. **Supprimer** proprement le commentaire contenant la directive `ia :` dans le fichier source.
   - Utiliser `replace_file_content` en ciblant le bloc exact.
2. **Confirmer** au développeur : "✅ Directive exécutée et nettoyée."