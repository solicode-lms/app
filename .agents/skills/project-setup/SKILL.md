---
name: project-setup
description: Initialise l'agent pour un projet, analyse le codebase ou un cahier des charges, recommande les skills du Hub Global et identifie les manques.
version: 1.0.0
inspiration:
  source: "anthropic/claude-plugins-official"
  plugin: "claude-code-setup"
  pattern: "project onboarding & automated capability recommendation"
---

# 1. Mission
Ce skill est le point d'entrée pour initialiser et configurer l'environnement d'agent IA sur n'importe quel projet. Il analyse l'arborescence, les technologies et les cahiers des charges (`cahiers-charges/`), audite l'adéquation avec les compétences partagées, configure les liens symboliques et recommande les skills à activer ou à créer.

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Initialisation d'un nouveau projet pour l'agent ("Configure l'agent pour ce projet", "Initialise le setup agent").
  - Audit d'un cahier des charges pour recommander les compétences requises ("Analyse le cahier des charges de PkgFormation et dis-moi quels skills utiliser").
  - Vérification de l'état des liens symboliques et de la configuration `.agents/`.
- **Quand NE PAS l'activer :**
  - Développement direct de code ➔ Utiliser `feature-dev`.
  - Création physique d'un nouveau skill ➔ Utiliser `plugin-dev`.
  - Simple cartographie de code sans setup ➔ Utiliser `architecture-planner`.

# 3. Entrées & Ressources à Charger
- **Priorité 1 :** `.agents/local/project-conventions.md` (si existant).
- **Priorité 2 :** `.agents/hub/catalog.yaml` (catalogue officiel des skills).
- **Priorité 3 :** Fichiers de cahiers des charges dans `cahiers-charges/` ou documentation projet.

# 4. Contraintes & Règles Inviolables
- **Non-Destructif :** Ne jamais écraser une configuration locale `.agents/local/` existante sans confirmation explicite.
- **Principe d'Intégration Locale par Jonctions NTFS :** Le projet doit contenir toutes les ressources via des jonctions NTFS dans `.agents/` (`.agents/skills`, `.agents/references`, `.agents/standards`, `.agents/templates`, `.agents/scripts`, `.agents/hub`). Les skills travaillent et s'expriment toujours avec des chemins relatifs au projet.

# 5. Procédure Pas-à-Pas (Workflow de Setup & Audit)
1. **Étape 1 : Diagnostic de l'Environnement Projet :**
   - Vérifier la présence du dossier `.agents/` et tester si les jonctions NTFS existent (`.agents/skills`, `.agents/references`, `.agents/standards`, `.agents/templates`, `.agents/scripts`, `.agents/hub`).
   - Si les liens sont absents, poser les jonctions NTFS via `cmd /c mklink /J`.
2. **Étape 2 : Analyse de la Stack & des Cahiers des Charges :**
   - Identifier la stack technique (Laravel, Alpine.js, Vue, MySQL, Gapp).
   - Examiner les documents présents dans `cahiers-charges/` pour identifier les besoins fonctionnels et pédagogiques.
3. **Étape 3 : Recommandation des Skills du Hub :**
   - Mapper les besoins avec les compétences existantes du catalogue `catalog.yaml` (`architecture-planner`, `feature-dev`, `tutorial-writer`, etc.).
4. **Étape 4 : Détection des Manques & Feuille de Route :**
   - Identifier si un nouveau flux de travail doit être documenté ou si un nouveau skill doit être conçu par `plugin-dev`.
   - Proposer la création de fiches `AI_CONTEXT.md` pour les modules concernés via `context-generator`.

# 6. Gestion des Ambiguïtés & Décisions
- Si le dossier source du Hub Global n'est pas encore lié : demander son emplacement au développeur au premier démarrage pour initialiser les jonctions NTFS. Ensuite, tous les accès sont strictement internes au projet.
- Si le projet n'a pas de cahier des charges : analyser l'architecture des dossiers (ex: présence de `modules/`) pour en déduire les conventions.

# 7. Format de Sortie & Livrables
L'agent fournit un rapport de setup structuré :
- **État des Liens & Contexte :** Statut de `.agents/skills`, `.agents/references`, `.agents/local`.
- **Skills Recommandés à l'Activation :** Liste des compétences du Hub adaptées au projet.
- **Manques Détectés :** Nouveaux flux ou modules nécessitant une contextualisation.
- **Prochaine Action Recommandée :** Commande claire pour démarrer le développement.

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] Les liens symboliques NTFS vers le Hub Global sont-ils actifs et valides ?
- [ ] Le dossier physique .agents/local/ est-il bien présent et isolé du Hub ?
- [ ] La recommandation de skills s'appuie-t-elle sur le catalogue officiel catalog.yaml ?
- [ ] Les besoins non couverts sont-ils clairement signalés pour prise en charge ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Échec de création du lien symbolique | Droits manquants sur mklink /D | Utiliser impérativement mklink /J (jonction NTFS sans élévation) |
| Dupliquer physiquement les skills | Oubli du lien symbolique | Supprimer la copie locale et poser la jonction vers le Hub |

# 10. Exemples Concrets (Few-Shot)
**Exemple d'invocation :** *"Initialise l'agent pour le projet et analyse le cahier des charges de PkgQcm."*
**Comportement :** L'agent vérifie `.agents/`, lit `cahiers-charges/PkgQcm/`, constate qu'il s'agit d'un module de questionnaires avec persistance, recommande d'activer `feature-dev`, suggère de générer `AI_CONTEXT.md` avec `context-generator`, et confirme que le setup est opérationnel.
