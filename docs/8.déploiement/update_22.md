# Procédure de première installation 

## Configuration et installation de la version 2025 

Installation de la version de l'année précédente dans le serveur avec le port : 2025

## Déploiement de la nouvelle version 

1. Création de fichier d'environnement


````bash
npm install
composer install
php artisan migrate
php artisan db:seed
php artisan key:generate
php artisan serve
````


## Création de la base de données 


## Insertion des nouvelle données 

- Modification des données de test 

- FormateursDataSeeder
  - Ne pas ajouter les groupe de teste
  - Il faut ajouter les nouvelle apprenants


## Exercice pratique pour les formateur : 

ajouter les unité non aligné au session de formation existant 

Il existe 7 unité d'apprentissage non aligné que vous devez ajouter à leurs session de formation

L'objectif de cet exercice et de maîtriser l'utilisation des alignement des unité d’apprentissage et découvrir les session de formation existant