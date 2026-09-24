<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Générer un prompt IA pour : {{ $affectation->qcm->titre ?? 'QCM' }}</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if($uas && $uas->count() > 0)
            @foreach($uas as $ua)
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Prompt pour l'Unité : <strong>{{ $ua->nom ?? 'Inconnue' }}</strong></h3>
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm" onclick="copyPrompt('prompt-content-{{ $ua->id }}')">
                            <i class="fas fa-copy"></i> Copier le prompt
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <p>Copiez ce texte et collez-le dans ChatGPT, Claude ou un autre assistant IA pour générer votre QCM au format JSON.</p>
                    <div class="position-relative">
<textarea id="prompt-content-{{ $ua->id }}" class="form-control bg-dark text-white p-3" style="font-family: monospace; height: 350px;" readonly>
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

Génère 40 questions.
</textarea>
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
