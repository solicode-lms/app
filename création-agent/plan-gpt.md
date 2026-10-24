# Phase 1 — Analyse les projets et identifie les flux

Objectif : comprendre le travail réel avant de concevoir les Skills.

| Étape             | Action                                                                                               | Livrable                                    |
| ----------------- | ---------------------------------------------------------------------------------------------------- | ------------------------------------------- |
| A — Cadrer        | Définir les objectifs : conception, implémentation, maintenance, tests, documentation et tutoriels.  | Document de cadrage.                        |
| B — Recenser      | Collecter les tâches récurrentes, corrections, tickets et retours des développeurs.                  | Inventaire des tâches réelles.              |
| C — Explorer      | Examiner le dépôt, les configurations, les tests, les migrations, les documents et l'historique Git. | Inventaire des sources disponibles.         |
| D — Cartographier | Identifier les packages, couches, classes, relations, points d'entrée et composants partagés.        | Cartographie technique.                     |
| E — Reconstituer  | Tracer des tâches de bout en bout, avec les fichiers et les appels réellement impliqués.             | Fiches de flux vérifiées.                   |
| F — Diagnostiquer | Repérer les divergences entre code, documentation, tests et pratiques de développement.              | Registre des lacunes, risques et inconnues. |

Pour Active LMS, les premières opérations à analyser sont : ajouter un filtre à un CRUD, modifier un formulaire, ajouter une table et ses relations, faire évoluer une couche Service, corriger un bug et documenter un composant.

Les flux doivent être reconstruits à partir de preuves : code, tests, historique ou procédure confirmée. Une hypothèse ne doit pas être présentée comme un fait.

Critère de sortie : les principaux mécanismes sont cartographiés et les flux prioritaires sont identifiés.

# Phase 2 — Étudier les plugins et concevoir l'architecture

Objectif : réutiliser les meilleures idées des plugins existants sans importer inutilement leur complexité.

## G — Étudier les plugins de référence

Commence par trois modèles d'Anthropic.

