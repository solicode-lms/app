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
                            <p class="text-muted small">Vous pouvez modifier ce texte avant de le copier dans votre IA (ChatGPT, Claude...).</p>
                            <div class="position-relative mb-2">
                                <textarea id="prompt-content-{{ $ua->id }}" class="form-control bg-dark text-white p-3" style="font-family: monospace; font-size: 0.85rem; height: 350px; resize: none;">
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
                            <form action="{{ route('affectationQcmProjets.importIaProcess', ['id' => $affectation->id]) }}" method="POST" onsubmit="return syncEditor('{{ $ua->id }}')">
                                @csrf
                                <div class="form-group mb-2">
                                    <!-- Conteneur pour Monaco Editor -->
                                    <div id="json-editor-{{ $ua->id }}" style="height: 350px; border: 1px solid #ced4da; border-radius: 4px;"></div>
                                    <!-- Input caché pour envoyer les données -->
                                    <input type="hidden" name="json_payload" id="hidden_json_{{ $ua->id }}">
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
<!-- Monaco Editor CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.js"></script>
<script>
    // Dictionnaire pour stocker les instances de Monaco Editor
    window.monacoEditors = {};

    require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' }});
    require(['vs/editor/editor.main'], function() {
        @if($uas && $uas->count() > 0)
            @foreach($uas as $ua)
                // Initialisation de Monaco Editor pour chaque UA
                window.monacoEditors['{{ $ua->id }}'] = monaco.editor.create(document.getElementById('json-editor-{{ $ua->id }}'), {
                    value: "[\n  {\n    \"question\": \"...\",\n    \"reponses\": [\"A\", \"B\", \"C\", \"D\"],\n    \"bonneReponse\": 1,\n    \"points\": 1,\n    \"unite_apprentissage_code\": \"{{ $ua->code }}\"\n  }\n]",
                    language: 'json',
                    theme: 'vs-light',
                    automaticLayout: true,
                    minimap: { enabled: false },
                    scrollBeyondLastLine: false
                });
            @endforeach
        @endif
    });

    // Fonction pour synchroniser le contenu de Monaco Editor vers l'input caché avant la soumission
    window.syncEditor = function(uaId) {
        if (window.monacoEditors[uaId]) {
            document.getElementById('hidden_json_' + uaId).value = window.monacoEditors[uaId].getValue();
            return true;
        }
        return false;
    };

    window.copyPrompt = function(elementId) {
        const textarea = document.getElementById(elementId);
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
