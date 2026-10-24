---
name: project-setup
description: Initialise l'agent sur n'importe quel type de projet (Dev logiciel, Redaction de tutoriels/autoformations, Ateliers APC), analyse les cahiers des charges, audite et recycle les dossiers legacy (old_agent) et deploie les competences requises par liens symboliques.
version: 2.1.0
inspiration:
  source: "anthropic/claude-plugins-official"
  plugin: "claude-code-setup"
  pattern: "universal onboarding, legacy recycling, capability tailoring & rules proposal"
---

# 1. Mission
Ce skill est l'**orchestrateur universel d'onboarding, d'audit architectural et de configuration d'agent IA**. Il permet :
1. D'amorcer et configurer l'environnement IA sur **n'importe quel type de projet** (application de développement logiciel 3-tiers, projet éditorial de rédaction de cours et d'autoformations web, ou référentiel d'ingénierie pédagogique APC).
2. D'analyser le cahier des charges pour identifier les livrables cibles et qualifier le profil adéquat (avec dialogue interactif si le projet est vierge ou sans documentation claire).
3. D'auditer, trier et recycler les anciens dossiers d'agents historiques (`old_agent`, `.agent/`) pour en extraire la mémoire technique et archiver le superflu.
4. De configurer l'installation sélective granulaire des compétences du Hub Global via des jonctions NTFS unitaires pilotées par `.agents/skills.json`.
5. D'auditer les besoins spécifiques et le legacy pour **proposer la liste des Skills à créer** (locaux ou candidats au Hub) et des **Rules comportementales à créer** (`.agents/rules/`).

---

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Amorce d'un projet vierge ou onboarding initial (*« Initialise l'agent pour ce projet »*, *« Configure l'environnement IA »*).
  - Cadrage d'un projet d'autoformation ou de cours (*« Configure l'agent pour un projet de tutoriels d'autoformation web »*).
  - Modernisation d'un agent existant (*« Audite et migre notre ancien dossier old_agent »*).
  - Analyse d'un cahier des charges fonctionnel ou pédagogique (*« Analyse le cahier des charges et dis-moi quelles compétences installer »*).
  - Qualification et proposition de nouveaux skills ou de règles locales pour le projet.
  - Vérification de santé et réalignement de l'environnement IA (`manage-skills.cjs list`).
- **Quand NE PAS l'activer :**
  - Rédaction ou simplification de tutoriels ➔ Utiliser [tutorial-writer](../tutorial-writer/SKILL.md).
  - Conception, modification ou gouvernance des compétences et règles ➔ Utiliser [plugin-dev](../plugin-dev/SKILL.md).
  - Développement de composants, styles CSS ou layouts Jekyll ➔ Utiliser [dev-jekyll](../dev-jekyll/SKILL.md).
  - Synchronisation du socle technique partagé (Core Theme) ➔ Utiliser [sync-theme](../sync-theme/SKILL.md).

---

# 3. Entrées & Ressources à Charger
- **Ressources méthodologiques internes (à charger selon le besoin) :**
  - [project-typology-and-profiles.md](resources/project-typology-and-profiles.md) : Matrice des 4 profils (Dev Logiciel, Tutoriels/Autoformations, Ateliers APC, Hybride) et critères de détection.
  - [legacy-migration-playbook.md](resources/legacy-migration-playbook.md) : Protocole de tri des connaissances passives vs actions réelles et archivage d'`old_agent`.
  - [onboarding-workflow.md](resources/onboarding-workflow.md) : Procédure pas-à-pas de bootstrapping et d'initialisation.
- **Ressources du projet :**
  - Cahiers des charges (`cahiers-charges/`, `README.md`, spécifications fonctionnelles).
  - Manifeste `.agents/skills.json`, règles `.agents/rules/` et dossier de conventions `.agents/references/conventions/`.
  - Catalogue global du Hub : `D:/solicode-lms/skills-workspace/catalog.yaml`.

---

# 4. Contraintes & Règles Inviolables
- **Non-Destructif sur le Code :** `project-setup` ne modifie jamais le code applicatif du projet ni la structure des bases de données.
- **Bootstrapping Propre :** Pour un projet vierge sans dossier `.agents/`, utiliser la commande d'amorce `node <hubPath>/skills/project-setup/scripts/manage-skills.cjs bootstrap`.
- **Installation Sélective Granulaire (Skill-by-Skill) :** Le dossier `.agents/skills/` est physique. Tout élément dans `.agents/` est local par défaut. Seules les compétences partagées sont montées via des jonctions NTFS unitaires pointant vers le Hub.
- **Proposition Structurée Obligatoire :** Tout onboarding ou audit legacy doit obligatoirement expliciter la liste des **Skills à créer** et des **Rules à formaliser**.
- **Archivage Défensif Obligatoire :** Tout ancien dossier `old_agent` audité doit être déplacé dans `archive/old_agent` pour éviter toute collision cognitive d'agent.

---

# 5. Procédure Pas-à-Pas
1. **Étape 1 : Diagnostic & Bootstrapping :**
   - Vérifier si `.agents/skills/` et `.agents/skills.json` existent. Si le projet est vierge, exécuter l'amorce `node D:\solicode-lms\skills-workspace\skills\project-setup\scripts\manage-skills.cjs bootstrap`.
2. **Étape 2 : Analyse du Cahier des Charges & Profilage :**
   - Examiner les fichiers d'entrée et consulter [project-typology-and-profiles.md](resources/project-typology-and-profiles.md) pour identifier le profil (Profil 1: Dev Logiciel, Profil 2: Tutoriels/Autoformation, Profil 3: Ingénierie APC, Profil 4: Hybride).
   - En cas d'ambiguïté ou de projet sans doc : engager le dialogue interactif avec l'utilisateur.
