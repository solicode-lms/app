<?php

// app/Exceptions/Handler.php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Session\TokenMismatchException;
use Modules\Core\App\Exceptions\BlException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        // 1) Validation (422)
        $this->renderable(function (ValidationException $e, $request) {
            $message = "Certaines données sont invalides.";
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors'  => $e->errors(),
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        });

        // 2) Non authentifié (401) → JSON ou redirection login
        $this->renderable(function (AuthenticationException $e, $request) {
            $message = "Authentification requise.";
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 401)
                : redirect()->guest(route('login'))->with('error', $message);
        });

        // 3) Non autorisé (403)
        $this->renderable(function (AuthorizationException $e, $request) {
            $message = "Action non autorisée.";
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 403)
                : back()->with('error', $message);
        });

        // 3bis) Spatie\Permission Unauthorized (403) si présent
        $this->renderable(function (Throwable $e, $request) {
            if (class_exists(\Spatie\Permission\Exceptions\UnauthorizedException::class)
                && $e instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
                $message = "Permissions insuffisantes pour accéder à cette ressource.";
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => $message], 403)
                    : back()->with('error', $message);
            }
            return null;
        });

        // 4) Modèle introuvable (404)
        $this->renderable(function (ModelNotFoundException $e, $request) {
            $message = "Ressource introuvable.";
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 404)
                : response()->view('errors.404', ['message' => $message], 404);
        });

        // 5) Route introuvable (404)
        $this->renderable(function (NotFoundHttpException $e, $request) {
            $message = "Page introuvable.";
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 404)
                : response()->view('errors.404', ['message' => $message], 404);
        });

        // 6) Méthode HTTP non autorisée (405)
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            $message = "Méthode HTTP non autorisée pour cette route.";
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 405)
                : response()->view('errors.405', ['message' => $message], 405);
        });

        // 7) CSRF expiré (419)
        $this->renderable(function (TokenMismatchException $e, $request) {
            $message = "Session expirée. Veuillez réessayer.";
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 419)
                : response()->view('errors.419', ['message' => $message], 419);
        });

        // 8) Payload trop gros (413)
        $this->renderable(function (PostTooLargeException $e, $request) {
            $message = "Le fichier ou la requête est trop volumineux.";
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 413)
                : response()->view('errors.413', ['message' => $message], 413);
        });

        // 9) Trop de requêtes (429)
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            $message = "Trop de requêtes. Veuillez patienter avant de réessayer.";
            $headers = method_exists($e, 'getHeaders') ? $e->getHeaders() : [];
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 429, $headers)
                : response()->view('errors.429', ['message' => $message], 429, $headers);
        });

        // 10) Mass assignment / Guarded (422)
        $this->renderable(function (MassAssignmentException $e, $request) {
            $message = "Données non autorisées pour cette opération.";
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->with('error', $message);
        });

        // 11) Erreurs SQL courantes (MySQL) : FK/UNIQUE/NOT NULL/length/date/out of range/etc.
        $this->renderable(function (QueryException $e, $request) {
            $sqlState = $e->errorInfo[0] ?? null;        // '23000', '22001', ...
            $code     = (int) ($e->errorInfo[1] ?? 0);   // 1451, 1062, 1048, 1406...

            // Deadlock/timeout/connexion
            if (in_array($code, [1213, 1205], true)) { // deadlock / lock wait timeout
                $msg = "Conflit de concurrence détecté. Réessayez dans un instant.";
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => $msg], 409)
                    : back()->with('error', $msg);
            }
            if (in_array($code, [2002, 2006], true)) { // can't connect / server gone away
                $msg = "Le service base de données est momentanément indisponible.";
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => $msg], 503)
                    : response()->view('errors.503', ['message' => $msg], 503);
            }

            // Cartographie principale
            $map = [
                // Intégrité (UNIQUE, FK, NOT NULL, CHECK...)
                '23000' => [
                    1451 => ["Impossible de supprimer cet élément car d’autres en dépendent.", 409], // FK parent
                    1452 => ["Relation invalide : l’élément parent est introuvable.", 409],        // FK child
                    1217 => ["Suppression impossible (contrainte de dépendance).", 409],
                    1216 => ["Ajout/mise à jour impossible (contrainte de dépendance).", 409],
                    1062 => ["Un enregistrement identique existe déjà (unicité).", 409],
                    1048 => ["Champ obligatoire manquant (valeur NULL interdite).", 422],
                    3819 => ["Règle métier (CHECK) non respectée.", 422],
                    'default' => ["Contrainte d’intégrité violée.", 409],
                ],
                // Donnée trop longue
                '22001' => [
                    1406 => ["Donnée trop longue pour ce champ.", 422],
                    'default' => ["Donnée trop longue pour ce champ.", 422],
                ],
                // Hors limites
                '22003' => [
                    1264 => ["Valeur hors limites pour ce champ.", 422],
                    'default' => ["Valeur numérique hors limites.", 422],
                ],
                // Date/heure invalide
                '22007' => [
                    1292 => ["Date/heure invalide.", 422],
                    'default' => ["Date/heure invalide.", 422],
                ],
                // Valeur/encodage invalide (emoji sur collation non UTF8MB4, etc.)
                'HY000' => [
                    1366 => ["Valeur invalide pour ce champ (format/encodage).", 422],
                ],
            ];

            if (!isset($map[$sqlState])) {
                return null; // laisse d’autres handlers/fallback parler
            }

            [$message, $status] = $map[$sqlState][$code]
                ?? $map[$sqlState]['default']
                ?? ["Erreur de base de données.", 500];

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], $status)
                : ($status === 404
                    ? response()->view('errors.404', ['message' => $message], 404)
                    : back()->with('error', $message));
        });

        // 12) HTTP exceptions "propres" (ex: abort(4xx/5xx) avec message)
        $this->renderable(function (HttpExceptionInterface $e, $request) {
            $status  = $e->getStatusCode();
            $message = $e->getMessage() ?: match (true) {
                $status === 401 => "Authentification requise.",
                $status === 403 => "Action non autorisée.",
                $status === 404 => "Page introuvable.",
                $status === 405 => "Méthode HTTP non autorisée.",
                $status === 419 => "Session expirée. Veuillez réessayer.",
                $status === 429 => "Trop de requêtes. Réessayez plus tard.",
                default         => "Une erreur est survenue.",
            };

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], $status);
            }

            // Afficher des vues dédiées si disponibles, sinon flash + back
            $view = in_array($status, [404, 405, 419, 429, 503], true) ? "errors.$status" : null;
            return $view
                ? response()->view($view, ['message' => $message], $status)
                : ($status >= 400 && $status < 500
                    ? back()->with('error', $message)
                    : response()->view('errors.500', ['message' => $message], 500));
        });
    }

    public function render($request, Throwable $e)
    {
        // Exceptions métiers gérées en priorité
        if ($e instanceof BlException) {
            $message = $e->getMessage();
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 400)
                : back()->with('error', $message);
        }

        // Laisse les "renderable()" se déclencher
        $response = parent::render($request, $e);

        // Fallback production : si 5xx, message générique + ID d’erreur
        if (app()->isProduction() && $this->isServerError($response)) {
            $errorId = (string) Str::uuid();
            Log::error('Unhandled exception', [
                'error_id'  => $errorId,
                'url'       => $request->fullUrl(),
                'user_id'   => auth()->id(),
                'exception' => $e,
            ]);

            $message = "Une erreur est survenue. Si le problème persiste, communiquez l’ID d’erreur : $errorId";

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 500)
                : response()->view('errors.500', ['message' => $message, 'errorId' => $errorId], 500);
        }

        return $response;
    }

    private function isServerError($response): bool
    {
        return $response instanceof SymfonyResponse && $response->getStatusCode() >= 500;
    }
}