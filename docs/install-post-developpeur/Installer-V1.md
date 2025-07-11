
# 🧭 Héberger deux versions de Laravel avec Apache sous Windows (ports 80 et 2024)

## 🎯 Objectif

Faire fonctionner deux versions indépendantes de l’application **SoliCode-LMS** sur une même machine :

* `main` → version stable accessible via `http://localhost` (port **80**)
* `main-v1` → version expérimentale accessible via `http://localhost:2024`

---

## 🗂️ Pré-requis

* Apache 2.4 installé (ex. : `C:/Apache24`)
* PHP installé et fonctionnel
* Deux dossiers Laravel distincts :

  * `C:/AppServer/solicode-lms/` (version stable)
  * `C:/AppServer/solicode-lms-v1/` (version de test)

---

## 🔧 Étape 1 – Dupliquer le projet

Copiez le dossier du projet principal :

```bash
C:/AppServer/solicode-lms      → port 80
C:/AppServer/solicode-lms-v1   → port 2024
```

Dans `solicode-lms-v1`, configure le fichier `.env` :

```dotenv
APP_NAME="SoliLMS-V1"
DB_DATABASE=solicode_lms_v1
```

---

## ⚙️ Étape 2 – Configurer Apache (`httpd.conf`)

### 🔹 2.1 – Activer le port 2024

Ajoute en fin de fichier :

```apache
Listen 2024
```

---

### 🔹 2.2 – Ajouter un VirtualHost pour `main-v1`

Toujours dans `httpd.conf` :

```apache
<VirtualHost *:2024>
    ServerName localhost:2024
    DocumentRoot "C:/AppServer/solicode-lms-v1/public"

    <Directory "C:/AppServer/solicode-lms-v1/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "logs/solicode-v1-error.log"
    CustomLog "logs/solicode-v1-access.log" common
</VirtualHost>
```

---

### 🔹 2.3 – Garder la configuration actuelle pour le port 80

Ton `httpd.conf` devrait déjà contenir :

```apache
Listen 80

DocumentRoot "C:/AppServer/solicode-lms/public"
<Directory "C:/AppServer/solicode-lms/public">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

---

## 🚦 Étape 3 – Redémarrer Apache

- J'ai redémarrer le service en utilisant le service Windows

- httpd -k restart n'a pas marché avec moi

Méthode 1 (CLI) :

```bash
httpd -k restart
```



Méthode 2 : via Wamp, XAMPP ou le service Apache sous Windows.

---

## 🧹 Étape 4 – Nettoyer les caches Laravel

Dans **chaque projet**, exécute :

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## ✅ Étape 5 – Tester

* `http://localhost` → version stable
* `http://localhost:2024` → version expérimentale

---

## 🧪 Bonus – Dépannage

* Vérifier si le port 2024 est libre :

```bash
netstat -an | findstr :2024
```

* Consulter les logs Apache en cas d’erreur :

```
C:/Apache24/logs/error.log
```

---

## 🧱 Astuce – Utiliser des `.env` indépendants

Chaque version peut :

* Utiliser sa propre **base de données** (`DB_DATABASE`)
* Spécifier un **répertoire de stockage** (`APP_STORAGE`)
* Avoir des configurations propres (APP\_NAME, debug, etc.)

---

## 🧠 Conclusion

Cette configuration permet :

✅ De tester des évolutions sans perturber les utilisateurs
✅ De comparer visuellement deux versions
✅ De travailler en parallèle sans Docker ni VM

