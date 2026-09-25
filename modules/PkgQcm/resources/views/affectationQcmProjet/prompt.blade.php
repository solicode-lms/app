@extends('layouts.admin')

@section('content')
<div id="affectation-crud" class="crud">
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
    <!-- Panneau de Configuration du Prompt (Sticky) -->
    <div class="card shadow-sm mb-2" style="position: sticky; top: 0; z-index: 1020; border-top: 3px solid #17a2b8; border-bottom: 1px solid #17a2b8;">
        <div class="card-body py-2 px-3 bg-white">
            <div class="row align-items-end">
                <!-- Nombre de questions -->
                <div class="col-md-2 col-sm-6 mb-2 mb-md-0">
                    <label for="param-count" class="text-muted text-sm mb-1"><i class="fas fa-list-ol mr-1"></i> Nb. questions</label>
                    <input type="number" id="param-count" class="form-control form-control-sm border-info font-weight-bold text-center" value="40" min="1" max="100" onchange="updateAllPrompts()">
                </div>
                <!-- Langue -->
                <div class="col-md-2 col-sm-6 mb-2 mb-md-0">
                    <label for="param-lang" class="text-muted text-sm mb-1"><i class="fas fa-language mr-1"></i> Langue</label>
                    <select id="param-lang" class="form-control form-control-sm border-info custom-select custom-select-sm" onchange="updateAllPrompts()">
                        <option value="Français">🇫🇷 Français</option>
                        <option value="Anglais">🇬🇧 Anglais</option>
                        <option value="Arabe">🇲🇦 Arabe</option>
                    </select>
                </div>
                <!-- Niveau de langue -->
                <div class="col-md-2 col-sm-6 mb-2 mb-md-0">
                    <label for="param-level" class="text-muted text-sm mb-1"><i class="fas fa-graduation-cap mr-1"></i> Niveau de langue</label>
                    <select id="param-level" class="form-control form-control-sm border-info custom-select custom-select-sm" onchange="updateAllPrompts()">
                        <option value="Débutant (A1/A2)">🟢 Débutant</option>
                        <option value="Intermédiaire (B1/B2)" selected>🟡 Intermédiaire</option>
                        <option value="Avancé (C1/C2)">🔴 Avancé</option>
                    </select>
                </div>
                <!-- Ton -->
                <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                    <label for="param-tone" class="text-muted text-sm mb-1"><i class="fas fa-comment-dots mr-1"></i> Ton / Style</label>
                    <select id="param-tone" class="form-control form-control-sm border-info custom-select custom-select-sm" onchange="updateAllPrompts()">
                        <option value="Pédagogique et professionnel">👨‍🏫 Pédagogique</option>
                        <option value="Académique et strict">🏛️ Académique strict</option>
                        <option value="Ludique et encourageant">🎮 Ludique & Encourageant</option>
                    </select>
                </div>
                <!-- Difficulté / Détails -->
                <div class="col-md-3 col-sm-12 mb-2 mb-md-0">
                    <label for="param-diff" class="text-muted text-sm mb-1"><i class="fas fa-brain mr-1"></i> Difficulté technique</label>
                    <select id="param-diff" class="form-control form-control-sm border-info custom-select custom-select-sm" onchange="updateAllPrompts()">
                        <option value="Normal (Équilibré)">⚖️ Normal (Équilibré)</option>
                        <option value="Facile (Concepts de base)">🌱 Facile (Bases)</option>
                        <option value="Difficile (Détails et pièges)">🔥 Difficile (Pièges)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="accordion-uas">
        @if($uas && $uas->count() > 0)
            @foreach($uas as $index => $ua)
            <div class="card card-outline card-primary shadow-sm mb-2">
                <div class="card-header">
                    <h3 class="card-title w-100">
                        <a class="d-flex w-100 text-dark align-items-center" data-toggle="collapse" href="#collapse-ua-{{ $ua->id }}">
                            <div class="flex-grow-1">
                                <i class="fas fa-book text-primary"></i> Unité d'Apprentissage : <strong>{{ $ua->code }} - {{ $ua->nom ?? 'Inconnue' }}</strong>
                            </div>
                            <div class="mr-3">
                                <span class="badge badge-info px-2 py-1">
                                    <i class="fas fa-question-circle"></i> <span id="q-count-header-{{ $ua->id }}">{{ $affectation->qcm->questions()->where('unite_apprentissage_id', $ua->id)->count() }}</span> questions
                                </span>
                            </div>
                            <i class="fas fa-angle-down text-muted"></i>
                        </a>
                    </h3>
                </div>
                <div id="collapse-ua-{{ $ua->id }}" class="collapse {{ $index === 0 ? 'show' : '' }}" data-parent="#accordion-uas">
                    <div class="card-body p-2">
                        <!-- Contexte Visuel -->
                        <div class="mb-2 p-2 bg-light rounded border-left border-primary" style="border-width: 3px !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="text-info font-weight-bold m-0"><i class="fas fa-info-circle"></i> Chapitres de l'Unité</h6>
                                
                                <!-- Lien vers la gestion des questions -->
                                <div>
                                    <a href="{{ route('questions.index', ['unite_apprentissage_id' => $ua->id, 'qcm_id' => $affectation->qcm_id, 'showIndex' => 1]) }}" class="btn btn-xs btn-outline-info font-weight-bold showIndex">
                                        <i class="fas fa-external-link-square-alt"></i> Gérer
                                    </a>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-0" style="font-size: 0.85rem;">
                                @foreach($ua->chapitres as $chapitre)
                                    <li class="mb-0">
                                        <i class="fas fa-angle-right text-muted"></i> <strong>{{ $chapitre->nom }}</strong> : 
                                        <span class="text-secondary">{{ $chapitre->description ?? 'Aucune description' }}</span>
                                        @if($chapitre->lien)
                                            <a href="{{ $chapitre->lien }}" target="_blank" class="badge badge-info ml-1" title="Tutoriel / Lien de cours">
                                                <i class="fas fa-external-link-alt"></i> Tuto
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="row">
                            <!-- Étape 1 : Le Prompt -->
                            <div class="col-md-6 border-right">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="text-primary m-0 font-weight-bold"><i class="fas fa-copy"></i> 1. Copier le Prompt</h6>
                                    <div>
                                        <button type="button" onclick="openIA('chatgpt', '{{ $ua->id }}')" class="btn btn-xs btn-light border text-muted px-1 py-0"><i class="fas fa-robot text-success"></i> ChatGPT</button>
                                        <button type="button" onclick="openIA('claude', '{{ $ua->id }}')" class="btn btn-xs btn-light border text-muted px-1 py-0"><i class="fas fa-brain text-warning"></i> Claude</button>
                                        <button type="button" onclick="openIA('gemini', '{{ $ua->id }}')" class="btn btn-xs btn-light border text-muted px-1 py-0"><i class="fas fa-magic text-primary"></i> Gemini</button>
                                    </div>
                                </div>
                                <div class="position-relative mb-2">
                                    <div id="template-prompt-{{ $ua->id }}" class="d-none"># 🧠 **Prompt — Génération de QCM JSON**

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
5. Langue : __LANG__
6. Ton : __TONE__
7. Niveau de langue : __LEVEL__
8. Difficulté et Détail : __DIFF__

