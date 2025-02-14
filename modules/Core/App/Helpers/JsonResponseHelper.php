<?php

namespace Modules\Core\App\Helpers;

use Illuminate\Http\JsonResponse;

class JsonResponseHelper
{
    /**
     * Retourne une réponse JSON standardisée pour les succès
     *
     * @param string $message Message de succès
     * @param array|null $data Données supplémentaires à inclure
     * @param int $statusCode Code HTTP (par défaut 200)
     * @return JsonResponse
     */
  
    public static function success(string $message, array $data = null, int $statusCode = 200): JsonResponse
    {
        // Récupérer les messages de service s'ils existent
        $service_messages = session()->get('service_messages', []);
    
        // Concaténer les messages de service au message principal
        if (!empty($service_messages)) {
            $message .= ' </br> ' . implode(' </br> ', array_map(function ($msg) {
                return "{$msg['title']}: {$msg['message']}";
            }, $service_messages));
        }
    
        // Déterminer le type principal (par défaut 'success', sinon le type du dernier message)
        $type = !empty($service_messages) ? end($service_messages)['type'] : 'success';

        // Construire la réponse JSON
        $response = [
            'success' => true,
            'type' => $type,  // Ajout du type principal
            'title' => 'Opération réalisée avec succès',
            'message' => $message,
        ];
    
        // Ajouter les données si elles existent
        if (!empty($data)) {
            $response['data'] = $data;
        }
    

        return response()->json($response, $statusCode);
    }
    

    /**
     * Retourne une réponse JSON standardisée pour les erreurs
     *
     * @param string $message Message d'erreur
     * @param array|null $errors Liste d'erreurs détaillées
     * @param int $statusCode Code HTTP (par défaut 400)
     * @return JsonResponse
     */
    public static function error(string $message, array $errors = null, int $statusCode = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
