# # Démarrer Nginx
# cd "C:\nginx\"
# start nginx.exe

# # Lancer PHP en mode FastCGI
# cd "C:\php\"
# php-cgi.exe -b 127.0.0.1:9000

# # Retourner au répertoire du script
# cd $PSScriptRoot

# # Ouvrir l'application dans le navigateur
# Start-Process "http://localhost"

# Démarrer Nginx
cd "C:\nginx\"
start nginx.exe

# Lancer PHP en arrière-plan
cd "C:\php\"
Start-Process "php-cgi.exe" -ArgumentList "-b 127.0.0.1:9000"

# Retourner au dossier du script
cd $PSScriptRoot

# Attendre un peu pour laisser démarrer les services
Start-Sleep -Seconds 2

# Ouvrir l'application Laravel
Start-Process "http://localhost:8080"
