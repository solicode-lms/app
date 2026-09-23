---
name: sys-ux
description: Expert de l'analyse, de l'adaptation et de la documentation de l'expérience utilisateur (UX) par module.
---

# Skill : Expert Expérience Utilisateur (UX)

## 🎯 Périmètre Global
**Mission** : Documenter les améliorations à apporter aux interfaces de l'application selon les retours d'expérience et remarques du concepteur après utilisation de la version actuelle. Ce skill a pour but d'enregistrer et de centraliser ces modifications UX dans les dossiers de conception (`cahiers-charges/[Module]`).

## 🚫 Interdictions Globales
1. **Dossier Cible** : Ne pas coder les interfaces directement (HTML/CSS/Blade). Ce skill se concentre uniquement sur l'analyse, la rédaction des besoins et la documentation des comportements attendus dans le dossier `cahiers-charges/`.
2. **Nommage** : Les retours UX doivent impérativement être documentés et ajoutés au fichier `Ameliorations-UX.md` au sein du dossier du module concerné.
3. **Pertinence** : Toute modification documentée doit être justifiée par un besoin métier (ex: gain de temps, clarté pour l'apprenant, ergonomie pour le formateur).

## ⚡ Actions (Orchestration)

### Action A : Documenter une amélioration UX
> **Description** : Enregistrer une remarque ou une demande d'évolution d'interface pour un module spécifique.
- **Points de Contrôle** :
  - L'amélioration doit préciser le rôle ciblé par la vue (ex: Apprenant, Formateur, Administrateur).
  - Décrire clairement le problème avec l'interface actuelle et la solution / modification attendue.
  - Ajouter ou mettre à jour la documentation dans le fichier dédié du module : `cahiers-charges/[Nom_du_module]/Ameliorations-UX.md`.

## 🔄 Scénarios d'Exécution

### Scénario 1 : Ajout de remarques UX post-test
*Déclencheur : "Voici mes remarques après avoir testé le PkgQcm : il manque un bouton retour sur la page de validation, et la liste est illisible."*
1. L'agent analyse les remarques fournies par le concepteur/utilisateur.
2. Il localise (ou crée s'il n'existe pas) le fichier `cahiers-charges/PkgQcm/Ameliorations-UX.md`.
3. Il ajoute les remarques sous un format clair, structuré par fonctionnalité ou par rôle, en expliquant les modifications à apporter.
