<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Http\Response;
use Modules\Core\App\Exceptions\BlException;

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

    public function render($request, Throwable $e)
    {
        if ($e instanceof BlException) {
            $message = $e->getMessage();
               
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 400);
            }

            return redirect()->back()->with('error', $message);
        }

        // ⚙️ Fallback : laisser Laravel gérer les autres exceptions
        return parent::render($request, $e);
    }
}
