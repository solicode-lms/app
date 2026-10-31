# Modèle de Prompt de Rétroaction Multi-Agents

Ce modèle est utilisé par l'agent **session-importer** pour formuler un incident précis et actionnable à destination de l'agent **autoformation-web** ou **autoformation-mobile** lorsque l'exécution d'un seeder ou l'analyse des CSV rencontre une anomalie.

---

```markdown
### 📢 Demande de Correction CSV pour l'Agent {{TARGET_AGENT}} (Autoformation)

**Contexte :** 
Lors de la tentative d'importation des sessions dans Solicode LMS pour la filière **{{FILIERE_LABEL}}** (code `{{FILIERE_CODE}}`), une erreur bloquante est survenue lors de l'exécution du seeder `{{SEEDER_CLASS}}`.

**1. Erreur Constatée :**
```text
{{ERROR_MESSAGE}}
```

**2. Fichier CSV Impacté :**
- Fichier : `{{CSV_FILE_NAME}}`
- Ligne(s) en cause : Ligne {{CSV_LINE_NUMBER}}
- Référence de la ligne : `{{ROW_REFERENCE}}`

**3. Diagnostic & Cause Racine :**
{{DIAGNOSTIC_EXPLANATION}}
*(Exemple : La colonne `unite_apprentissage_reference` référence `DWB-UA.122.99` qui n'existe ni dans `uniteApprentissages.csv` ni en base de données).*

**4. Action Requise dans le Dépôt Autoformation :**
1. Corriger les métadonnées de l'élément incriminé dans `_data/` ou dans le fichier Markdown source.
2. S'assurer de la cohérence référentielle entre chapitres (tutoriels), UAs et compétences.
3. Regénérer les exports CSV via le script d'exportation de l'autoformation.
4. Mettre à disposition les nouveaux fichiers CSV dans `deploy/imports/sessions/{{SUBDIR}}/`.
```
