# Installation de post développeur 

## Labs 

- lab-deploiement


### Tutoriel : Installation d'Apache pour un Développement Laravel

Dans ce tutoriel, nous allons installer **Apache** sur **Windows** et le configurer pour exécuter une application Laravel en développement.


## Installation de PHP 

- php-debug-pack-8.4.4-Win32-vs17-x64

---


## PHP et Appache doit être compatible 

📌 Explication du problème
Apache 2.4.63 a été compilé avec Visual Studio 2017 (VS17).
PHP 8.3.7 a été compilé avec Visual Studio 2019 (VC19).
De plus, tu utilises la version NTS (Non-Thread Safe) de PHP, alors qu'Apache requiert la version Thread Safe (TS).
👉 Solution : Installer la bonne version de PHP (Thread Safe et compilée en VS17).


✅ **Thread Safe (TS)** : Utilisé avec **Apache (mod_php)**, inclut un verrouillage des threads pour gérer plusieurs requêtes en parallèle. **Obligatoire pour Apache**.  

❌ **Non-Thread Safe (NTS)** : Utilisé avec **PHP-FPM, FastCGI (IIS, Nginx)**, sans verrouillage des threads, offrant de meilleures performances. **Incompatible avec Apache mod_php**.  

📌 **Pour Apache 2.4.63, utilise PHP 8.3.7 TS (Thread Safe) VS17.**


## Configuration de PHP pour Laravel

````
[PHP]
zend_extension = xdebug
xdebug.mode = debug
xdebug.start_with_request = yes

extension=openssl
extension=pdo_mysql
extension=fileinfo
extension=curl
extension=gd
extension=mbstring
extension=zip
````



## Installe xdebug 


### **✅ Étapes pour Installer le Débogage avec PHP (Xdebug)**
Xdebug est l’extension la plus utilisée pour déboguer PHP. Voici comment l’installer et le configurer.

---

### **1️⃣ Vérifier si Xdebug est installé**
Dans ton terminal **PowerShell** ou **cmd**, exécute :
```sh
php -v
```
Si Xdebug est installé, tu verras une ligne comme :
```
with Xdebug v3.x.x, Copyright (c) 2002-2024, by Derick Rethans
```
**Si Xdebug n'est pas installé, passe à l'étape 2.**

---

### **2️⃣ Télécharger et Installer Xdebug**
#### **🔹 Étape 1 : Vérifier la version exacte de PHP**
Exécute :
```sh
php -i | findstr "Compiler Architecture"
```
Tu devrais voir quelque chose comme :
```
Compiler => MSVC17 (Visual C++ 2022)
Architecture => x64
```
Note **MSVC version** et **Architecture** (`x64` ou `x86`).

