# Guide d'Utilisation : Skill `planner-dev`

> **Compétence :** `planner-dev`  
> **Portée :** Locale (Incubation Solicode LMS)  
> **Catégorie :** Cadrage, Analyse d'Impact & Planification en Lecture Seule  
> **Fichier technique IA :** [`SKILL.md`](SKILL.md)

---

## 🎯 1. À quoi sert ce Skill ?

Le skill `planner-dev` est l'architecte en lecture seule de votre projet. Avant de modifier la moindre ligne de code sur un besoin complexe, il explore l'architecture, mesure les risques de régression et produit un **plan d'action étape par étape**.

**Sa garantie absolue :** Il n'écrit et ne modifie **aucun fichier de code applicatif**. Il produit un plan rigoureux (document d'implémentation / artefact Markdown) soumis à votre approbation.

---

## 💬 2. Comment l'Invoquer ? (Prompts Clés en Main)

### A. Invocation avec `/plan` (Recommandé)
- *« Analyse l'impact architectural de l'ajout d'une table d'émargement dans PkgApprenants /plan »*
- *« Nous devons refondre le calcul de progression des projets dans PkgRealisationProjet /plan »*
- *« Comment intégrer un système de notification par email sur validation d'un QCM ? /plan »*

### B. Invocation en Langage Naturel
- *« Utilise `planner-dev` pour explorer comment ajouter un filtre dépendant sur les filières et groupes. »*
- *« Quels fichiers et couches seront impactés si nous ajoutons un rôle 'tuteur' dans PkgAutorisation ? »*

---

## 📋 3. Structure du Plan Produit par `planner-dev`

Chaque plan généré respecte la structure standardisée :
1. **Objectif & Cadrage :** Résumé du problème et ce que le changement accomplit.
2. **User Review Required :** Décisions de design ou arbitrages nécessitant votre validation expresse.
3. **Open Questions :** Questions ouvertes ou zones d'ambiguïté à lever avant d'entamer le code.
4. **Proposed Changes :** Découpage logique par couche (Données ➔ Service ➔ Contrôleur ➔ Blade/TableUI).
5. **Verification Plan :** Commandes de tests automatisés et étapes de vérification manuelle.

---

## 🛡️ 4. Garde-Fous Inviolables

- **Lecture Seule Stricte :** Ne modifie aucun fichier du projet.
- **Respect de l'Architecture 3-Tiers :** Intègre systématiquement la séparation claire Présentation / Service / Données.
- **Protection Gapp :** Interdit toute modification des classes `Base/` dans le plan d'action.