[When and How to Use /feature-dev in Claude Code](https://images.openai.com/static-rsc-4/9xlPYw-7omOhYmYLoZJBVuR4-QI8Hbg9I33WDHLb6DzcP2r6XfJzIOknwXin8tUNRraRH2ClsbSMOphzU2OToklpmvYxynfBgvS-jQ0h8SZt5cI7IM_xQ8U-XdGdfhPcR3Q-wQEYZilo7TpYez1k0MvYctHs5qviLuFOY8J0YZk?purpose=inline)

Feature Dev — Développement de fonctionnalité

[Dépôt et documentation](https://github.com/anthropics/claude-plugins-official/tree/main/plugins/feature-dev?utm_source=chatgpt.com)

Étudier le parcours allant de la clarification du besoin à l'exploration du code, à la conception, à l'implémentation et à la revue. Le plugin propose un workflow structuré en sept phases, avec des responsabilités d'exploration, d'architecture et de revue.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://github.com\&sz=32)

GitHub

+1



[Claude Code Setup プラグイン 徹底解説 - コードベース分析で MCP / Skills / Hooks / Subagents / Slash Commands を自動レコメンド | プランタン - printemps tokyoブログ](https://images.openai.com/static-rsc-4/0e2gwWrn225dMyOPulOK4xmfzyMkQk8IBjOK_4nlxkJ3mk4h-O7rMs9n9b7emv0fLIactGuUaz9otm3Ek-lINIdiNuh_h2qRC2Hgkpeuzy6YuwTWJPwqpSrKs3-NSuW0QX3i4C-aNWkM313K64bm1zqU--FOU6r1HKc1bhufo8k?purpose=inline)

Claude Code Setup — Analyse du projet

[Dépôt et documentation](https://github.com/anthropics/claude-plugins-official/tree/main/plugins/claude-code-setup?utm_source=chatgpt.com)

Étudier comment analyser un dépôt et recommander des automatisations adaptées sans modifier directement les fichiers durant cette analyse. Cette approche inspire le premier Skill `project-analyzer`.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://github.com\&sz=32)

GitHub



[The 7 Claude Code Commands I Run Before Every Pull Request | by Suresh Kumar Ariya Gowder | Think in AI Agents | Apr, 2026 | Medium](https://images.openai.com/static-rsc-4/Pq6Jl9s1hAyi_dp6LGR3W0f7PKRXbjAWZa6U7qxA42CPuELwilwCjqXSNtLq-KPV9h-2eDUQKFa0r5is-431Q4E9mM8AOOT6xlUtKQC9bsU_SiuOIJjJj8RWmc8bdmw6AvN6MSCprlDRfuM_27usA2yXKgFYvNFn4DGtAwp6qu8?purpose=inline)

PR Review Toolkit — Contrôle du code

[Dépôt et documentation](https://github.com/anthropics/claude-plugins-official/tree/main/plugins/pr-review-toolkit?utm_source=chatgpt.com)

Étudier la séparation des contrôles : qualité générale, tests, erreurs silencieuses, types, commentaires et simplification du code. On réutilise ces perspectives sans nécessairement créer un Skill par contrôleur.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://github.com\&sz=32)

GitHub

+1



Dans un deuxième temps, examine `code-simplifier`, `frontend-design` et `security-guidance` pour les besoins de refactorisation, de conception d'interfaces et de sécurité. Pour les tests navigateur ou la documentation actualisée, évalue séparément des outils comme Playwright et Context7.

Le dépôt officiel avertit qu'il faut examiner les composants, les serveurs et les logiciels inclus dans un plugin avant de lui faire confiance. Ces ressources sont donc des modèles à étudier, pas des éléments à installer aveuglément.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://github.com\&sz=32)

GitHub



## H — Extraire les pratiques utiles

Pour chaque plugin étudié, identifie les éléments à conserver :

- le déclencheur et le périmètre ;
- les étapes de travail ;
- les rôles éventuels des agents spécialisés ;
- les formats de sortie ;
- les contrôles qualité ;
- les outils et permissions nécessaires.

Livrable : une matrice « pratique observée → besoin Active LMS → adaptation retenue ».

## I — Définir les responsabilités des Skills

Regrouper les tâches selon des résultats cohérents, sans créer un Skill par technologie, classe ou micro-opération.

## J — Organiser les règles et références

Séparer les règles communes, les profils techniques, les connaissances propres à un projet et les instructions opérationnelles.

## K — Définir les contrats des Skills

Pour chaque Skill, spécifier la mission, les situations d'activation, les entrées, les sorties, les limites, les références et les critères de réussite.

## L — Initialiser le référentiel central

Créer le dépôt Git, le catalogue, les modèles, l'historique des versions et les outils de validation.

Critère de sortie : chaque Skill candidat possède une mission distincte, une valeur démontrable et un contrat de sortie défini.

# Phase 3 — Construire la bibliothèque initiale

Objectif : créer les Skills prioritaires à partir des flux vérifiés et des méthodes qui fonctionnent.

## M — Sélectionner les Skills pilotes

Je recommande de commencer avec trois Skills :

1. `project-analyzer` — analyser un projet existant.
2. `feature-implementer` — réaliser une fonctionnalité en respectant l'architecture du projet.
3. `active-tutorial-writer` — produire un tutoriel technique progressif et vérifiable.

Ils couvrent tes trois besoins fondamentaux : comprendre, réaliser et transmettre.

## N — Extraire l'expertise réelle

Pour chaque pilote, utiliser les tâches réussies, les corrections humaines, les fichiers existants, les tickets et les tests.

Cette étape est essentielle : les bonnes pratiques officielles recommandent de construire les Skills à partir de l'expérience réelle et des artefacts du projet, puis de les affiner par des exécutions réelles plutôt que de produire des instructions génériques.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://agentskills.io\&sz=32)

Agent Skills



## O — Rédiger les instructions

Appliquer un modèle commun :

```
---
name: feature-implementer
description: Use when implementing or extending a feature
  in an existing project, following its architecture and conventions.
---

# Mission

# Déclenchement et limites

# Entrées et préconditions

# Références à consulter

# Procédure

# Règles et pièges connus

# Format de sortie

# Contrôles et critères de réussite
```

La description est particulièrement importante : elle indique à l'agent quand charger le Skill. Elle doit décrire l'intention de l'utilisateur et distinguer les cas où le Skill est utile de ceux où il ne l'est pas.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://agentskills.io\&sz=32)

Agent Skills



## P — Ajouter les ressources nécessaires

Chaque Skill reçoit uniquement les ressources dont il a besoin :

- `references/` pour les conventions détaillées et les API ;
- `templates/` pour les formats de sortie répétitifs ;
- `scripts/` pour les contrôles déterministes ;
- `evals/` pour les cas de test.

Ces dossiers sont facultatifs. Ne les crée que si leur contenu est utile.

Pour Active LMS, les références doivent documenter les packages, relations de classes, composants et mécanismes réels. Le Skill d'extension CRUD devra consulter les procédures correspondantes au lieu d'inventer sa propre implémentation.

Critère de sortie : les trois pilotes sont rédigés et peuvent être exécutés sur de vraies tâches.

# Phase 4 — Tester et améliorer les Skills

Objectif : prouver qu'un Skill améliore les résultats et qu'il est activé dans les bonnes situations.

## Q — Contrôler la structure

Automatiser les contrôles possibles : métadonnées, noms, chemins, liens, fichiers requis et syntaxe. Ajouter une vérification des références manquantes.

## R — Tester les résultats

Créer au départ deux ou trois cas représentatifs par Skill, chacun comprenant une demande réaliste, le résultat attendu et les fichiers d'entrée nécessaires.

Prévoir des cas incomplets ou limites. Les évaluations structurées permettent ensuite d'ajouter des assertions vérifiables, au lieu de juger les résultats uniquement à l'impression générale.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://agentskills.io\&sz=32)

Agent Skills



## S — Tester les déclencheurs

Pour les Skills dont la sélection automatique est prise en charge, construire un jeu de requêtes positives et négatives.

Le guide officiel propose environ vingt requêtes comme cible de test : 8 à 10 demandes qui devraient déclencher le Skill, et 8 à 10 demandes proches qui ne devraient pas le déclencher. Ajuste ce volume selon le nombre de Skills et les conséquences d'une mauvaise sélection.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://agentskills.io\&sz=32)

Agent Skills



Exemples pour `feature-implementer` :

| Demande                                                        | Résultat attendu                                                    |
| -------------------------------------------------------------- | ------------------------------------------------------------------- |
| Ajouter un filtre au CRUD existant d'Active LMS.               | Activation.                                                         |
| Implémenter un nouveau workflow métier dans plusieurs couches. | Activation.                                                         |
| Expliquer ce qu'est une clé étrangère sans modifier le projet. | Pas d'activation automatique du workflow d'implémentation.          |
| Corriger une faute de frappe dans un commentaire.              | Pas nécessairement d'activation ; une réponse directe peut suffire. |

## T — Comparer avec une référence

Exécuter les mêmes cas avec le Skill, puis sans celui-ci ou avec sa version précédente. Utiliser un contexte propre pour chaque essai afin d'éviter que les résultats bénéficient d'informations déjà présentes dans la conversation de conception du Skill.

Comparer la qualité, les erreurs, les vérifications effectuées et, si disponibles, les coûts et durées. Cette comparaison est explicitement recommandée dans le guide d'évaluation officiel.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://agentskills.io\&sz=32)

Agent Skills



## U — Corriger et rejouer les tests

Examiner les résultats et les traces d'exécution pour repérer les instructions vagues, étapes superflues, erreurs récurrentes et consignes qui ne s'appliquent pas à certains cas.

Modifier le Skill, rejouer les tests, puis comparer avec la référence. Une correction n'est validée qu'après vérification de son effet.

Critère de sortie : les critères essentiels passent, les erreurs critiques sont résolues et les résultats sont suffisamment stables pour publier la version.

# Phase 5 — Publier et intégrer dans les projets

Objectif : distribuer les Skills génériques sans perdre les particularités de chaque application.

## V — Versionner

Utiliser Git et une version identifiable pour chaque publication.

- `PATCH` : correction compatible ;
- `MINOR` : ajout compatible ;
- `MAJOR` : changement incompatible.

Les dépendances et versions doivent être suivies dans le catalogue. Ne présume pas que tous les agents comprennent les métadonnées personnalisées de version ou de dépendance dans le `SKILL.md`.

## W — Publier la bibliothèque centrale

La structure cible peut être :

```
skills-library/
├── core/
├── profiles/
├── .agents/
│   └── skills/
├── blueprints/
├── evals/
├── scripts/
├── catalog.yaml
└── CHANGELOG.md
```

Le dépôt central conserve les règles communes et les sources officielles des Skills génériques. Un script peut préparer les versions distribuées avec leurs ressources nécessaires.

## X — Intégrer dans Active LMS et les projets futurs

Chaque projet dispose de ses instructions, profils et connaissances spécifiques :

```
Active-LMS/
├── AGENTS.md
├── docs/
│   └── active-lms/
│       ├── architecture/
│       ├── database/
│       ├── packages/
│       ├── crud/
│       ├── components/
│       └── workflows/
└── .agents/
    └── skills/
        └── active-lms-crud-extension/
            └── SKILL.md
```

Le projet conserve la version des Skills génériques qu'il utilise. Les nouveautés sont intégrées après vérification, pas automatiquement à chaque changement du dépôt central.

Critère de sortie : une version publiée a été intégrée à Active LMS et a réussi au moins un scénario complet, par exemple l'ajout d'un filtre à un CRUD.

# Phase 6 — Maintenir la bibliothèque et généraliser

Objectif : transformer les retours d'utilisation en améliorations contrôlées et rendre le processus reproductible pour les nouveaux projets.

## Y — Organiser la maintenance continue

Pour chaque problème observé, appliquer cette procédure :

1. Enregistrer le problème et la demande d'origine.
2. Reproduire le défaut avec le Skill concerné.
3. Déterminer si la cause vient du Skill, des références, du projet ou de l'agent.
4. Ajouter un test de non-régression.
5. Corriger les instructions, le modèle ou la référence concernée.
6. Rejouer les tests et enregistrer la version validée.

Les problèmes récurrents doivent conduire à une amélioration structurelle. Une erreur critique doit être corrigée immédiatement ; il ne faut pas attendre qu'elle se reproduise.

## Z — Réutiliser le système sur un nouveau projet

Pour chaque nouveau projet :

1. Lancer `project-analyzer`.
2. Cartographier l'architecture, les composants, les données et les flux réels.
3. Sélectionner les Skills génériques pertinents.
4. Préparer les profils techniques et les références du projet.
5. Créer un Skill local uniquement si un flux distinct le justifie.
6. Tester l'intégration, publier la version adoptée et suivre les retours.

Le nouveau projet bénéficie ainsi des procédures déjà validées sans hériter de conventions spécifiques à Active LMS.

# 3. Catalogue cible après validation des pilotes

Les trois pilotes initiaux doivent être évalués avant d'étendre le catalogue. Voici les autres responsabilités à envisager ensuite :

| Skill candidat           | Rôle                                                     | Inspiration                                      |
| ------------------------ | -------------------------------------------------------- | ------------------------------------------------ |
| `project-analyzer`       | Analyser le projet et ses flux.                          | Claude Code Setup                                |
| `arch-feature-spec`      | Concevoir les données, l'architecture et les contrats.   | Feature Dev                                      |
| `feature-implementer`    | Implémenter une fonctionnalité complète.                 | Feature Dev                                      |
| `code-reviewer`          | Repérer les défauts et vérifier les conventions.         | Code Review / PR Review Toolkit                  |
| `code-refactor`          | Simplifier le code sans modifier son comportement.       | Code Simplifier                                  |
| `test-validator`         | Contrôler les résultats et les régressions.              | PR Review Toolkit et outils de test              |
| `security-auditor`       | Rechercher des risques de sécurité.                      | Security Guidance                                |
| `technical-documenter`   | Actualiser la documentation technique.                   | Contrôle des commentaires et de la documentation |
| `active-tutorial-writer` | Rédiger un tutoriel progressif et vérifiable.            | Ton référentiel pédagogique                      |
| `practical-lab-creator`  | Produire un atelier pratique ou une évaluation.          | Ton référentiel pédagogique                      |
| `skill-auditor`          | Contrôler la qualité et la cohérence de la bibliothèque. | Plugin Dev et bonnes pratiques Agent Skills      |

Cette liste est un catalogue de capacités potentielles, pas une obligation de créer onze Skills. Certaines responsabilités pourront être regroupées si les tests montrent qu'elles partagent une procédure cohérente.

Il faut aussi distinguer les Skills des outils : Playwright sert à exécuter des tests de navigateur, Context7 à consulter de la documentation, et Git à versionner le code. Ce ne sont pas automatiquement des Skills supplémentaires.

# 4. Comment orchestrer le développement d'une fonctionnalité ?

Le modèle Feature Dev inspire un workflow de développement structuré. Pour Active LMS, je recommande ce parcours :

1\. Découverte

Comprendre la demande, les contraintes et les critères de réussite.

2\. Exploration

Examiner le code, les packages, les flux et les conventions.

3\. Clarification

Résoudre les ambiguïtés importantes.

4\. Conception

Définir la modification, les couches et les contrats nécessaires.

5\. Implémentation

Modifier les composants existants et respecter les mécanismes du projet.

6\. Revue

Contrôler les défauts, la sécurité et la conformité architecturale.

7\. Validation

Exécuter les tests, corriger les écarts et documenter le résultat.

Les sept étapes sont inspirées du workflow de développement de Feature Dev ; leur adaptation à Active LMS est notre proposition.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://github.com\&sz=32)

GitHub



Ne déclenche pas systématiquement ce parcours complet pour une modification mineure. L'agent doit sélectionner le niveau de contrôle adapté au risque et à la complexité.

# 5. Les outils nécessaires

Commence avec une chaîne légère :

| Besoin                   | Outil                                                                            |
| ------------------------ | -------------------------------------------------------------------------------- |
| Édition et documentation | VS Code, Markdown et YAML                                                        |
| Bibliothèque centralisée | Git et GitHub ou GitLab                                                          |
| Contrôle structurel      | Markdownlint, Yamllint et scripts de validation                                  |
| Évaluation des Skills    | Jeux de tests, critères PASS/FAIL et comparaison des résultats                   |
| Documentation technique  | Références du projet et intégration documentaire si nécessaire                   |
| Tests logiciel           | Suite de tests du projet, plus Playwright si les tests navigateur sont utiles    |
| Automatisation           | Scripts et intégration continue, uniquement après validation du processus manuel |

L'automatisation doit intervenir après stabilisation des procédures, afin de ne pas automatiser un workflow mal défini.

# 6. Ordre d'exécution immédiat

Voici les prochaines actions que je recommande, dans l'ordre :

Livraison 1 — Analyse d'Active LMS

Effectuer A–F. Produire une cartographie du code, un inventaire des mécanismes et un catalogue des flux. Ne modifier aucun code applicatif pendant cette phase.

Livraison 2 — Étude des modèles et socle commun

Effectuer G–L. Extraire les pratiques utiles de Feature Dev, Claude Code Setup et PR Review Toolkit, puis créer les références communes et les modèles de Skills.

Livraison 3 — Trois Skills pilotes

Effectuer M–P. Construire `project-analyzer`, `feature-implementer` et `active-tutorial-writer` à partir de tâches réelles.

Livraison 4 — Évaluation

Effectuer Q–U. Tester la structure, le déclenchement, les résultats et les régressions, puis corriger les Skills.

Livraison 5 — Publication et généralisation

Effectuer V–Z. Publier, intégrer dans Active LMS, organiser les retours et définir la procédure pour chaque nouveau projet.

Décision finale : on commence par analyser Active LMS, puis on étudie les plugins de référence et extrait leurs méthodes utiles. On ne crée les Skills qu'après avoir identifié les flux réels. Chaque Skill est ensuite évalué sur des tâches concrètes avant d'être publié dans la bibliothèque centrale.

Les principes officiels qui guident cette méthode sont détaillés dans les [bonnes pratiques de création](https://agentskills.io/skill-creation/best-practices?utm_source=chatgpt.com), l'[évaluation des résultats](https://agentskills.io/skill-creation/evaluating-skills?utm_source=chatgpt.com) et l'[optimisation des descriptions](https://agentskills.io/skill-creation/optimizing-descriptions?utm_source=chatgpt.com).