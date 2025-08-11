# 📚 Déboguer Laravel avec **Xdebug** en utilisant des variables d’environnement et `php artisan serve`

## 1️⃣ Introduction

Par défaut, activer **Xdebug** en permanence dans `php.ini` peut ralentir l’exécution de Laravel.
Une meilleure approche consiste à **le charger de manière conditionnelle** :

* **Désactivé** la plupart du temps
* **Activé** uniquement lorsque l’on lance une session de debug

Pour cela, on combine :

* Des **variables d’environnement** définies au moment de lancer `php artisan serve`
* Une configuration dynamique dans `php.ini` qui lit ces variables

---

## 2️⃣ Configuration du `php.ini`

Dans votre `php.ini` (ou `php-dev.ini`), configurez **Xdebug** ainsi :

```ini
[XDEBUG]
zend_extension="C:\php\ext\php_xdebug.dll"

xdebug.mode=${XDEBUG_MODE}
xdebug.start_with_request=${XDEBUG_START_WITH_REQUEST}
xdebug.idekey=${XDEBUG_IDEKEY}
xdebug.discover_client_host=${XDEBUG_DISCOVER_CLIENT_HOST}
```

### 📌 Explication :

* `${XDEBUG_MODE}` → lit la variable d’environnement `XDEBUG_MODE` au lancement de PHP
* `${XDEBUG_START_WITH_REQUEST}` → détermine si le débogage commence automatiquement
* `${XDEBUG_IDEKEY}` → identifiant IDE (ex. `VSCODE`)
* `${XDEBUG_DISCOVER_CLIENT_HOST}` → détecte automatiquement l’IP du client pour la connexion

---

## 3️⃣ Activer Xdebug **à la demande**

### 🔹 Lancer Laravel en debug

Dans PowerShell :

```powershell
$env:XDEBUG_MODE="debug,develop"
$env:XDEBUG_START_WITH_REQUEST="yes"
$env:XDEBUG_IDEKEY="VSCODE"
$env:XDEBUG_DISCOVER_CLIENT_HOST="yes"
php artisan serve
```

### 🔹 Lancer Laravel sans debug

```powershell
$env:XDEBUG_MODE=""
$env:XDEBUG_START_WITH_REQUEST="no"
php artisan serve
```

💡 Ainsi, **Xdebug** n’est activé que lorsque vous définissez les variables.

---

## 4️⃣ Description des principaux paramètres

| Variable / Option                  | Rôle                                                                                                                             |
| ---------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| **XDEBUG\_MODE**                   | Modes séparés par virgule : `off`, `debug`, `develop`, `trace`, `profile`, `gcstats`, `coverage`                                 |
| **XDEBUG\_START\_WITH\_REQUEST**   | `yes` = démarre le debug dès le début du script, `trigger` = démarre avec un cookie/paramètre GET, `no` = jamais automatiquement |
| **XDEBUG\_IDEKEY**                 | Identifie la session dans l’IDE (doit correspondre à la config de VS Code / PHPStorm)                                            |
| **XDEBUG\_DISCOVER\_CLIENT\_HOST** | Si `yes`, Xdebug détecte l’IP du poste client automatiquement                                                                    |
| **php artisan serve**              | Lance le serveur interne PHP en tenant compte des variables définies                                                             |

---

## 5️⃣ Avantages de cette méthode

✅ Pas besoin de commenter/décommenter `php.ini`
✅ Pas de ralentissement Laravel hors debug
✅ Compatible Windows / Linux / Mac
✅ Contrôle total depuis la ligne de commande

---

## 6️⃣ Exemple complet en **mode debug**

```powershell
# 1. Activer les variables d’environnement
$env:XDEBUG_MODE="debug,develop"
$env:XDEBUG_START_WITH_REQUEST="yes"
$env:XDEBUG_IDEKEY="VSCODE"
$env:XDEBUG_DISCOVER_CLIENT_HOST="yes"

# 2. Lancer le serveur Laravel
php artisan serve
```

📌 Ensuite, ouvrez VS Code, placez vos points d’arrêt, et lancez le débogage (F5).

