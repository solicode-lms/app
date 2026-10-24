# Étape 3 : Spécification d'Implémentation du Socle Hub Global & Contexte Local

> **Document Préparatoire d'Implémentation (Étape 3)**  
> **Projet :** Hub Global `skills-workspace` & Contexte Local Solicode LMS  
> **Date :** 2026-10-24  
> **Statut :** Soumis à validation avant exécution des commandes et création des fichiers  

---

## 1. Analyse Comparative des Approches pour l'Étape 3

| Dimension | Plan Personnel (`plan-gpt.md` L & V à X) | Plan Unifié (`plan-gem.md` Partie 2 & Phase 1) | Solution Recommandée pour SoliLMS |
| :--- | :--- | :--- | :--- |
| **Structure du Hub** | Dépôt `skills-library` avec arborescence fragmentée (`core/`, `profiles/`, `blueprints/`). | Dépôt `skills-workspace` conforme aux standards Agent Skills (`standards/`, `references/`, `templates/`, `.agents/skills/`). | **Standard Agent Skills :** Structure plus lisible, directement compatible avec tous les agents IA modernes (Claude Code, Antigravity, Cursor, etc.). |
| **Contexte Projet** | Disséminé dans `AGENTS.md` et plusieurs sous-dossiers `docs/`. | Regroupé dans un dossier unique et versionné `.agents/local/`. | **Dossier unique `.agents/local/` :** Évite de disperser les règles du projet et permet une lecture prioritaire immédiate pour l'agent. |
| **Liaison Projet ➔ Hub** | Approche par copie ou synchronisation manuelle. | Jonction de répertoire NTFS (`mklink /J`) sous Windows. | **Jonction NTFS (`mklink /J`) :** Zéro duplication physique, modification bidirectionnelle instantanée sans besoin de droits d'administrateur. |
| **Outillage de Contrôle** | Scripts de build et validation. | Scripts déterministes. | **Scripts Node.js natifs** (`validate-skills.js`, `check-references.js`) adaptés à l'environnement présent sur la machine. |

---

## 2. Plan d'Action Précis de l'Étape 3 (À Exécuter)

Dès votre accord, voici les actions concrètes qui seront réalisées :

### Action 3.1 : Création physique du Hub Global (`D:\solicode-lms\skills-workspace/`)
1. **Création de l'arborescence complète :**
   - `standards/`
   - `references/architecture/`
   - `references/frontend/`
   - `references/database/`
   - `references/gapp/`
   - `templates/`
   - `.agents/skills/`
   - `scripts/`
   - `tests/feedbacks/`
2. **Initialisation des fichiers de référence fondamentaux (extraits et nettoyés d'`old_agent`) :**
   - `catalog.yaml` : Registre initial SemVer 1.0.0.
   - `standards/critical-rules.md` : Les 7 règles d'or (locks Gapp, pas de `artisan migrate` direct, intégrité du code).
   - `standards/pedagogy-referential.md` : Grille des compétences Solicode.
   - `references/architecture/layers-3tiers.md` : Architecture Contrôleurs / Services / Modèles.
   - `references/frontend/tableui-framework.md` : Documentation API TableUI, FilterUI et modales.
   - `references/database/migrations-and-schema.md` : Conventions de colonnes (`reference` unique) et clés étrangères.
   - `references/gapp/01-conventions-et-garde-fous.md` : Règles d'inviolabilité des fichiers `Base/` et locks `ESSARRAJ Fouad`.
   - `references/gapp/04-specifications-avancees.md` : Espace évolutif réservé pour vos ajouts continus de Gapp.
   - `templates/generic-skill.md` : Squelette officiel du `SKILL.md` en 10 rubriques.
3. **Mise en place des scripts Node.js :**
   - `scripts/validate-skills.js` : Vérifie la validité des `SKILL.md` (frontmatter YAML, 10 rubriques obligatoires).
   - `scripts/check-references.js` : Vérifie que tous les liens Markdown relatifs pointent vers des fichiers existants.

### Action 3.2 : Initialisation du Contexte Local (`c:\AppServer\solicode-lms/.agents/local/`)
Création des 3 fiches de contexte propres à Solicode LMS :
1. `project-conventions.md` : Spécificités de SoliLMS, rôles Spatie (`admin`, `formateur`, `apprenant`), gestion du ViewState.
2. `crud-backend-mechanic.md` : Couche Service, gestion des filtres dynamiques, levée des exceptions `BlException`.
3. `crud-frontend-ui.md` : Composants `TableUI`, `FilterUI`, sous-dossier `custom/` pour Blade.

### Action 3.3 : Établissement des Liens Symboliques (Jonctions NTFS)
Exécution des commandes sous Windows PowerShell :
```powershell
cmd /c mklink /J "c:\AppServer\solicode-lms\.agents\skills" "D:\solicode-lms\skills-workspace\.agents\skills"
cmd /c mklink /J "c:\AppServer\solicode-lms\.agents\references" "D:\solicode-lms\skills-workspace\references"
```
*Test de validation immédiat : vérification de la lecture et de l'écriture transparente à travers le lien.*

---

## 3. Critères d'Acceptation de l'Étape 3

L'Étape 3 sera considérée comme achevée lorsque :
- [ ] Le dossier `D:\solicode-lms\skills-workspace` est créé avec son arborescence et ses fichiers de standards.
- [ ] Le dossier local `c:\AppServer\solicode-lms\.agents\local` est créé et documenté.
- [ ] Les jonctions NTFS `.agents/skills` et `.agents/references` sont actives et testées avec succès.
- [ ] Le script Node.js `node scripts/validate-skills.js` s'exécute sans erreur.
