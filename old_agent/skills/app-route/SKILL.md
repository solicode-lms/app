---
name: app-route
description: Expert de l'architecture des Routes web personnalisées et de leur intégration dans Gapp.
---

# Skill : Expert Routes Personnalisées (app-route)

## 🎯 Périmètre Global
**Mission** : Guider l'agent et le développeur pour ajouter des routes personnalisées dans un module généré par Gapp (SoliLMS), sans créer de conflit avec les routes natives (CRUD).

## 📐 Règles d'Architecture des Routes (Gapp)

### 1. Intégrité des Fichiers de Routes Natifs
Les fichiers de routes générés par Gapp (ex: `Routes/[Model]Route.php`) portent l'en-tête de protection `// Ce fichier est maintenu par ESSARRAJ Fouad`. 
**Règle d'or** : Il est interdit de modifier ces fichiers. Toute modification manuelle serait écrasée lors de la prochaine exécution de la commande de regénération (ex: `gapp make:crud`).

### 2. Le Mécanisme de Priorité `.custom.`
Le `BaseServiceProvider` des modules (ex: `BasePkgQcmServiceProvider`) est intelligemment conçu pour charger les fichiers de routes dans un ordre spécifique :
```php
$routeFiles = collect(File::allFiles(__DIR__ .  '/../../../Routes'))
->sortBy(function ($file) {
    $name = $file->getFilename();
    return match (true) {
        str_contains($name, '.custom.') => 0, // Priorité #1
        str_contains($name, '.api.')    => 1, // Priorité #2
        default                         => 10, // Reste des routes (générées)
    };
});
```
Pour ajouter une route de manière permanente et prioritaire, il **faut** créer un fichier portant l'extension `.custom.php` (ex: `AffectationQcmProjetRoute.custom.php`).

### 3. Éviter le Piège du Route::resource
Placer les routes personnalisées dans un fichier `.custom.` garantit qu'elles sont évaluées par Laravel **avant** les routes génériques générées (comme `Route::resource`). 
Cela évite que Laravel n'interprète des segments d'URL spécifiques (comme `/prompt`) comme étant l'identifiant `{id}` de la route `show` générée par défaut.

---

## ⚡ Actions (Orchestration)

### Action A : Créer une Route Personnalisée (CRUD)
> **Description** : Déclarer proprement une ou plusieurs routes supplémentaires pour un modèle Gapp existant (ex: pour une action en base, un export spécifique, ou l'affichage d'une vue personnalisée).

- **Entrées** : `Nom du modèle`, `Spécifications de la route (GET/POST, URI)`
- **Sorties** : Création d'un fichier `Routes/[Model]Route.custom.php`.
- **✅ Points de Contrôle** :
  - Le fichier `.custom.php` doit répliquer le regroupement (middleware `auth` et prefix admin) pour que la route s'intègre correctement dans l'écosystème.
  
- **📝 Instructions d'Orchestration** :
  1. Analyser le préfixe utilisé par Gapp dans le module (ex: `Route::prefix('/admin/PkgQcm')`).
  2. Créer le fichier `[Model]Route.custom.php` dans le dossier `Routes/` du module.
  3. Y injecter la nouvelle route en ciblant une méthode personnalisée dans le `[Model]Controller` enfant.

---

## 🧾 Modèle de Base pour un Fichier Custom

**Fichier :** `modules/PkgQcm/Routes/AffectationQcmProjetRoute.custom.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\AffectationQcmProjetController;

// Regroupement identique au fichier natif pour préserver la sécurité et les URLs
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgQcm')->group(function () {
        
        // Nouvelle route personnalisée chargée en PRIORITÉ
        Route::get('affectationQcmProjets/{id}/prompt', [AffectationQcmProjetController::class, 'prompt'])
            ->name('affectationQcmProjets.prompt');
            
    });
});
```
