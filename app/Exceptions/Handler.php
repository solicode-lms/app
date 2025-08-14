<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Enregistre les callbacks pour le reporting des exceptions.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Log::error('Erreur détectée : ' . $e->getMessage(), [
                'exception' => $e,
                'url'       => request()->fullUrl(),
                'input'     => request()->except(['password', 'password_confirmation']), // sécurité
                'user_id'   => Auth::id(),
            ]);
        });
    }
}
