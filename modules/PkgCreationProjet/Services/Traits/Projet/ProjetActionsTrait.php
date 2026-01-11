<?php

namespace Modules\PkgCreationProjet\Services\Traits\Projet;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Core\App\Exceptions\BlException;
use Modules\PkgCreationProjet\Models\Livrable;
use Modules\PkgCreationProjet\Models\NatureLivrable;

/**
 * Trait ProjetActionsTrait
 * 
 * Contient la logique métier complexe et les workflows spécifiques au Projet.
 */
trait ProjetActionsTrait
{
    /**
     * Clone un projet complet pour le formateur connecté.
     *
     * Duplique le projet et toutes ses dépendances :
     * - Ressources
     * - Livrables
     * - Tâches
     * - Relations Livrables-Tâches
     *
     * @param int $projetId L'ID du projet source.
     * @return mixed Le nouveau projet cloné ou false en cas d'erreur.
     * @throws BlException Si l'utilisateur n'est pas autorisé.
     * @throws \Exception Si l'ID formateur est introuvable.
     */
    public function clonerProjet(int $projetId)
    {
        $formateurId = null;
        if (Auth::user()->hasRole('formateur')) {
            // Récupère l’id du formateur depuis la session utilisateur
            $formateurId = $this->sessionState->get('formateur_id');
            if (!$formateurId) {
                // Sécurité : si le formateur_id n’est pas en session, tu peux lever une exception ou afficher un message d’erreur
                throw new \Exception("Impossible de récupérer l'identifiant du formateur depuis la session.");
            }
        } else {
            // Gestion si l’utilisateur n’est pas formateur
            throw new BlException("Seuls les formateurs peuvent cloner un projet.");
        }


        // On récupère le projet à cloner (avec ses relations)
        $projet = $this->model::with(['taches', 'livrables', 'resources'])->find($projetId);

        if (!$projet) {
            $this->pushServiceMessage("danger", "Clonage projet", "Projet introuvable.");
            return false;
        }

        // On encapsule tout dans une transaction
        return DB::transaction(function () use ($projet, $formateurId) {
            // Clone du projet (hors clé primaire et références uniques)
            $nouveauProjet = $projet->replicate(['id', 'reference']);
            $nouveauProjet->reference = (string) Str::uuid(); // Nouvelle référence unique
            $nouveauProjet->titre .= ' (Cloné)';
            $nouveauProjet->formateur_id = $formateurId;
            $nouveauProjet->push(); // Insert le nouveau projet

            // -- Clonage des ressources --
            foreach ($projet->resources as $resource) {
                $newResource = $resource->replicate(['id', 'reference', 'projet_id']);
                $newResource->reference = (string) Str::uuid();
                $newResource->projet_id = $nouveauProjet->id;
                $newResource->save();
            }

            // -- Clonage des livrables --
            $livrableMap = []; // id_orig => id_clone
            foreach ($projet->livrables as $livrable) {
                $newLivrable = $livrable->replicate(['id', 'reference', 'projet_id']);
                $newLivrable->reference = (string) Str::uuid();
                $newLivrable->projet_id = $nouveauProjet->id;
                $newLivrable->save();
                $livrableMap[$livrable->id] = $newLivrable->id;
            }

            // -- Clonage des tâches --
            $tacheMap = []; // id_orig => id_clone
            foreach ($projet->taches as $tache) {
                $newTache = $tache->replicate(['id', 'reference', 'projet_id']);
                $newTache->reference = (string) Str::uuid();
                $newTache->projet_id = $nouveauProjet->id;
                $newTache->save();
                $tacheMap[$tache->id] = $newTache->id;
            }

            // -- Clonage du pivot Livrable_Tache --
            $pivotTable = DB::table('livrable_tache')
                ->whereIn('tache_id', array_keys($tacheMap))
                ->orWhereIn('livrable_id', array_keys($livrableMap))
                ->get();

            foreach ($pivotTable as $pivot) {
                // On ne clone que si les deux existent dans la nouvelle map
                if (isset($tacheMap[$pivot->tache_id]) && isset($livrableMap[$pivot->livrable_id])) {
                    DB::table('livrable_tache')->insert([
                        'tache_id' => $tacheMap[$pivot->tache_id],
                        'livrable_id' => $livrableMap[$pivot->livrable_id],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Message de succès
            $this->pushServiceMessage("success", "Clonage projet", "Le projet a été cloné avec succès.");
            return $nouveauProjet;
        });
    }

    /**
     * Ajoute les livrables par défaut à un projet.
     * 
     * Crée automatiquement les entrées pour "Code source" et "Présentation"
     * en se basant sur les références de nature de livrable.
     *
     * @param mixed $projet Le projet cible.
     * @return void
     */
    protected function addDefaultLivrables($projet)
    {
        $defaultLivrables = [
            [
                'titre' => 'Code source',
                'description' => 'Livrable contenant le code source complet du projet',
                'natureReference' => 'Code'
            ],
            [
                'titre' => 'Présentation',
                'description' => 'Présentation du projet (slides, vidéo, etc.)',
                'natureReference' => 'Présentation'
            ],
        ];

        foreach ($defaultLivrables as $livrableData) {
            // Récupérer l’ID de la nature correspondant à la référence
            $natureId = NatureLivrable::where('reference', $livrableData['natureReference'])->value('id');

            Livrable::firstOrCreate(
                [
                    'projet_id' => $projet->id,
                    'titre' => $livrableData['titre'],
                ],
                [
                    'description' => $livrableData['description'],
                    'nature_livrable_id' => $natureId, // null si introuvable
                ]
            );
        }
    }
}