Génère __COUNT__ questions.

---
📄 **Contenu de référence pour la génération des questions :**
@foreach($ua->chapitres as $chapitre)
@if($chapitre->lien)
@php $tutoContent = $tutosContent[$chapitre->id] ?? null; @endphp
@if($tutoContent)

📍 **Chapitre : {{ $chapitre->nom }}**
{{ \Illuminate\Support\Str::limit($tutoContent, 3000) }}
@endif
@endif
@endforeach</div>
                                    <textarea id="prompt-content-{{ $ua->id }}" class="form-control bg-dark text-white p-2" style="font-family: monospace; font-size: 0.75rem; height: 180px; resize: none;"></textarea>
                                </div>
                                <button class="btn btn-outline-primary btn-sm btn-block font-weight-bold py-1" onclick="copyPrompt('prompt-content-{{ $ua->id }}')">
                                    <i class="fas fa-copy"></i> Copier
                                </button>
                            </div>
                            
                            <!-- Étape 2 : L'Import -->
                            <div class="col-md-6">
                                <h6 class="text-success mb-1 font-weight-bold"><i class="fas fa-file-import"></i> 2. Importer les Questions (JSON)</h6>
                                <form id="form-import-{{ $ua->id }}" onsubmit="submitJsonAjax(event, '{{ $ua->id }}', '{{ route('affectationQcmProjets.importIaProcess', ['id' => $affectation->id]) }}')">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <div id="json-editor-{{ $ua->id }}" style="height: 180px; border: 1px solid #ced4da; border-radius: 4px;"></div>
                                        <input type="hidden" name="json_payload" id="hidden_json_{{ $ua->id }}">
                                    </div>
                                    <div id="feedback-{{ $ua->id }}" class="mb-1"></div>
                                    <button type="submit" id="btn-submit-{{ $ua->id }}" class="btn btn-success btn-sm btn-block font-weight-bold py-1">
                                        <i class="fas fa-save"></i> Enregistrer
                                    </button>
                                    <button type="button" id="btn-view-{{ $ua->id }}" class="btn btn-info btn-sm btn-block d-none font-weight-bold py-1" onclick="showQuestionsModal('{{ $ua->id }}')">
                                        <i class="fas fa-eye"></i> Voir les questions
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="alert alert-warning py-2 mb-2">
                Aucune Unité d'Apprentissage n'a été trouvée pour le projet rattaché à ce QCM.
            </div>
        @endif
    </div>
    
    <!-- Bouton de fin de page -->
    <div class="text-center mt-3 mb-4">
        <a href="{{ route('qcms.edit', ['qcm' => $affectation->qcm_id]) }}" class="btn btn-primary shadow rounded-pill px-4 py-1">
            <i class="fas fa-check-circle"></i> Terminer
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
            let editor;
            @foreach($uas as $ua)
                editor = monaco.editor.create(document.getElementById('json-editor-{{ $ua->id }}'), {
                    value: "[\n  {\n    \"question\": \"...\",\n    \"reponses\": [\"A\", \"B\", \"C\", \"D\"],\n    \"bonneReponse\": 1,\n    \"points\": 1,\n    \"unite_apprentissage_code\": \"{{ $ua->code }}\"\n  }\n]",
                    language: 'json',
                    theme: 'vs-light',
                    automaticLayout: true,
                    minimap: { enabled: false },
                    scrollBeyondLastLine: false,
                    formatOnPaste: true
                });
                
                // Forcer le formatage au collage
                editor.onDidPaste(function() {
                    setTimeout(function() {
                        try {
                            // On tente de reformater proprement avec le moteur JSON de JS
                            let val = editor.getValue();
                            // Nettoyage éventuel si l'IA a rajouté du markdown (ex: ```json ... ```)
                            val = val.replace(/^```json\s*/, '').replace(/\s*```$/, '');
                            const parsed = JSON.parse(val);
                            const formatted = JSON.stringify(parsed, null, 4);
                            editor.setValue(formatted);
                        } catch (e) {
                            // Fallback sur le formateur Monaco par défaut
                            editor.getAction('editor.action.formatDocument').run();
                        }
                    }, 50);
                });
                
                window.monacoEditors['{{ $ua->id }}'] = editor;
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

    window.openIA = function(provider, uaId) {
        const promptText = document.getElementById('prompt-content-' + uaId).value;
        
        let url = "";
        if (provider === 'chatgpt') {
            url = "https://chatgpt.com/?q=" + encodeURIComponent(promptText);
        } else if (provider === 'claude') {
            url = "https://claude.ai/new?q=" + encodeURIComponent(promptText);
        } else if (provider === 'gemini') {
            url = "https://gemini.google.com/";
        }
        
        // Copie synchrone pour garantir que le texte est copié AVANT de perdre le focus (nouvel onglet)
        const tempTextArea = document.createElement("textarea");
        tempTextArea.value = promptText;
        document.body.appendChild(tempTextArea);
        tempTextArea.select();
        try {
            document.execCommand('copy');
            if (provider === 'gemini' && typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'info', title: 'Copié !', text: 'Le prompt a été copié, vous n\'avez plus qu\'à le coller dans Gemini (Ctrl+V) !', timer: 4000 });
            }
        } catch (err) {
            console.error("Erreur de copie dans le presse-papier :", err);
        }
        document.body.removeChild(tempTextArea);
        
        if (url) {
            window.open(url, '_blank');
        }
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
        .then(async response => {
            const isJson = response.headers.get('content-type')?.includes('application/json');
            const data = isJson ? await response.json() : null;
            
            if (!response.ok) {
                const errorText = data ? JSON.stringify(data, null, 2) : await response.text();
                console.error(`[Serveur HTTP ${response.status}] Erreur :`, errorText);
                throw new Error(data?.message || data?.error || `Erreur HTTP ${response.status}`);
            }
            
            if (!isJson) {
                const text = await response.text();
                console.error("[Réponse Inattendue] Le serveur n'a pas renvoyé de JSON :", text);
                throw new Error("Le serveur n'a pas renvoyé de JSON");
            }
            
            return data;
        })
        .then(data => {
            if(data.success) {
                feedback.innerHTML = `<div class="alert alert-success py-2 mb-0"><i class="fas fa-check-circle"></i> ${data.message} (${data.count} ajoutées)</div>`;
                window.insertedQuestions[uaId] = data.questions; 
                btnView.classList.remove('d-none');
                btnSubmit.classList.add('d-none');
                
                // Mettre l'éditeur en lecture seule
                editor.updateOptions({ readOnly: true });
                
                // Rafraîchir les compteurs de questions
                fetchCountsAndUpdateUI();
            } else {
                console.error("[Erreur Métier] Le serveur a refusé le traitement :", data);
                feedback.innerHTML = `<div class="alert alert-danger py-2 mb-0"><i class="fas fa-ban"></i> ${data.error || 'Erreur lors de l\'insertion.'}</div>`;
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fas fa-save"></i> Enregistrer les Questions';
            }
        })
        .catch(err => {
            console.error("[Exception AJAX] Détail complet de l'erreur :", err);
            feedback.innerHTML = `<div class="alert alert-danger py-2 mb-0"><i class="fas fa-exclamation-triangle"></i> <strong>Erreur technique :</strong> ${err.message}. Consultez la console.</div>`;
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
        
    }

    // Écouter la fermeture de la modale CRUD pour rafraîchir les compteurs
    $("body").on("crudModalClosed", function(e, config) {
        if (config.entity_name === 'affectation' || config.entity_name === 'question') {
            fetchCountsAndUpdateUI();
        }
    });

    function fetchCountsAndUpdateUI() {
        const url = '{{ route("affectationQcmProjets.getQuestionsCount", ["id" => $affectation->id]) }}';
        fetch(url)
            .then(response => response.json())
            .then(data => {
                let countHeaderSpan;
                @foreach($uas as $ua)
                    countHeaderSpan = document.getElementById('q-count-header-{{ $ua->id }}');
                    
                    if (countHeaderSpan) {
                        countHeaderSpan.innerText = data['{{ $ua->id }}'] || 0;
                    }
                @endforeach
            })
            .catch(err => console.error("Erreur lors de la récupération des statistiques de questions :", err));
    }
    
    // Fonction de mise à jour dynamique des prompts selon les paramètres globaux
    window.updateAllPrompts = function() {
        const count = document.getElementById('param-count').value;
        const lang = document.getElementById('param-lang').value;
        const tone = document.getElementById('param-tone').value;
        const level = document.getElementById('param-level').value;
        const diff = document.getElementById('param-diff').value;
        
        // Sauvegarde dans localStorage
        localStorage.setItem('prompt_param_count', count);
        localStorage.setItem('prompt_param_lang', lang);
        localStorage.setItem('prompt_param_tone', tone);
        localStorage.setItem('prompt_param_level', level);
        localStorage.setItem('prompt_param_diff', diff);
        
        @foreach($uas as $ua)
        {
            let templateEl = document.getElementById('template-prompt-{{ $ua->id }}');
            if(templateEl) {
                let newPrompt = templateEl.textContent;
                newPrompt = newPrompt.replace('__COUNT__', count)
                                     .replace('__LANG__', lang)
                                     .replace('__TONE__', tone)
                                     .replace('__LEVEL__', level)
                                     .replace('__DIFF__', diff);
                document.getElementById('prompt-content-{{ $ua->id }}').value = newPrompt;
            }
        }
        @endforeach
    };

    // Initialisation au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        // Restaurer les valeurs sauvegardées
        if(localStorage.getItem('prompt_param_count')) document.getElementById('param-count').value = localStorage.getItem('prompt_param_count');
        if(localStorage.getItem('prompt_param_lang')) document.getElementById('param-lang').value = localStorage.getItem('prompt_param_lang');
        if(localStorage.getItem('prompt_param_tone')) document.getElementById('param-tone').value = localStorage.getItem('prompt_param_tone');
        if(localStorage.getItem('prompt_param_level')) document.getElementById('param-level').value = localStorage.getItem('prompt_param_level');
        if(localStorage.getItem('prompt_param_diff')) document.getElementById('param-diff').value = localStorage.getItem('prompt_param_diff');
        
        // Générer les prompts avec ces paramètres
        updateAllPrompts();
    });
</script>

<script>
    // Configuration d'un gestionnaire CRUD factice pour initialiser le micro-framework JS
    // Cela permettra à ShowIndexAction (intercepteur de .showIndex) de s'attacher et d'ouvrir les questions en modale
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        entity_name: 'affectation',
        contextKey: 'affectation.prompt',
        crudSelector: '#affectation-crud',
        tableSelector: '#affectation-data-container',
        filterFormSelector: '#affectation-filter',
        formSelector: '#affectation-form',
        isMany: false,
        indexUrl: '',
        createUrl: '',
        editUrl: '',
        showUrl: '',
        deleteUrl: ''
    });
</script>

@endpush

