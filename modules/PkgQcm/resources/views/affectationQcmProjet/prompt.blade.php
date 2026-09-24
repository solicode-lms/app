@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-primary"><i class="fas fa-robot"></i> Assistant IA pour le QCM : {{ $affectation->qcm->titre ?? 'QCM' }}</h1>
            </div>
            <div class="col-sm-6">
                <!-- Messages de retour -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible shadow-sm py-1 mb-0 float-right">
                        <button type="button" class="close py-1" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fas fa-check"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible shadow-sm py-1 mb-0 float-right">
                        <button type="button" class="close py-1" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fas fa-ban"></i> {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if($uas && $uas->count() > 0)
            @foreach($uas as $ua)
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-book"></i> Unité d'Apprentissage : <strong>{{ $ua->nom ?? 'Inconnue' }}</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Étape 1 : Le Prompt -->
                        <div class="col-md-6 border-right">
                            <h5 class="text-primary mb-3"><i class="fas fa-copy"></i> 1. Copier le Prompt</h5>
                            <p class="text-muted small">Copiez ce texte et collez-le dans votre IA (ChatGPT, Claude...) pour générer les questions.</p>
                            <div class="position-relative mb-2">
                                <textarea id="prompt-content-{{ $ua->id }}" class="form-control bg-dark text-white p-3" style="font-family: monospace; font-size: 0.85rem; height: 350px; resize: none;" readonly>
# 🧠 **Prompt — Génération de QCM JSON**

Génère un **QCM** au **format JSON** strict, sans aucun texte additionnel ni explication.

Sujets à évaluer :
- **Unité d'Apprentissage** : {{ $ua->nom ?? 'Inconnue' }}
@foreach($ua->chapitres as $chapitre)
- {{ $chapitre->nom }} : {{ $chapitre->description ?? '' }}
@endforeach

Format attendu :
[{"question":"...","reponses":["A","B","C","D"],"bonneReponse":1,"points":1,"unite_apprentissage_code":"{{ $ua->code }}"}]

Règles obligatoires :
1. Renvoie UNIQUEMENT le tableau JSON.
2. "reponses" : 4 propositions.
3. "bonneReponse" : Chiffre de 1 à 4 (varie la position).
4. "unite_apprentissage_code" : Toujours "{{ $ua->code }}".

Génère 40 questions.</textarea>
                            </div>
                            <button class="btn btn-primary btn-sm btn-block" onclick="copyPrompt('prompt-content-{{ $ua->id }}')">
                                <i class="fas fa-copy"></i> Copier le prompt
                            </button>
                        </div>
                        
                        <!-- Étape 2 : L'Import -->
                        <div class="col-md-6">
                            <h5 class="text-success mb-3"><i class="fas fa-file-import"></i> 2. Importer les Questions</h5>
                            <p class="text-muted small">Collez ici le code JSON renvoyé par l'IA pour cette unité, puis validez.</p>
                            <form action="{{ route('affectationQcmProjets.importIaProcess', ['id' => $affectation->id]) }}" method="POST">
                                @csrf
                                <div class="form-group mb-2">
                                    <textarea name="json_payload" class="form-control" style="font-family: monospace; font-size: 0.85rem; height: 350px;" required placeholder="[
  {
    &quot;question&quot;: &quot;...&quot;,
    &quot;reponses&quot;: [&quot;A&quot;, &quot;B&quot;, &quot;C&quot;, &quot;D&quot;],
    &quot;bonneReponse&quot;: 1,
    &quot;points&quot;: 1,
    &quot;unite_apprentissage_code&quot;: &quot;...&quot;
  }
]">{{ old('json_payload') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm btn-block"><i class="fas fa-save"></i> Enregistrer les Questions de cette UA</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="alert alert-warning">
                Aucune Unité d'Apprentissage n'a été trouvée pour le projet rattaché à ce QCM.
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    window.copyPrompt = function(elementId) {
        const textarea = document.getElementById(elementId);
        console.log(textarea);
        textarea.select();
        textarea.setSelectionRange(0, 99999);
        
        navigator.clipboard.writeText(textarea.value).then(() => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Copié !',
                    text: 'Le prompt a été copié.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert('Prompt copié avec succès !');
            }
        });
    }
</script>
@endpush
