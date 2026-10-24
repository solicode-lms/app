---
name: [nom-du-skill]
description: [Description rédigée à la 3ème personne indiquant clairement QUAND déclencher et QUAND NE PAS déclencher ce skill]
version: 1.0.0
---

# 1. Mission
[Décrire avec précision l'objectif du skill]

# 2. Déclenchement & Périmètre
- **Cas d'activation :** [Situations où le skill est indispensable]
- **Cas d'exclusion :** [Situations où le skill ne doit pas être utilisé]

# 3. Entrées & Ressources à Charger
- **Priorité 1 :** `.agents/local/...` (Contexte du projet)
- **Priorité 2 :** `references/...` (Standards et conventions)
- **Priorité 3 :** `modules/Pkg.../AI_CONTEXT.md` (Spécificités du module)

# 4. Contraintes & Règles Inviolables
- Respect strict des classes `Base/` (Gapp)
- Interdiction d'exécuter `php artisan migrate` de manière autonome
- Utilisation de `TableUI.js` sans scripts jQuery sauvages

# 5. Procédure Pas-à-Pas (Workflow)
1. **Étape 1 :** [Action 1]
2. **Étape 2 :** [Action 2]
3. **Étape 3 :** [Action 3]

# 6. Gestion des Ambiguïtés & Décisions
[Conduite à tenir en cas de données manquantes : poser max 3 questions ciblées]

# 7. Format de Sortie & Livrables
[Description exacte des livrables et des fichiers modifiés]

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] Règle 1 respectée (OUI / NON)
- [ ] Règle 2 respectée (OUI / NON)

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| ... | ... | ... |

# 10. Exemples Concrets (Few-Shot)
[Exemple canonique issu du code réel]
