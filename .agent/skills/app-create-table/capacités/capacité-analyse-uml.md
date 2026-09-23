# Capacité : Analyse UML

## 🎯 Rôle
Fournir les standards et la méthode pour analyser un diagramme de classes UML (Mermaid) et déterminer l'ordre de création des tables et de leurs relations.

## 📝 Règles d'Analyse
1. **Ordre de Création Chronologique** : Établir un ordre chronologique où chaque table est créée avec ses relations (clés étrangères), à condition que les tables cibles existent déjà. Les tables pivots (ManyToMany) sont créées en dernier.
2. **Conventions de Nommage (Laravel)** : Les noms de tables doivent impérativement respecter la convention Laravel (en `snake_case` et au pluriel, ex: `QuestionLib` devient `question_libs`).
3. **Cas Spécifiques** : 
   - Si un champ se nomme `sys_color_id`, il doit systématiquement être interprété comme une relation (clé étrangère) vers la table externe `sys_colors` (Core).

## 🛠️ Protocole d'Extraction
1. Lire le contenu du fichier Mermaid passé en paramètre.
2. Extraire les `namespace` pour grouper les classes par package.
3. Pour chaque `class`, lister les attributs avec leurs types.
4. Pour chaque lien (ex: `A "1" --> "*" B`), déduire s'il s'agit d'une relation `ManyToOne` (clé étrangère) ou `ManyToMany` (table pivot).
