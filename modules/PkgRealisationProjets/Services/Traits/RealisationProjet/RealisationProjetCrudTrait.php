<?php

namespace Modules\PkgRealisationProjets\Services\Traits\RealisationProjet;

use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\PkgNotification\Enums\NotificationType;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgAutorisation\Models\Role;
use Illuminate\Validation\ValidationException;

trait RealisationProjetCrudTrait
{
    /**
     * Règles métiers appliquées avant la mise à jour d'un RealisationProjet.
     *
     * @param array $data Données à mettre à jour (passées par référence).
     * @param int $id Identifiant de l'entité à modifier.
     * @return void
     * @throws ValidationException En cas de violation de règles métier.
     */
    public function beforeUpdateRules(array &$data, int $id): void
    {
        $entity = $this->find($id);

        if (empty($entity)) {
            throw ValidationException::withMessages([
                'id' => "Projet de réalisation introuvable."
            ]);
        }

        // 🛡️ Vérification de changement d'état
        if (!empty($data["etats_realisation_projet_id"])) {
            $nouvelEtatId = $data["etats_realisation_projet_id"];

            $etatActuel = $entity->etatsRealisationProjet;

            // Charger le nouvel état pour validation
            $nouvelEtat = EtatsRealisationProjet::find($nouvelEtatId);

            if (!$nouvelEtat) {
                throw ValidationException::withMessages([
                    'etats_realisation_projet_id' => "L'état sélectionné est invalide."
                ]);
            }

            // 🛡️ 1. Empêcher la modification d'un état actuel protégé
            if ($etatActuel) {
                if (
                    $etatActuel->is_editable_by_formateur
                    && $etatActuel->id !== $nouvelEtatId
                    && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)
                ) {
                    throw ValidationException::withMessages([
                        'etats_realisation_projet_id' => "L'état actuel du projet ne peut être changé que par un formateur."
                    ]);
                }
            }

            // 🛡️ 2. Empêcher l'affectation d'un nouvel état protégé
            if (
                $nouvelEtat->is_editable_by_formateur
                && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)
            ) {
                throw ValidationException::withMessages([
                    'etats_realisation_projet_id' => "Vous ne pouvez pas affecter cet état réservé au formateur."
                ]);
            }
        }

        // 🛡️ 3. Vérification cohérence dates (facultatif mais recommandé)
        if (isset($data['date_debut'], $data['date_fin']) && $data['date_debut'] > $data['date_fin']) {
            throw ValidationException::withMessages([
                'date_fin' => "La date de fin doit être postérieure à la date de début."
            ]);
        }
    }

    public function afterCreateRules($realisationProjet): void
    {
        if (!$realisationProjet instanceof RealisationProjet) {
            return; // 🛡️ Vérification de sécurité
        }
        // Étape 1 : Affecter l'état "TODO" s'il existe
        if (empty($realisationProjet->etats_realisation_projet_id)) {
            $etatTodo = EtatsRealisationProjet::where('code', 'TODO')->first();

            if ($etatTodo) {
                $realisationProjet->etats_realisation_projet_id = $etatTodo->id;
                $realisationProjet->save();
            }
        }
        // Étape 2 : Notification
        $this->notifierApprenant($realisationProjet);

        // Étape 3 : Création des RealisationTache
        $realisationTacheService = new RealisationTacheService();
        $realisationTacheService->generateFromRealisationProjet($realisationProjet);
    }
}
