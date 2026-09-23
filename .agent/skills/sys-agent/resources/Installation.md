# Gestion du Skill : Expert Agent

Ce guide détaille les commandes pour installer, mettre à jour et contribuer au skill `expert-agent` via Git Subtree.

## 1. Pré-requis

Ajouter le dépôt distant de la bibliothèque de skills (une seule fois par projet).

```bash
git remote add -f skills-lib https://github.com/labs-web/agent-skills.git
```

## 2. Installation

Installer le skill dans le dossier `.agent/skills/expert-agent`.

```bash
git subtree add --prefix .agent/skills/expert-agent skills-lib expert-agent --squash
```

## 3. Mise à jour (Pull)

Récupérer les dernières modifications depuis le dépôt distant.

```bash
git subtree pull --prefix .agent/skills/expert-agent skills-lib expert-agent --squash
```

## 4. Contribution (Push)

Envoyer les modifications locales vers le dépôt distant pour les partager.

```bash
git subtree push --prefix .agent/skills/expert-agent skills-lib expert-agent
```