# Procédure d'Onboarding & Bootstrapping d'un Projet Vierge

> **Document de référence — Workflow d'Initialisation Multi-Projets**  
> **Composant :** `project-setup` v2.0.0 (Progressive Disclosure Resource)

---

## 1. Cas 1 : Amorcer un Nouveau Projet Vierge (Sans dossier `.agents` ni aucun skill)

Lorsqu'un développeur crée un tout nouveau projet (ex: `c:\AppServer\nouveau-projet`, ou `c:\Users\essarraj\mon-projet-tutoriel`), le projet ne possède encore aucun dossier `.agents` ni aucun skill local.

Pour permettre à l'agent IA de démarrer, deux méthodes simples d'amorce (bootstrapping) sont disponibles :

### Méthode A (Recommandée — Commande 1-Ligne via Node.js)
Ouvrez le terminal dans la racine de votre nouveau projet et lancez la commande d'amorce fournie par le Hub :

```powershell
node D:\solicode-lms\skills-workspace\skills\project-setup\scripts\manage-skills.cjs bootstrap
```

**Actions automatiques réalisées par la commande :**
1. Création de l'arborescence `.agents/skills/`.
2. Déploiement physique du skill d'amorce `.agents/skills/project-setup` (Solution B - Compatible Antigravity).
3. Création du manifeste initial `.agents/skills.json` configuré avec `["project-setup"]`.

---

### Méthode B (Alternative Manuelle — PowerShell natif)
Si Node.js n'est pas invoqué directement :

```powershell
# 1. Créer le dossier d'accueil
New-Item -ItemType Directory -Force -Path ".agents\skills"

# 2. Copier physiquement le skill d'amorce (Solution B - Compatible Antigravity)
Copy-Item -Path "D:\solicode-lms\skills-workspace\skills\project-setup" -Destination ".agents\skills\project-setup" -Recurse -Force
```

---

## 2. Cas 2 : Déclenchement de l'Agent IA & Qualification

Une fois l'amorce posée, ouvrez le projet dans votre IDE (Antigravity IDE, Cursor, Claude Code, VS Code) et adressez-vous à votre agent :

> 💬 **Prompt utilisateur d'amorce :**  
> *« Initialise l'agent pour ce projet »* ou *« Analyse le projet et installe les compétences nécessaires »*

L'agent charge automatiquement le skill [project-setup](../SKILL.md) et déroule le protocole suivant :

---

## 3. Déroulement du Workflow d'Onboarding (5 Étapes)

```mermaid
sequenceDiagram
    autonumber
    actor Dev as Développeur
    participant Agent as Agent IA (project-setup)
    participant Hub as Hub Global (D:)
    participant Local as Projet Local (.agents/)

    Dev->>Agent: "Initialise l'agent pour ce projet"
    Agent->>Local: Détection des fichiers caractéristiques & cahier des charges
    alt Projet Vierge ou Sans Cahier des Charges
        Agent->>Dev: Pose questions interactives de qualification (Dev / Tutos / APC)
        Dev->>Agent: Répond avec la finalité du projet
    end
    alt Dossier old_agent / .agent existant détecté
        Agent->>Local: Audit, tri passif/actif et déplacement vers archive/old_agent
    end
    Agent->>Local: Génération du manifeste .agents/skills.json
    Agent->>Local: Exécution de node .agents/skills/project-setup/scripts/manage-skills.cjs install
    Local->>Hub: Déploiement physique des compétences sélectionnées (Solution B)
    Agent->>Local: Génération de .agents/references/conventions/project-conventions.md
    Agent->>Dev: Présentation du Rapport d'Onboarding & Checklist
```

### Étape 1 : Diagnostic & Analyse du Projet
- Scanner la racine du projet (fichiers de code, documentation, `cahier-des-charges.md`, `README.md`).
- Consulter la grille de [project-typology-and-profiles.md](project-typology-and-profiles.md) pour classifier le projet :
  - **Profil 1 — Application Dev Logiciel**
  - **Profil 2 — Rédaction de Tutoriels & Autoformations**
  - **Profil 3 — Ingénierie Pédagogique & Ateliers APC**
  - **Profil 4 — Projet Hybride**

### Étape 2 : Qualification Interactive (Si besoin)
Si aucun indice probant n'apparaît, l'agent pose la question de qualification au développeur :
*« Quel est l'objectif principal de ce projet : développement applicatif, rédaction de tutoriels/cours, ou conception d'ateliers pédagogiques ? »*

### Étape 3 : Audit & Recyclage Legacy (Si `old_agent` présent)
Si un dossier `old_agent` ou `.agent/` non normé préexiste :
- Appliquer le [legacy-migration-playbook.md](legacy-migration-playbook.md).
- Transférer les règles et fiches techniques vers `.agents/references/conventions/` ou sous-dossiers thématiques de `references/`.
- Déplacer l'ancien dossier dans `archive/old_agent/`.

### Étape 4 : Déploiement Granulaire & Synchronisation des Compétences
- Rédiger le fichier `.agents/skills.json` avec la liste exacte des skills retenus pour le profil.
- Exécuter la commande de synchronisation physique :
  ```bash
  node .agents/skills/project-setup/scripts/manage-skills.cjs sync
  ```
- *Remarque Antigravity :* Antigravity ne suivant pas les jonctions NTFS, la synchronisation physique (`sync`) garantit que chaque compétence globale du Hub est déployée sous forme de dossier physique autonome tout en conservant ses dépendances `.agents/references/` à jour.

### Étape 5 : Formalisation du Cadre Local & Bilan
- Rédiger `.agents/references/conventions/project-conventions.md` récapitulant les directives propres au projet.
- Présenter le rapport d'onboarding récapitulant les compétences actives, exclues, et la marche à suivre.

---

## 4. Maintenance Continue : Synchronisation des Compétences

Au fil de la vie du projet, lorsque de nouvelles améliorations sont publiées dans le Hub global (`skills-workspace`) :

```powershell
# Récupérer l'ensemble des compétences globales (Hub -> Projet)
node .agents/skills/project-setup/scripts/manage-skills.cjs pull

# Pousser l'ensemble des compétences globales vers le Hub (Projet -> Hub)
node .agents/skills/project-setup/scripts/manage-skills.cjs push

# Pousser ou mettre à jour une compétence spécifique
node .agents/skills/project-setup/scripts/manage-skills.cjs push <nom-du-skill>
```
