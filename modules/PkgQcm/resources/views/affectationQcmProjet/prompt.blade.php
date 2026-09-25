@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0 text-primary"><i class="fas fa-robot"></i> Insertion des Questions par IA : {{ $affectation->qcm->titre ?? 'QCM' }}</h1>
            </div>
            <div class="col-sm-4 text-right">
                <a href="{{ route('qcms.edit', ['qcm' => $affectation->qcm_id]) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour aux QCM
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid" id="accordion-uas">
        @if($uas && $uas->count() > 0)
            @foreach($uas as $index => $ua)
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header">
                    <h3 class="card-title w-100">
                        <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapse-ua-{{ $ua->id }}">
                            <i class="fas fa-book text-primary"></i> Unité d'Apprentissage : <strong>{{ $ua->nom ?? 'Inconnue' }}</strong>
                            <i class="fas fa-angle-down float-right text-muted mt-1"></i>
                        </a>
                    </h3>
                </div>
                <div id="collapse-ua-{{ $ua->id }}" class="collapse {{ $index === 0 ? 'show' : '' }}" data-parent="#accordion-uas">
                    <div class="card-body">
                        <!-- Contexte Visuel -->
                        <div class="mb-4 p-3 bg-light rounded border-left border-primary" style="border-width: 4px !important;">
                            <h5 class="text-info font-weight-bold"><i class="fas fa-info-circle"></i> Chapitres de l'Unité</h5>
                            <ul class="list-unstyled mb-0">
                                @foreach($ua->chapitres as $chapitre)
                                    <li class="mb-1"><i class="fas fa-angle-right text-muted"></i> <strong>{{ $chapitre->nom }}</strong> : <span class="text-secondary">{{ $chapitre->description ?? 'Aucune description' }}</span></li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="row">
                            <!-- Étape 1 : Le Prompt -->
                            <div class="col-md-6 border-right">
                                <h5 class="text-primary mb-3 font-weight-bold"><i class="fas fa-copy"></i> 1. Copier le Prompt</h5>
                                <p class="text-muted small">Vous pouvez modifier ce texte avant de le copier dans votre IA (ex: ChatGPT).</p>
                                <div class="position-relative mb-2">
                                    <textarea id="prompt-content-{{ $ua->id }}" class="form-control bg-dark text-white p-3" style="font-family: monospace; font-size: 0.85rem; height: 300px; resize: none;">
# 🧠 **Prompt — Génération de QCM JSON**

Génère un **QCM** au **format JSON** strict, sans texte avant ou après.

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
3. "bonneReponse" : Chiffre de 1 à 4.
4. "unite_apprentissage_code" : Toujours "{{ $ua->code }}".

Génère 40 questions.</textarea>
                                </div>
                                <button class="btn btn-outline-primary btn-sm btn-block font-weight-bold" onclick="copyPrompt('prompt-content-{{ $ua->id }}')">
                                    <i class="fas fa-copy"></i> Copier le prompt
                                </button>
                            </div>
                            
                            <!-- Étape 2 : L'Import -->
                            <div class="col-md-6">
                                <h5 class="text-success mb-3 font-weight-bold"><i class="fas fa-file-import"></i> 2. Importer les Questions</h5>
                                <p class="text-muted small">Collez ici le code JSON renvoyé par l'IA.</p>
                                <form id="form-import-{{ $ua->id }}" onsubmit="submitJsonAjax(event, '{{ $ua->id }}', '{{ route('affectationQcmProjets.importIaProcess', ['id' => $affectation->id]) }}')">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <div id="json-editor-{{ $ua->id }}" style="height: 300px; border: 1px solid #ced4da; border-radius: 4px;"></div>
                                        <input type="hidden" name="json_payload" id="hidden_json_{{ $ua->id }}">
                                    </div>
                                    <div id="feedback-{{ $ua->id }}" class="mb-2"></div>
                                    <button type="submit" id="btn-submit-{{ $ua->id }}" class="btn btn-success btn-sm btn-block font-weight-bold">
                                        <i class="fas fa-save"></i> Enregistrer les Questions
                                    </button>
                                    <button type="button" id="btn-view-{{ $ua->id }}" class="btn btn-info btn-sm btn-block d-none font-weight-bold" onclick="showQuestionsModal('{{ $ua->id }}')">
                                        <i class="fas fa-eye"></i> Voir les questions ajoutées
                                    </button>
                                </form>
                            </div>
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
    
    <!-- Bouton de fin de page -->
    <div class="text-center mt-5 mb-5">
        <a href="{{ route('qcms.edit', ['qcm' => $affectation->qcm_id]) }}" class="btn btn-lg btn-primary shadow rounded-pill px-5">
            <i class="fas fa-check-circle"></i> Terminer et retourner à la gestion des Questions
        </a>
    </div>
