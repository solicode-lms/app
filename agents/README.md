# 🤖 Système Multi-Agents Solicode LMS

Ce dossier contient l'architecture des agents IA spécialisés pour le projet Solicode LMS. Chaque agent est optimisé pour une couche spécifique de l'architecture logicielle (Presentations, Business, Data).

## 📂 Structure des Agents

1.  **00_context_global/** : Le "Cerveau Commun". Contient la documentation projet, la structure BDD et les règles globales. Tous les agents lisent ce dossier.
2.  **01_agent_presentation/** : Expert Frontend, UI/UX et Controllers.
3.  **02_agent_business/** : Expert Logique Métier et Services.
4.  **03_agent_data/** : Expert Base de Données et Optimisation SQL.

---

## 🚀 Comment Démarrer un Agent

Pour travailler avec un agent spécifique, ouvre un **nouvel onglet de chat** avec l'IA et copie-colle le contenu de son fichier d'initialisation :

| Agent            | Rôle                                           | Fichier d'Init à Copier                                   |
| :--------------- | :--------------------------------------------- | :-------------------------------------------------------- |
| **Présentation** | Création de Vues (Blade), JS, CSS, Controllers | `agents/01_agent_presentation/init_agent_presentation.md` |
| **Business**     | Création de Services, Traits, Logique complexe | `agents/02_agent_business/init_agent_business.md`         |
| **Data**         | Migrations, Requêtes SQL, Relations Eloquent   | `agents/03_agent_data/init_agent_data.md`                 |

Chaque agent chargera automatiquement son contexte et activera son mode "Apprentissage Continu" (mise à jour automatique de ses propres règles).

---

## 📡 Communication Inter-Agents (Le Protocole)

Les agents sont isolés dans leurs fenêtres de discussion respectives, mais ils travaillent sur le même code source. Pour les faire collaborer efficacement, vous (le développeur) agissez comme le **bus de communication**.

### Scénario Typique : Création d'une nouvelle fonctionnalité "Gestion des Cours"

#### Étape 1 : Architecture des Données (Agent Data)
1.  Ouvrez l'onglet **Agent Data**.
2.  Demandez : *"Prépare la migration et le modèle pour la table `cours`."*
3.  L'agent crée le fichier de migration et le modèle Eloquent.

#### Étape 2 : Logique Métier (Agent Business)
1.  Ouvrez l'onglet **Agent Business**.
2.  Dites-lui : *"L'Agent Data a créé le modèle `Cours`. Crée maintenant le `CoursService` avec les méthodes `create` et `publish`."*
3.  L'agent lit les nouveaux fichiers créés par l'Agent Data et implémente la logique.

#### Étape 3 : Interface Utilisateur (Agent Présentation)
1.  Ouvrez l'onglet **Agent Présentation**.
2.  Dites-lui : *"Utilise le `CoursService` créé par l'Agent Business pour afficher la liste des cours dans un contrôleur et une vue Blade."*
3.  L'agent connecte le tout et génère l'interface finale.

### Astuce "Presse-Papier"
Si un agent a besoin d'informations complexes produites par un autre (ex: une documentation API générée par Business pour Presentation), demandez à l'Agent Business de **générer un fichier markdown temporaire** (ex: `docs/specs_temp.md`) que l'Agent Présentation pourra lire.

---

## 🧠 Apprentissage Continu (Fichiers `rules.md`)

Chaque agent possède un fichier `rules.md` dans son dossier.
- Si vous corrigez souvent l'agent sur un point précis, il vous proposera d'ajouter une règle dans ce fichier.
- **Acceptez sa proposition** pour qu'il ne refasse plus la même erreur la prochaine fois.
