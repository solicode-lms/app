<?php

namespace Modules\PkgTableauBord\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgTableauBord\Models\LogActiviteFormateur;

class ActiviteFormateurController extends AdminController
{
    /**
     * @DynamicPermissionIgnore
     */
    public function ping(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $formateur = Formateur::where('user_id', $user->id)->first();
        if (!$formateur) {
            return response()->json(['status' => 'ignored', 'message' => 'Utilisateur non rattaché à un profil Formateur.'], 200);
        }

        $valides = LogActiviteFormateur::getTypesValides();
        $typeActivite = $request->input('type_activite');

        if (!in_array($typeActivite, $valides, true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Type d\'activité invalide. Valeurs permises : ' . implode(', ', $valides)
            ], 422);
        }

        $duree = min(max((int) $request->input('duree_secondes', 60), 1), 300);
        $today = now()->toDateString();
        $semaine = now()->format('o-\WW');

        $existing = DB::table('log_activite_formateurs')
            ->where('formateur_id', $formateur->id)
            ->where('date_activite', $today)
            ->where('type_activite', $typeActivite)
            ->first();

        if ($existing) {
            DB::table('log_activite_formateurs')
                ->where('id', $existing->id)
                ->increment('duree_secondes', $duree, ['updated_at' => now()]);
        } else {
            DB::table('log_activite_formateurs')->insert([
                'formateur_id' => $formateur->id,
                'type_activite' => $typeActivite,
                'duree_secondes' => $duree,
                'date_activite' => $today,
                'semaine_annee' => $semaine,
                'reference' => 'LOG_ACT_' . $formateur->id . '_' . str_replace('-', '', $today) . '_' . $typeActivite,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'formateur_id' => $formateur->id,
            'type_activite' => $typeActivite,
            'duree_secondes' => $duree,
        ]);
    }
}