</section>

<!-- Modale d'affichage des questions -->
<div class="modal fade" id="questionsModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-list-ul"></i> Questions Insérées</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light" id="questionsModalBody" style="max-height: 60vh; overflow-y: auto;">
        <!-- Injecté via JS -->
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.js"></script>
<script>
    window.monacoEditors = {};
    window.insertedQuestions = {}; // Stockage temporaire des questions par UA

    require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' }});
    require(['vs/editor/editor.main'], function() {
        @if($uas && $uas->count() > 0)
            @foreach($uas as $ua)
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

    window.copyPrompt = function(elementId) {
        const textarea = document.getElementById(elementId);
        textarea.select();
        textarea.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(textarea.value).then(() => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: 'Copié !', text: 'Prompt copié dans le presse-papier.', timer: 2000, showConfirmButton: false });
            } else {
                alert('Prompt copié avec succès !');
            }
        });
    }

    // Soumission AJAX
    window.submitJsonAjax = function(e, uaId, url) {
        e.preventDefault();
        const editor = window.monacoEditors[uaId];
        const jsonValue = editor.getValue();
        const feedback = document.getElementById('feedback-' + uaId);
        const btnSubmit = document.getElementById('btn-submit-' + uaId);
        const btnView = document.getElementById('btn-view-' + uaId);
        const token = document.querySelector('input[name="_token"]').value;

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
        feedback.innerHTML = '';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ json_payload: jsonValue, ua_id: uaId })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                feedback.innerHTML = `<div class="alert alert-success py-2 mb-0"><i class="fas fa-check-circle"></i> ${data.message} (${data.count} ajoutées)</div>`;
                window.insertedQuestions[uaId] = data.questions; 
                btnView.classList.remove('d-none');
                btnSubmit.classList.add('d-none');
                
                // Mettre l'éditeur en lecture seule
                editor.updateOptions({ readOnly: true });
            } else {
                feedback.innerHTML = `<div class="alert alert-danger py-2 mb-0"><i class="fas fa-ban"></i> ${data.error || 'Erreur lors de l\'insertion.'}</div>`;
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fas fa-save"></i> Enregistrer les Questions';
            }
        })
        .catch(err => {
            feedback.innerHTML = `<div class="alert alert-danger py-2 mb-0"><i class="fas fa-exclamation-triangle"></i> Erreur lors de la requête serveur. Vérifiez votre JSON.</div>`;
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fas fa-save"></i> Enregistrer les Questions';
        });
    }

    // Affichage Modale
    window.showQuestionsModal = function(uaId) {
        const questions = window.insertedQuestions[uaId];
        const modalBody = document.getElementById('questionsModalBody');
        
        if(!questions || questions.length === 0) {
            modalBody.innerHTML = '<p class="text-muted">Aucune question trouvée.</p>';
        } else {
            let html = '<ul class="list-group shadow-sm">';
            questions.forEach((q, index) => {
                html += `<li class="list-group-item bg-white mb-1 rounded border-0 border-left border-info" style="border-width: 3px !important;">
                            <strong>${index + 1}.</strong> ${q.question ?? q.titre ?? q}
                         </li>`;
            });
            html += '</ul>';
            modalBody.innerHTML = html;
        }
        
        $('#questionsModal').modal('show');
    }
</script>
@endpush
