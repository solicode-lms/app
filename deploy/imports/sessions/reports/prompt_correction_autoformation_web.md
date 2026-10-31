# 📢 Demande de Correction pour l'Agent du Dépôt `autoformation-web`

---

## 📌 Contexte & Problème Identifié

Lors de la synchronisation entre **autoformation-web** et **Solicode LMS**, plusieurs codes et références de tutoriels présentent une anomalie de formatage : ils comportent **4 parties** avec un point superflu (ex. `T.152.21.1`, `DWB-T.152.21.1`), au lieu du format standardisé et obligatoire à **3 parties**.

### 🛑 Règle Métier Inviolable de Structuration des Codes Tutos :
Le code d'un tutoriel doit strictement contenir **3 parties** séparées par des points :
$$\text{T} \ . \ \{\text{module}\} \ . \ \{\text{ua\_et\_ordre}\}$$

- **Exemple Incorrect :** `T.152.21.1` (4 parties : `T` . `152` . `21` . `1`)
- **Exemple Correct :** `T.152.211` (3 parties : `T` . `152` . `211` où `21` est l'UA et `1` est l'ordre)
- **Référence correspondante :** `DWB-T.152.211` (et non `DWB-T.152.21.1`)

---

## 📋 Table Exhaustive des 32 Tutoriels à Corriger

| N° | Code Erroné (Actuel) | Code Corrigé (3 parties) | Référence Corrigée | Titre du Tutoriel |
| :---: | :--- | :--- | :--- | :--- |
| 1 | `T.152.28.1` | `T.152.281` | `DWB-T.152.281` | Réaliser la revue finale du suivi de projet |
| 2 | `T.152.27.1` | `T.152.271` | `DWB-T.152.271` | Préparer un compte-rendu d'avancement pour son tuteur |
| 3 | `T.152.26.1` | `T.152.261` | `DWB-T.152.261` | Structurer une demande d'aide face à un blocage |
| 4 | `T.152.25.1` | `T.152.251` | `DWB-T.152.251` | Mesurer l'écart entre temps estimé et temps réel |
| 5 | `T.152.24.1` | `T.152.241` | `DWB-T.152.241` | Mettre en place et actualiser un tableau Kanban simple |
| 6 | `T.152.23.1` | `T.152.231` | `DWB-T.152.231` | Découper un sprint en sous-tâches indépendantes |
| 7 | `T.152.22.1` | `T.152.221` | `DWB-T.152.221` | Estimer le temps nécessaire pour une tâche d'intégration |
| 8 | `T.152.21.1` | `T.152.211` | `DWB-T.152.211` | Analyser une consigne et identifier les critères d'acceptation |
| 9 | `T.152.21.2` | `T.152.212` | `DWB-T.152.212` | Rédiger une fiche de tâche synthétique |
| 10 | `T.151.18.1` | `T.151.181` | `DWB-T.151.181` | Maintenir un dépôt propre et récupérable |
| 11 | `T.151.17.1` | `T.151.171` | `DWB-T.151.171` | Cloner et remettre en service un projet avec git clone |
| 12 | `T.151.16.1` | `T.151.161` | `DWB-T.151.161` | Partager son travail sur Internet avec git push |
| 13 | `T.151.16.2` | `T.151.162` | `DWB-T.151.162` | Mettre à jour son ordinateur avec git pull |
| 14 | `T.151.15.1` | `T.151.151` | `DWB-T.151.151` | Créer et configurer un fichier .gitignore |
| 15 | `T.151.15.2` | `T.151.152` | `DWB-T.151.152` | Réparer une erreur avant le commit avec git restore |
| 16 | `T.151.14.1` | `T.151.141` | `DWB-T.151.141` | Lire l'historique du projet avec git log |
| 17 | `T.151.13.1` | `T.151.131` | `DWB-T.151.131` | Rédiger des messages de commit professionnels et sécurisés |
| 18 | `T.151.12.1` | `T.151.121` | `DWB-T.151.121` | Les 3 zones de Git expliquées simplement |
| 19 | `T.151.12.2` | `T.151.122` | `DWB-T.151.122` | La routine locale du développeur (Terminal) |
| 20 | `T.151.11.1` | `T.151.111` | `DWB-T.151.111` | Installer et configurer Git sur son ordinateur |
| 21 | `T.151.11.2` | `T.151.112` | `DWB-T.151.112` | Comprendre les concepts de base : Git et GitHub |
| 22 | `T.151.11.3` | `T.151.113` | `DWB-T.151.113` | Sauvegarder son code en un clic avec VS Code |
| 23 | `T.141.13.1` | `T.141.131` | `DWB-T.141.131` | Démarrer un environnement complet avec MySQL |
| 24 | `T.141.12.1` | `T.141.121` | `DWB-T.141.121` | Démarrer un environnement PHP (Apache) |
| 25 | `T.141.11.1` | `T.141.111` | `DWB-T.141.111` | Démarrer un serveur statique local |
| 26 | `T.125.24.1` | `T.125.241` | `DWB-T.125.241` | Déboguer un parcours complet de soumission de formulaire |
| 27 | `T.125.23.1` | `T.125.231` | `DWB-T.125.231` | Corriger une variable mal transmise dans l'URL |
| 28 | `T.125.22.1` | `T.125.221` | `DWB-T.125.221` | Installer et tester Xdebug avec VS Code |
| 29 | `T.125.22.2` | `T.125.222` | `DWB-T.125.222` | Traquer une anomalie dans un script PHP |
| 30 | `T.125.21.1` | `T.125.211` | `DWB-T.125.211` | Déboguer un script JavaScript avec des breakpoints |
| 31 | `T.122.22.6` | `T.122.226` | `DWB-T.122.226` | Projet de synthèse — Modèle de boîte |
| 32 | `T.122.21.10` | `T.122.2110` | `DWB-T.122.2110` | Projet de Synthèse CSS |

---

## 🎯 Actions Requises de l'Agent `autoformation-web`

1. **Fichiers Markdown des tutoriels (`tutos/*.md`) :**
   Mettre à jour dans les frontmatters YAML les champs `code:` et `reference:` pour appliquer les codes normalisés à 3 parties ci-dessus.
2. **Script ou générateur d'export CSV :**
   Vérifier l'algorithme qui compose les codes de tutoriels afin de s'assurer qu'il concatène toujours le numéro d'ordre sans insérer de point supplémentaire (format `T.{mid}.{ua_index}`).
3. **Régénération des CSV :**
   Régénérer le fichier `chapitres.csv` pour la filière Web.
