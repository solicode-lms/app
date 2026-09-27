

- Indiquer dans les rules  .agent/rules, que toute modification d'un composant de l'application doit être en présence de son skills responable afin d'appliquer les bonne régle et capacité.
- Indoquer dans le skill : app-controller que 
  - que toute manimulation d'un service doit être en présenter de skills app-service
  - tous les règle de gestion doit être implémenter dans le service convenable 
  - tous les opération sur un objet doit être effectuer apr son service, car le service est le seul responsable de la gestion de ses données. de même pour un contrôleur, il doit être le seul responsable de la gestion de ses données. et il applique des règle de gestiopn avant et aprés chaque opération. 


- par exemple toute calcule doit être dans le service convenable : 

        // Calcul du temps restant
        $dureeMax = ($realisationQcm->qcm->duree_minutes ?? 60) * 60;
        $tempsEcoule = now()->diffInSeconds($realisationQcm->date_debut);
        $timeRemaining = max(0, $dureeMax - $tempsEcoule);

ne doit pas être dans 

- Déterminer dans le skill app-controller des exemple de code que peut contennire dans un controller et les exemple de code que doit être dans Service pour que la règle soit bien appliqué.


- Le controller peut selectioner des données depuis la base de données, mais pour les oépration de modification de la base de données doit être depuis le service convenable.

- par exemples dans start on :   $realisationQcm->date_debut = now();, donc start doit être un méthode dans service, car il modiier la base de données et il détemriner la règle , que la date de début et la date "now"

- ce ligne doit être dans le service : $etatEnCours = \Modules\PkgQcm\Models\EtatRealisationQcm::where('reference', 'EN_COURS')->first();, car c'est une règle, on a utiliser le référence "EN_COURS" pour trouger l'état etatEnCours, 
  

-    $rup->save(); : il faut ajouter les règle dans le skill: app-service, qui dit que pour modifier les donnés, il faut utiliser le service convenable, on peut pas modifier directement le depuis le model, car il existe des règle de gestion à appliquer avant et aprés chaque action de modification de base de données.