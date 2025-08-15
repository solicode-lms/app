# 📘 Débogage PHP avec Xdebug et `debug.ps1`

## 1. Configuration par défaut dans `php.ini`

Dans notre installation, Xdebug est installé mais désactivé par défaut pour ne pas impacter les performances.
Ajouter ces lignes dans `php.ini` :

```ini
[XDEBUG]
zend_extension="C:\php\ext\php_xdebug.dll"
xdebug.mode = off
xdebug.start_with_request = yes
xdebug.client_host = 127.0.0.1
xdebug.client_port = 9003
xdebug.idekey = "vscode"
xdebug.log = "C:\Apache24\logs\xdebug.log"
```

* **xdebug.mode = off** → Xdebug ne fait rien tant que la variable d’environnement `XDEBUG_MODE` n’est pas changée.
* Les autres paramètres (`client_host`, `client_port`, etc.) sont déjà prêts pour VS Code.

---

## 2. Rôle de `XDEBUG_MODE`

`XDEBUG_MODE` est une **variable d’environnement** qui indique à Xdebug quoi activer :

* `off` → Xdebug est inactif, PHP s’exécute normalement.
* `debug` → Xdebug active le pas-à-pas et communique avec l’IDE (VS Code dans notre cas).

Changer `XDEBUG_MODE` nécessite **un redémarrage d’Apache** pour être pris en compte.

---

## 3. Utilité du script `debug.ps1`

`debug.ps1` permet de basculer **automatiquement** `XDEBUG_MODE` entre `debug` et `off` sans devoir éditer `php.ini` à chaque fois.
Il redémarre ensuite Apache pour appliquer le changement.

---

## 4. Contenu du script

```powershell
# Elevation auto si non admin
if (-not ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()
).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) {
    Start-Process powershell "-File `"$PSCommandPath`"" -Verb RunAs
    exit
}

# Lire la valeur actuelle
$current = [Environment]::GetEnvironmentVariable("XDEBUG_MODE", "Machine")

# Basculer la valeur
$new = if ($current -eq "debug") { "off" } else { "debug" }
[Environment]::SetEnvironmentVariable("XDEBUG_MODE", $new, "Machine")

Write-Host "XDEBUG_MODE changé de '$current' à '$new'"

# Redémarrer Apache
Restart-Service Apache2.4 -Force

# Attente avant fermeture
Read-Host "Appuyez sur Entrée pour fermer"
```

---

## 5. Utilisation

1. **Exécuter `debug.ps1`**

   * Double-cliquez dessus ou clic droit → *Exécuter avec PowerShell*.
   * Le script demandera les droits administrateur si besoin.
2. **Lire le message affiché**

   * Exemple : `XDEBUG_MODE changé de 'off' à 'debug'`
3. **Redémarrage automatique**

   * Apache est relancé automatiquement avec la nouvelle configuration.
4. **Lancer VS Code**

   * Utiliser la configuration *Listen for Xdebug*.
   * Recharger la page → les breakpoints sont pris en compte.

