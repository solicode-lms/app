# 🚀 Démarrer Nginx
cd "C:\nginx\"
Start-Process "nginx.exe" 

# 🚀 Lancer PHP-CGI en arrière-plan
cd "C:\php\"
Start-Process "php-cgi.exe" -ArgumentList "-b 127.0.0.1:9000"
# Start-Process "php-cgi.exe" -ArgumentList "-b 127.0.0.1:9000" -WindowStyle Hidden
# ↩️ Retourner au dossier du script
cd $PSScriptRoot

# ⏳ Attendre un peu
Start-Sleep -Seconds 2

# 🌐 Ouvrir Laravel
Start-Process "http://localhost:8080"

# 🛑 Attendre une touche pour quitter
Write-Host "`nAppuyez sur une touche pour arrêter le serveur..." -ForegroundColor Yellow
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

# 🔻 Arrêter Nginx et PHP-CGI
Stop-Process -Name "nginx" -Force -ErrorAction SilentlyContinue
Stop-Process -Name "php-cgi" -Force -ErrorAction SilentlyContinue

Write-Host "✅ Serveur arrêté." -ForegroundColor Green