3. **Étape 3 : Audit & Recyclage Legacy (Si `old_agent` présent) :**
   - Appliquer le [legacy-migration-playbook.md](resources/legacy-migration-playbook.md) pour trier le contenu en 4 destinations :
     - *Actions outillées locales* ➔ Proposer la création de **Skills locaux**.
     - *Invariants comportementaux* ➔ Proposer la création de **Rules** dans `.agents/rules/`.
     - *Connaissances documentaires* ➔ Transférer vers `.agents/references/conventions/` (ou sous-dossiers thématiques).
     - *Outils obsolètes* ➔ Déplacer l'ancien dossier dans `archive/old_agent/` puis assainir.
4. **Étape 4 : Déploiement Granulaire des Compétences :**
   - Rédiger `.agents/skills.json` avec la liste exacte des compétences globales du Hub et locales requises.
   - Synchroniser via `node .agents/skills/project-setup/scripts/manage-skills.cjs install`.
5. **Étape 5 : Formalisation des Règles, Conventions & Bilan :**
   - Créer les règles retenues dans `.agents/rules/`.
   - Rédiger les conventions propres au projet dans `.agents/references/conventions/project-conventions.md`.
   - Émettre le rapport d'onboarding structuré.

---

# 6. Gestion des Ambiguïtés & Décisions
- **Projet vierge sans documentation :** Ne pas deviner arbitrairement. Poser les 4 options à l'utilisateur (Dev, Tutoriels, APC, Hybride) et attendre sa confirmation.
- **Conflit entre règles legacy et standards du Hub :** Les règles d'architecture et de sécurité du Hub prévalent toujours sur les anciennes instructions d'`old_agent`.
- **Absence de Node.js lors du bootstrap :** Fournir le script PowerShell alternatif documenté dans [onboarding-workflow.md](resources/onboarding-workflow.md).

---

# 7. Format de Sortie & Livrables
L'agent fournit systématiquement un rapport d'onboarding structuré comprenant :
- **Typologie & Profil Retenu :** (ex: *Profil 2 — Rédaction de Tutoriels d'Autoformation*).
- **Compétences Globales Installées :** Tableau des jonctions NTFS unitaires actives vers le Hub.
- **Skills Proposés / Créés :** Liste des compétences locales créées dans `.agents/skills/` pour outiller le projet.
- **Rules Proposées / Créées :** Liste des règles comportementales formalisées dans `.agents/rules/`.
- **Compétences Exclues :** Liste des skills non pertinents filtrés.
- **Recyclage Legacy :** Synthèse du tri effectué et statut de l'assainissement d'`archive/old_agent/`.
- **Conventions du Projet :** Emplacement et contenu des fiches sous `.agents/references/conventions/`.
- **Prochaine Action Recommandée :** Prompt d'action prêt à l'emploi.

---

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] Le projet est-il correctement qualifié selon l'un des 4 profils typologiques ?
- [ ] En l'absence de cahier des charges, une qualification interactive a-t-elle été engagée ?
- [ ] Le dossier physique `.agents/skills/` ne contient-il QUE les jonctions et compétences retenues ?
- [ ] Les compétences hors profil (ex: scaffolding sur projet de tutoriels) sont-elles bien exclues ?
- [ ] Une proposition claire des Skills et Rules à créer a-t-elle été formalisée ?
- [ ] L'éventuel dossier `old_agent` a-t-il été recyclé et assaini ?
- [ ] Le script `node .agents/skills/project-setup/scripts/manage-skills.cjs list` confirme-t-il l'état attendu ?

---

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Échec de création de la jonction NTFS | Syntaxe incorrecte ou tentative de symlink standard | Utiliser `cmd /c mklink /J <dest> <src>` (ne requiert pas de droits administrateur) |
| Pollution de compétences non pertinentes | Jonction globale posée sur tout le dossier `skills/` | Supprimer la jonction globale et installer unitairement via `manage-skills.cjs install` |
| Conflit d'attention de l'agent | Présence simultanée d'`old_agent` et du nouveau `.agents/` | Déplacer immédiatement l'ancien dossier dans `archive/old_agent/` |

---

# 10. Exemples Concrets (Few-Shot)

### Exemple 1 : Projet Vierge de Rédaction de Tutoriels d'Autoformation Web
- **Prompt :** *« Initialise l'agent pour ce projet, nous allons créer des tutoriels d'autoformation web. »*
- **Comportement de l'agent :** Exécute le bootstrap, identifie le **Profil 2 (Tutoriels & Autoformation)**, configure `.agents/skills.json` avec `["project-setup", "planner-dev", "tutorial-writer", "plugin-dev"]`, exclut `bug-fixer` et `domain-scaffolder`, installe les jonctions unitaires et consigne les conventions dans `.agents/references/conventions/project-conventions.md`.

### Exemple 2 : Modernisation d'un Projet avec Dossier `old_agent`
- **Prompt :** *« Configure l'agent sur notre application Solicode LMS et nettoie l'ancien dossier old_agent. »*
- **Comportement de l'agent :** Scanne `old_agent`, classe les documentations de référence dans `.agents/references/` (`conventions/`, `frontend/`, `gapp/`), configure le **Profil 1 (Dev Logiciel 3-Tiers)**, déplace `old_agent` dans `archive/old_agent/`, installe les 7 skills applicatifs et confirme la propreté du dépôt.

### Exemple 3 : Projet Sans Cahier des Charges (Dialogue Interactif)
- **Prompt :** *« Initialise l'agent pour ce projet. »*
- **Comportement de l'agent :** Constate l'absence de fichiers ou de cahier des charges, interroge immédiatement l'utilisateur sur la finalité (Dev, Tutoriels, Ateliers APC) et attend le choix avant de déployer la configuration.
