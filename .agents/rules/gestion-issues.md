---
trigger: always_on
priority: 2
---

# Organisation et Cycle de Vie des Issues (`issues/`)

## 1. Emplacement & Organisation par Sous-Dossiers
- Toutes les issues (anomalies, spécifications, remarques, tâches à réaliser) doivent impérativement être enregistrées dans le dossier `issues/`.
- **Organisation obligatoire par sous-dossiers :** Il est formellement interdit de créer ou laisser des fichiers d'issues directement à la racine de `issues/`.
- Les issues doivent être classées dans un sous-dossier correspondant au module, package ou domaine fonctionnel concerné (ex. : `issues/PkgCore/`, `issues/PkgCreationTache/`, `issues/PkgTableauBord/`, `issues/PkgQcm/`).
- Convention de nommage d'un fichier d'issue : `[Numero].[Titre-Court].md` (ex. : `1.Pas-droit-acces.md`, `3.Creation-Tache.md`).

## 2. Clôture et Déplacement des Issues Terminées
- Dès qu'une issue est traitée, résolue ou validée :
  - Le fichier Markdown correspondant **doit obligatoirement être déplacé** vers le répertoire :
    `issues/issues-closed/`
  - La structure de sous-dossier doit être rigoureusement conservée lors du déplacement :
    `issues/<SousDossier>/<issue>.md` ➔ `issues/issues-closed/<SousDossier>/<issue>.md`
  - Si le sous-dossier de destination n'existe pas encore sous `issues/issues-closed/`, il doit être créé automatiquement.
- Aucune issue terminée ne doit subsister dans les sous-dossiers actifs de `issues/`.
