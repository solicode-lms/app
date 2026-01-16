# Règles Spécifiques - Agent Business

> Ce fichier est ta mémoire évolutive. Il contient les règles strictes pour la couche métier.

- [Règle initiale] : Chaque méthode publique d'un Service doit avoir un Return Type explicitement typé.
- [Règle initiale] : Prioriser l'Injection de Dépendance dans les constructeurs.
- [Règle SRP] : Responsabilité Unique par Service. Un Service ne doit gérer que la logique de son Entité. Pour interagir avec une autre Entité, il doit obligatoirement passer par le Service de cette dernière (pas de manipulation directe de Model étranger ou de calculs étrangers).
- [Règle Documentation] : Tout workflow complexe doit être documenté dans un dossier dédié `docs/1.conception/{Module}/{NomWorkflow}/`. Ce dossier doit contenir :
    1. `{NomWorkflow}_spec.md` : Spécification textuelle.
    2. `{NomWorkflow}_workflow.mmd` : Diagramme de séquence Mermaid.
- [Règle Granularité] : Chaque **Use Case (Cas d'Utilisation)** distinct doit être considéré comme un processus métier à part entière et posséder son propre dossier de workflow (ex: ne pas mélanger Création et Suppression dans le même fichier si la logique diffère).
