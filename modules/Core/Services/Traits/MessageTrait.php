<?php

namespace Modules\Core\Services\Traits;


trait MessageTrait
{

    
    public function pushServiceMessage(string $type, string $title, string $message): void
    {
        // Récupérer les messages existants ou initialiser un tableau vide
        $messages = session()->get('service_messages', []);

        // Ajouter un nouveau message au tableau
        $messages[] = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ];

        // Stocker la liste mise à jour dans la session avec flash
        session()->flash('service_messages', $messages);
    }

}