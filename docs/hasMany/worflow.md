Description Détaillée de la Solution

La solution proposée permet d'utiliser une seule vue pour deux contextes d'utilisation :

1. Mode CRUD normal : Gestion générale des entités (comme les compétences) sans lien avec un projet spécifique.


2. Mode Workflow : Gestion des entités liées à un projet spécifique via un paramètre projet_id.



L'idée principale est d'adapter dynamiquement la logique et la présentation dans les contrôleurs et les vues en fonction de la présence ou non du paramètre projet_id.


---

Objectif

Mode CRUD Normal :

Permet de gérer des entités globales (comme des compétences) indépendamment d'un projet.

Accessible via /transfert-competence.

Ajoute des compétences directement à la table transfert_competences sans relation avec un projet.


Mode Workflow :

Permet de gérer les compétences associées à un projet spécifique.

Accessible via /workflow/{projet_id}/transfert-competence.

Filtre les compétences liées uniquement à ce projet.

Ajoute une compétence avec une relation au projet via projet_id.




---

Composants de la Solution

1. Routes

Les routes gèrent les deux modes :

Mode CRUD Normal (Routes Laravel classiques pour les ressources).

Mode Workflow (Routes spécifiques utilisant projet_id).


use App\Http\Controllers\TransfertCompetenceController;

// Routes CRUD normales
Route::resource('transfert-competence', TransfertCompetenceController::class);

// Routes Workflow (avec projet_id)
Route::get('/workflow/{projet_id}/transfert-competence', [TransfertCompetenceController::class, 'index'])->name('workflow.transfert-competence.index');
Route::post('/workflow/{projet_id}/transfert-competence', [TransfertCompetenceController::class, 'store'])->name('workflow.transfert-competence.store');

Route /transfert-competence :

Utilisée pour CRUD normal.

Charge toutes les compétences sans lien avec un projet.


Route /workflow/{projet_id}/transfert-competence :

Utilisée pour le workflow.

Charge uniquement les compétences associées au projet identifié par projet_id.




---

2. Contrôleur

Le contrôleur est conçu pour gérer les deux modes dans une méthode unique grâce à une condition basée sur projet_id.

index

Cette méthode charge :

Toutes les compétences dans le mode CRUD normal.

Les compétences liées à un projet dans le mode Workflow.



store

Cette méthode ajoute :

Une compétence indépendante dans le mode CRUD normal.

Une compétence associée à un projet dans le mode Workflow.



Exemple : TransfertCompetenceController

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\TransfertCompetence;
use Illuminate\Http\Request;

class TransfertCompetenceController extends Controller
{
    public function index(Request $request, $projet_id = null)
    {
        if ($projet_id) {
            // Workflow Mode
            $projet = Projet::with('transferts')->findOrFail($projet_id);
            $transferts = $projet->transferts;
        } else {
            // CRUD Normal Mode
            $projet = null;
            $transferts = TransfertCompetence::all();
        }

        return view('transfert-competence.index', compact('transferts', 'projet'));
    }

    public function store(Request $request, $projet_id = null)
    {
        $validated = $request->validate([
            'competence' => 'required|string|max:255',
        ]);

        if ($projet_id) {
            // Workflow Mode
            $projet = Projet::findOrFail($projet_id);
            $projet->transferts()->create(['competence' => $validated['competence']]);
            return redirect()->route('workflow.transfert-competence.index', $projet_id);
        } else {
            // CRUD Normal Mode
            TransfertCompetence::create(['competence' => $validated['competence']]);
            return redirect()->route('transfert-competence.index');
        }
    }
}


---

3. Vue

La vue utilise la variable $projet pour adapter son contenu :

Si $projet est défini, nous sommes en mode Workflow :

Affiche le titre du projet et les compétences associées uniquement à ce projet.

Le formulaire permet d’ajouter une compétence au projet.


Si $projet n'est pas défini, nous sommes en mode CRUD normal :

Affiche toutes les compétences disponibles dans la base de données.

Le formulaire permet d’ajouter une compétence indépendante.



Exemple : transfert-competence/index.blade.php

@extends('layouts.app')

@section('content')
<div class="container">
  <h2>
    @if (isset($projet))
      Étape 2 : Transfert de Compétences pour le projet <strong>{{ $projet->titre }}</strong>
    @else
      Gestion des Transferts de Compétences
    @endif
  </h2>

  <!-- Liste des compétences -->
  <h4>Compétences existantes</h4>
  <ul class="list-group mb-4">
    @foreach ($transferts as $transfert)
      <li class="list-group-item">{{ $transfert->competence }}</li>
    @endforeach
  </ul>

  <!-- Formulaire pour ajouter une nouvelle compétence -->
  <form action="{{ isset($projet) ? route('workflow.transfert-competence.store', $projet->id) : route('transfert-competence.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="competence">Compétence</label>
      <input type="text" class="form-control" name="competence" placeholder="Entrez une compétence" required>
    </div>
    <button type="submit" class="btn btn-primary">
      @if (isset($projet))
        Ajouter au projet
      @else
        Ajouter
      @endif
    </button>
  </form>

  @if (isset($projet))
    <a href="{{ route('projet.index') }}" class="btn btn-secondary mt-3">Retourner à la liste des projets</a>
  @endif
</div>
@endsection


---

4. Modèle Projet

Ajoutez la relation entre un projet et ses compétences.

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    protected $fillable = ['titre', 'description', 'date_debut', 'date_fin'];

    public function transferts()
    {
        return $this->hasMany(TransfertCompetence::class);
    }
}


---

Fonctionnement

1. Mode CRUD Normal

Accès via /transfert-competence.

Liste toutes les compétences globales.

Ajout de compétences non liées à un projet.



2. Mode Workflow

Accès via /workflow/{projet_id}/transfert-competence.

Liste uniquement les compétences liées à un projet spécifique.

Ajout de compétences directement associées au projet via projet_id.



3. Vue Unique

Affiche des informations dynamiques (projet ou non) en fonction de la présence de $projet.





---

Avantages

Réutilisation : Une seule vue pour deux contextes différents.

Flexibilité : Permet de gérer les compétences globalement ou par projet.

Simplicité : La logique est clairement séparée entre contrôleurs, routes et vues.


