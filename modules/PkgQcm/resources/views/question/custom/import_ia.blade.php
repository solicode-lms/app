@extends('layouts.admin')

@section('title', 'Générer et Importer via IA')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-primary"><i class="fas fa-robot"></i> Assistant IA pour les Questions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('questions.index') }}">Questions</a></li>
                    <li class="breadcrumb-item active">Import IA</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Messages de retour -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible shadow-sm">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Succès !</h5>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible shadow-sm">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Erreur !</h5>
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <!-- Étape 1 : Le Prompt -->
            <div class="col-md-6">
                <div class="card card-outline card-primary shadow-sm h-100">
                    <div class="card-header">
                        <h3 class="card-title">1. Générer le QCM avec l'IA</h3>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="form-group">
                            <label for="ua-selector">Sélectionner l'Unité d'Apprentissage (UA)</label>
                            <select id="ua-selector" class="form-control select2" onchange="updatePrompt()">
                                <option value="" disabled selected>-- Choisissez une UA --</option>
                                @foreach($uas as $ua)
                                    @php
                                        // On prépare la liste des chapitres pour cette UA
                                        $chapitresTexte = "";
                                        if($ua->chapitres && $ua->chapitres->count() > 0) {
                                            foreach($ua->chapitres as $chap) {
                                                $desc = $chap->description ? ' ('.$chap->description.')' : '';
                                                $chapitresTexte .= "- " . addslashes($chap->nom) . $desc . '\n';
                                            }
                                        } else {
                                            $chapitresTexte = "- Notions fondamentales de cette unité.\n";
                                        }
                                    @endphp
                                    <option value="{{ $ua->id }}" 
                                            data-nom="{{ addslashes($ua->nom) }}" 
                                            data-code="{{ $ua->code }}"
                                            data-chapitres="{{ $chapitresTexte }}">
                                        {{ $ua->code }} - {{ $ua->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="position-relative flex-grow-1 mt-3" style="display: none;" id="prompt-container">
                            <label>Prompt généré :</label>
                            <textarea id="prompt-textarea" class="form-control bg-dark text-white p-3 mb-2" style="font-family: monospace; font-size: 0.85rem; height: 350px; resize: none;" readonly></textarea>
                            <button class="btn btn-primary btn-block rounded-pill shadow-sm" onclick="copyPrompt()">
                                <i class="fas fa-copy"></i> Copier ce prompt
                            </button>
                            <small class="text-muted text-center d-block mt-2">Collez ensuite ce texte dans ChatGPT ou Claude.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 2 : L'Import -->
            <div class="col-md-6">
                <div class="card card-outline card-success shadow-sm h-100">
                    <div class="card-header">
                        <h3 class="card-title">2. Importer les Questions</h3>
                    </div>
                    <form action="{{ route('questions.importIaProcess') }}" method="POST" class="d-flex flex-column h-100">
                        @csrf
                        <div class="card-body d-flex flex-column flex-grow-1">
                            <div class="form-group flex-grow-1 d-flex flex-column">
                                <label for="json_payload">Coller le code JSON renvoyé par l'IA :</label>
                                <textarea name="json_payload" id="json_payload" class="form-control flex-grow-1" style="font-family: monospace; font-size: 0.85rem; min-height: 350px;" required placeholder="[
  {
    &quot;question&quot;: &quot;...&quot;,
    &quot;reponses&quot;: [&quot;A&quot;, &quot;B&quot;, &quot;C&quot;, &quot;D&quot;],
    &quot;bonneReponse&quot;: 1,
    &quot;points&quot;: 1,
    &quot;unite_apprentissage_code&quot;: &quot;...&quot;
  }
]">{{ old('json_payload') }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('questions.index') }}" class="btn btn-default mr-2">Annuler</a>
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer les Questions</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<!-- Initialisation de Select2 -->
<script>
    $(function () {
        if($.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        }
    });

    function updatePrompt() {
        const selector = document.getElementById('ua-selector');
        const selectedOption = selector.options[selector.selectedIndex];
        
        if (!selectedOption || !selectedOption.value) return;

        const nom = selectedOption.getAttribute('data-nom');
        const code = selectedOption.getAttribute('data-code');
        const chapitres = selectedOption.getAttribute('data-chapitres');

        // Construction du prompt complet
        const promptText = `Génère un QCM au format JSON strict, sans aucun texte additionnel ni explication.

Sujets à évaluer :
- Unité d'Apprentissage : ${nom}
${chapitres.replace(/\\n/g, '\n')}
Format attendu :
[{"question":"...","reponses":["A","B","C","D"],"bonneReponse":1,"points":1,"unite_apprentissage_code":"${code}"}]

Règles obligatoires :
1. Renvoie UNIQUEMENT le tableau JSON.
2. "reponses" : 4 propositions.
3. "bonneReponse" : Chiffre de 1 à 4.
4. "unite_apprentissage_code" : Toujours "${code}".

Génère 40 questions.`;

        document.getElementById('prompt-textarea').value = promptText;
        document.getElementById('prompt-container').style.display = 'block';
    }

    function copyPrompt() {
        const textarea = document.getElementById('prompt-textarea');
        textarea.select();
        textarea.setSelectionRange(0, 99999);
        
        navigator.clipboard.writeText(textarea.value).then(() => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Prompt copié !',
                    text: 'Prêt à être collé dans l\'IA.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                alert('Prompt copié avec succès !');
            }
        });
    }
</script>
@endpush
