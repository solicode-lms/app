# Ajax Polling

### 🎯 Objectif

Permettre à Laravel d’exécuter un traitement lourd (ex. génération de PDF, calculs) **en arrière-plan** via une route HTTP, tout en maintenant **l’interface réactive** avec AJAX.

---

### 🧩 Composants de la solution

| Élément                                  | Rôle                                                                |
| ---------------------------------------- | ------------------------------------------------------------------- |
| `/traitement-lourd` (POST)               | Lance le traitement long et renvoie un `token`                      |
| `/traitement-lourd/status/{token}` (GET) | Permet de vérifier l’état (`pending` / `done`)                      |
| `Cache`                                  | Sert à stocker l’état du traitement                                 |
| `exec()`                                 | Lance un script PHP en **tâche de fond**                            |
| JavaScript                               | Fait un **polling** toutes les 2 secondes pour surveiller le statut |

---

### 🔄 Fonctionnement simplifié

1. L’utilisateur clique sur un bouton dans l’interface.
2. Une requête AJAX POST appelle `/traitement-lourd`.
3. Laravel crée un **script PHP temporaire** et le lance via `exec()`.
4. Le navigateur démarre un **polling AJAX** (toutes les 2 secondes) vers `/traitement-lourd/status/{token}`.
5. Dès que le statut passe à `done`, le frontend affiche "✅ Terminé".

---

### ✅ Avantages

* **100% compatible Apache**
* **Sans queue, sans Redis, sans Supervisor**
* Parfait pour hébergement mutualisé ou serveur limité
* Ne bloque **pas** la requête principale

