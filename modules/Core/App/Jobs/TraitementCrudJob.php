<?php

namespace Modules\Core\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Modules\PkgAutorisation\Models\User;

class TraitementCrudJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected int $userId;

    protected string $module;
    protected string $service; // nom sans suffixe
    protected int $id;
    protected string $token;
    protected string $method;

    /**
     * @param string $module Nom du module (ex: "PkgRealisationProjets")
     * @param string $service Nom de la classe service (ex: "AffectationProjet" → sans suffixe "Service")
     * @param int $id ID de l’entité à traiter
     * @param string $token Jeton unique de suivi
     * @param string $method Nom de la méthode à appeler (par défaut "runAsyncAfterCreate")
     */
    public function __construct(int  $userId, string $module, string $service,string $method, int $id, string $token,  )
    {
        $this->userId = $userId;
        $this->module = $module;
        $this->service = $service;
        $this->id = $id;
        $this->token = $token;
        $this->method = $method;
    }

    public function handle(): void
    {
        
        // Simuler la connexion de l'utilisateur
        $user = User::find($this->userId);
        Auth::login($user); // pour Auth::user()
        Gate::forUser($user); // pour Gate::denies()

        $serviceClass = "Modules\\{$this->module}\\Services\\{$this->service}Service";

        try {
            if (!class_exists($serviceClass)) {
                throw new \RuntimeException("Service {$serviceClass} introuvable.");
            }

            $service = new $serviceClass();

            if (!method_exists($service, $this->method)) {
                throw new \BadMethodCallException("Méthode {$this->method} non trouvée dans {$serviceClass}.");
            }

            $result = $service->{$this->method}($this->id, $this->token);

            Cache::put("traitement.{$this->token}.status", $result ?? 'done', 3600);

        } catch (\Throwable $e) {
            Cache::put("traitement.{$this->token}.status", 'error', 3600);
            Cache::put("traitement.{$this->token}.messageError", $e->getMessage(), 3600);
            throw $e;
          
        }
    }

    /**
     * Détermine si le job courant correspond au post-traitement d'une création.
     * Ajuste la liste selon ta convention de nommage.
     */
    protected function isAfterCreateJob(): bool
    {
        $afterCreateMethods = [
            'afterCreateJob'
        ];

        return in_array($this->method, $afterCreateMethods, true);
    }
}
