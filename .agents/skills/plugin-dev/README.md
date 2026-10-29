# Guide d'Utilisation : Skill `plugin-dev`

> **Compétence :** `plugin-dev`  
> **Portée :** Globale (Partagée par le Hub `skills-workspace`)  
> **Catégorie :** Gouvernance des Skills, Conception, Promotion & Standards IA  
> **Fichier technique IA :** [`SKILL.md`](SKILL.md)

---

## 🎯 1. À quoi sert ce Skill ?

Le skill `plugin-dev` est l'**architecte et le gouverneur de vos compétences IA**. Il intervient pour :
1. **Créer une nouvelle compétence :** Rédiger un fichier `SKILL.md` rigoureux respectant le standard normé en 10 rubriques et les patterns Anthropic officiels.
2. **Promouvoir une compétence :** Convertir un skill local testé sur un projet vers le Hub Global via la commande de promotion en cascade (`manage-skills.cjs promote <skill>`).
3. **Affiner les règles d'or :** Mettre à jour les consignes comportementales (`rules/critical-rules.md`, `rules/affichage-skills.md`).
4. **Valider la conformité :** Contrôler la syntaxe avec `validate-skills.cjs` pour garantir 0 erreur bloquante.

---

## 💬 2. Comment l'Invoquer ? (Prompts Clés en Main)

- *« Crée un nouveau skill local pour l'automatisation des seeders de test. »*
- *« Utilise `plugin-dev` pour promouvoir le skill feature-dev vers le Hub Global avec toutes ses dépendances. »*
- *« Ajoute une règle d'or interdisant les requêtes SQL brutes dans les contrôleurs dans rules/critical-rules.md. »*
- *« Vérifie la conformité de tous les skills du projet avec le script de validation. »*

---

## 📋 3. Le Gabarit Universel en 10 Rubriques

Tout skill conçu ou audité par `plugin-dev` respecte obligatoirement ces 10 sections :
1. Mission
2. Déclenchement & Limites
3. Entrées & Ressources à Charger (avec colocalisation des ressources non-partagées)
4. Contraintes & Règles Inviolables
5. Procédure Pas-à-Pas
6. Gestion des Ambiguïtés & Décisions
7. Format de Sortie & Livrables
8. Contrôle Qualité (Checklist Binaire)
9. Gestion des Erreurs Fréquentes
10. Exemples Concrets (Few-Shot)