#### **🔹 Étape 2 : Télécharger Xdebug compatible**
1. Va sur **[Xdebug Wizard](https://xdebug.org/wizard)**
2. Copie la sortie de `php -i` et colle-la dans l’outil.
3. Télécharge la DLL recommandée (ex: `php_xdebug-3.3.2-8.3-vs17-x64.dll`).
4. Place ce fichier dans `C:\php\ext\`.

---

### **3️⃣ Configurer `php.ini` pour Activer Xdebug**
1. Ouvre `php.ini` :
   ```sh
   notepad C:\php\php.ini
   ```
2. Ajoute ou modifie ces lignes en bas du fichier :
   ```ini
   [Xdebug]
   zend_extension="C:\php\ext\php_xdebug-3.3.2-8.3-vs17-x64.dll"
   xdebug.mode=debug
   xdebug.start_with_request=yes
   xdebug.client_host=127.0.0.1
   xdebug.client_port=9003
   ```
3. **Enregistre et ferme le fichier**.

---

### **4️⃣ Redémarrer Apache ou PHP**
Si tu utilises **Apache**, exécute :
```sh
net stop Apache2.4
net start Apache2.4
```
Si tu utilises **PHP intégré** :
```sh
php -m | findstr "xdebug"
```
Si **Xdebug apparaît**, l’installation est réussie !

---

### **5️⃣ Tester Xdebug**
1. Crée un fichier `test.php` dans ton projet :
   ```php
   <?php
   phpinfo();
   ?>
   ```
2. Ouvre **http://localhost/test.php** et cherche **Xdebug**.

Si Xdebug est listé, le débogueur est **installé avec succès !** 🎯

---

### **6️⃣ Configurer Xdebug avec VS Code (Facultatif)**
Si tu veux **déboguer PHP avec VS Code** :
1. Installe l’extension **PHP Debug** sur VS Code.
2. Dans VS Code, ouvre ton projet et ajoute un fichier `.vscode/launch.json` :
   ```json
   {
       "version": "0.2.0",
       "configurations": [
           {
               "name": "Listen for Xdebug",
               "type": "php",
               "request": "launch",
               "port": 9003
           }
       ]
   }
   ```
3. Démarre **VS Code en mode débogage**.

✅ **Xdebug est maintenant prêt à être utilisé pour tracer et déboguer ton application PHP !** 🚀




## Désinstaller appache 

Exécuter Powershell comme administrateur

````bash
net stop Apache2.4
.\httpd.exe -k uninstall
````

## **1. Installation d’Apache**
### **Étape 1 : Téléchargement et installation d’Apache**
1. Téléchargez **Apache 2.4** depuis [Apache Lounge](https://www.apachelounge.com/download/).
2. Extrayez le fichier ZIP dans **C:\Apache24**.
3. Ouvrez **Invite de commandes (cmd)** en mode administrateur et exécutez :

   ```sh
   cd C:\Apache24\bin
   ./httpd.exe -k install
   ```

4. Démarrez Apache :

   ```sh
   httpd.exe -k start
   ```

5. Vérifiez qu'Apache fonctionne en accédant à **http://localhost/** dans votre navigateur.

---

## **2. Installation de PHP**
### **Étape 1 : Téléchargement et installation de PHP**
1. Téléchargez PHP depuis [Windows PHP Downloads](https://windows.php.net/download).
2. Extrayez le contenu de l’archive ZIP dans **C:\php**.
3. Renommez le fichier **php.ini-development** en **php.ini**.

### **Étape 2 : Configuration d’Apache pour PHP**
1. Ouvrez le fichier **C:\Apache24\conf\httpd.conf** avec un éditeur de texte.
2. Ajoutez ces lignes à la fin du fichier :

   ```apache
   PHPIniDir "C:/php"
   LoadModule php_module "C:/php/php8apache2_4.dll"
   AddType application/x-httpd-php .php
   ```

3. Redémarrez Apache :

   ```sh
   httpd.exe -k restart
   ```

4. Vérifiez que PHP fonctionne en créant un fichier **C:\Apache24\htdocs\info.php** contenant :

   ```php
   <?php phpinfo(); ?>
   ```

5. Accédez à **http://localhost/info.php** pour voir la page d'information PHP.

---

## **3. Configuration d’Apache pour Laravel**
### **Étape 1 : Modification du DocumentRoot**
1. Ouvrez **httpd.conf** et trouvez la ligne :

   ```apache
   DocumentRoot "C:/Apache24/htdocs"
   <Directory "C:/Apache24/htdocs">
   ```

2. Remplacez-la par votre projet Laravel :

   ```apache
   DocumentRoot "C:/AppServer/solicode-lms/public"
   <Directory "C:/AppServer/solicode-lms/public">
       Options Indexes FollowSymLinks
       AllowOverride All
       Require all granted
   </Directory>
   ```

### **Étape 2 : Activation du module mod_rewrite**
1. Recherchez cette ligne dans **httpd.conf** et décommentez-la (supprimez le `#`) :

   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```

2. Redémarrez Apache :

   ```sh
   httpd.exe -k restart
   ```

---

## **4. Installation et Exécution de Laravel**
### **Étape 1 : Installation de Composer**
1. Téléchargez **Composer** depuis [getcomposer.org](https://getcomposer.org/download/).
2. Installez-le et assurez-vous qu'il est ajouté à la variable d’environnement `PATH`.

### **Étape 2 : Installation de Laravel**
1. Dans **C:\AppServer**, exécutez :

   ```sh
   composer create-project --prefer-dist laravel/laravel solicode-lms
   ```

2. Accédez au dossier du projet :

   ```sh
   cd C:\AppServer\solicode-lms
   ```

3. Assurez-vous que **Apache a accès aux fichiers Laravel** en définissant les permissions adéquates :

   ```sh
   icacls C:\AppServer\solicode-lms /grant Everyone:(OI)(CI)F /T
   ```

---

## **5. Vérification du Débogage de Laravel**
1. Vérifiez que **mod_rewrite** fonctionne en accédant à **http://localhost/**.
2. Activez le mode débogage en modifiant le fichier **.env** :

   ```ini
   APP_ENV=local
   APP_DEBUG=true
   ```

3. Testez Laravel en exécutant :

   ```sh
   php artisan serve
   ```

Si tout fonctionne bien, votre application Laravel devrait être accessible via **http://localhost**.

---

## **6. Débogage en cas de problème**
### **1. Vérifier la configuration d’Apache**
- Exécutez :

  ```sh
  httpd.exe -t
  ```

  Si des erreurs apparaissent, corrigez-les dans **httpd.conf**.

### **2. Vérifier si Apache écoute sur le port 80**
- Exécutez :

  ```sh
  netstat -ano | findstr :80
  ```

  Si un autre service utilise le port, changez-le dans **httpd.conf** :

  ```apache
  Listen 8080
  ServerName localhost:8080
  ```

  Redémarrez Apache et accédez à **http://localhost:8080**.

---

Avec ces étapes, vous aurez un environnement **Apache + PHP + Laravel** fonctionnel sur Windows. 🚀