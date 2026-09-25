# Capacité : Commandes Gapp

## 1. Exécution
Les commandes Gapp s'exécutent via l'exécutable natif `gapp` (et non `php artisan`).

## 2. Liste des Commandes
- **`gapp meta:sync`** : Synchronise les modifications de BDD avec les métadonnées de Gapp et génère/exporte les fichiers JSON nécessaires.
- **`gapp meta:seed .`** : Synchronise les métadonnées vers la base de données.
- **`gapp make:crud NomModel .`** : Regénère les fichiers CRUD standard (BaseModel, BaseController, BaseService, FormRequests) pour un modèle spécifique après modification de ses métadonnées.

## 3. Workflow de l'Agent
L'agent ne modifie **JAMAIS** les fichiers Gapp manuellement. Il doit systématiquement demander au développeur de :
1. Copier le JSON fourni.
2. Exécuter `gapp meta:sync`.
3. Exécuter `gapp make:crud NomModel .` (si impact CRUD).
