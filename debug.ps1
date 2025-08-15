# Elevation automatique si non admin
if (-not ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()
).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) {
    Start-Process powershell "-File `"$PSCommandPath`"" -Verb RunAs
    exit
}

# Récupère la valeur actuelle
$current = [Environment]::GetEnvironmentVariable("XDEBUG_MODE", "Machine")

# Détermine la nouvelle valeur
$new = if ($current -eq "debug") { "off" } else { "debug" }

# Applique la nouvelle valeur
[Environment]::SetEnvironmentVariable("XDEBUG_MODE", $new, "Machine")

Write-Host "XDEBUG_MODE changé de '$current' à '$new'"

# Redémarre Apache
Restart-Service Apache2.4 -Force

# Attente avant fermeture
Read-Host "Appuyez sur Entrée pour fermer"
