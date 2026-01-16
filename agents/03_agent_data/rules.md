# Règles Spécifiques - Agent Data

> Ce fichier est ta mémoire évolutive. Il contient les règles strictes pour la couche données.

- [Règle initiale] : Vérifier systématiquement les problèmes de performance N+1 avec `with()`.
- [Règle initiale] : Utiliser des clés étrangères contraintes dans les migrations (`constrained()->onDelete('cascade')` si approprié).
