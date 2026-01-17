# Règles Spécifiques - Agent Business

> Ce fichier est ta mémoire évolutive. Il contient les règles strictes pour la couche métier.

- [Règle initiale] : Chaque méthode publique d'un Service doit avoir un Return Type explicitement typé.
- [Règle initiale] : Prioriser l'Injection de Dépendance dans les constructeurs.
- [Règle SRP] : Responsabilité Unique par Service. Un Service ne doit gérer que la logique de son Entité. Pour interagir avec une autre Entité, il doit obligatoirement passer par le Service de cette dernière (pas de manipulation directe de Model étranger ou de calculs étrangers).
- [Règle Documentation] : Tout workflow complexe doit être documenté dans un dossier dédié `docs/1.conception/{Module}/{NomWorkflow}/`. Ce dossier doit contenir :
    1. `{NomWorkflow}_spec.md` : Spécification textuelle.
    2. `{NomWorkflow}_workflow.mmd` : Diagramme de séquence Mermaid.
- [Règle Granularité] : Chaque **Use Case (Cas d'Utilisation)** distinct doit être considéré comme un processus métier à part entière et posséder son propre dossier de workflow (ex: ne pas mélanger Création et Suppression dans le même fichier si la logique diffère).
- [Règle Adaptation Code] : Lors de l'implémentation d'un diagramme de séquence, ne modifiez pas les signatures des méthodes existantes si elles sont fonctionnelles. Adaptez le workflow aux hooks (`afterCreateRules`, etc.) et paramètres disponibles.
- [Règle Traits] : Lors de la modification d'un Service utilisant des Traits, VOUS DEVEZ LIRE toutes les méthodes de tous les Traits importés. Cela est CRITIQUE pour éviter d'écraser silencieusement des méthodes importantes (ex: `afterCreateRules`) et de briser des fonctionnalités existantes. Si une méthode est surchargée, assurez-vous de restaurer la logique du Trait (ex: via `parent::` ou appel explicite).
- [Règle Sync Diagramme] : Après toute modification de la signature d'une méthode de service (nom, paramètres), **L'AGENT DOIT DEMANDER AU DÉVELOPPEUR** : "Souhaitez-vous synchroniser les diagrammes de séquence impactés par cette modification ?". La mise à jour du diagramme (`.mmd`) doit être effectuée uniquement après confirmation et après que le code ait été modifié. **CRITIQUE : Les paramètres (noms et types) affichés dans le diagramme doivent être STRICTEMENT IDENTIQUES à ceux du code PHP.**
