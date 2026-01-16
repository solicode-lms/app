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

## 📡 Communication Inter-Agents (Le BUS Automatique)

Chaque agent dispose désormais d'un fichier `communication_agent_*.md` dans son dossier. C'est sa **Boîte aux Lettres**.

### Comment ça marche ?

1.  **Au Démarrage** :
    L'agent lit automatiquement son fichier `communication_agent_[NOM].md` pour voir si ses collègues (Data, Business, etc.) lui ont laissé des instructions ou des statuts.

2.  **En Fin de Tâche** :
    Si l'Agent Data termine de créer une table, il va (si le prompt initial est respecté) écrire un petit message dans le `communication_agent_business.md` de l'Agent Business pour le prévenir :
    > *Écriture dans `agents/02_agent_business/communication_agent_business.md` :*
    > `[De Agent Data] : J'ai créé la table 'cours'. Le modèle est prêt.`

3.  **Votre Rôle** :
    Vous n'avez plus besoin de tout répéter. Dites simplement à l'agent : *"Vérifie tes messages et commence le travail."*

### Scénario Typique Mise à Jour

#### Étape 1 : Agent Data
Il crée la table.
*Action* : Il écrit dans `02_agent_business/communication_agent_business.md` -> "Table OK".

#### Étape 2 : Agent Business
Vous lancez l'agent. Il lit son inbox. Il voit "Table OK".
Il code le Service.
*Action* : Il écrit dans `01_agent_presentation/communication_agent_presentation.md` -> "Service OK".

#### Étape 3 : Agent Présentation
Il lit son inbox. Il voit "Service OK".
Il génère la Vue.

---

## 🧠 Apprentissage Continu (Fichiers `rules_agent_*.md`)

Chaque agent possède un fichier `rules_agent_[NOM].md` dans son dossier.
- Si vous corrigez souvent l'agent sur un point précis, il vous proposera d'ajouter une règle dans ce fichier.
- **Acceptez sa proposition** pour qu'il ne refasse plus la même erreur la prochaine fois.
