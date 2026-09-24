
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-primary"><i class="fas fa-robot"></i> Génération de Prompts QCM</h1>
                <p class="text-muted">Projet cible : {{ $affectation->affectationProjet->projet->titre ?? 'Inconnu' }}</p>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if($uas && $uas->count() > 0)
            <div class="row">
                @foreach($uas as $ua)
                <div class="col-md-6 col-lg-6">
                    <div class="card card-outline card-primary shadow-sm h-100">
                        <div class="card-header">
                            <h3 class="card-title text-truncate" style="max-width: 70%;" title="{{ $ua->nom }}">
                                <strong>UA :</strong> {{ $ua->nom ?? 'Inconnue' }}
                            </h3>
                            <div class="card-tools">
                                <button class="btn btn-primary btn-sm rounded-pill shadow-sm" onclick="copyPrompt('prompt-{{ $ua->id }}')">
                                    <i class="fas fa-copy"></i> Copier
                                </button>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="text-sm text-muted mb-3">
                                <i class="fas fa-info-circle"></i> Ce prompt inclut automatiquement les tutoriels liés à cette unité. Collez-le dans votre IA favorite.
                            </p>
                            <div class="position-relative flex-grow-1">
<textarea id="prompt-{{ $ua->id }}" class="form-control bg-dark text-light p-3" style="font-family: monospace; font-size: 0.85rem; height: 350px; resize: none;" readonly>
Génère un QCM au format JSON strict.
Contexte de l'évaluation : "{{ $ua->nom }}"

Sujets couverts par les tutoriels :
@forelse($ua->chapitres as $chapitre)
- {{ $chapitre->nom }} {{ $chapitre->description ? '('.$chapitre->description.')' : '' }}
@empty
- Notions fondamentales de cette unité.
@endforelse

Format attendu :
[{"question":"...","reponses":["A","B","C","D"],"bonneReponse":1,"points":1}]

Règles obligatoires :
1. Renvoie UNIQUEMENT un tableau JSON, aucune phrase d'introduction ni de conclusion.
2. "reponses" : 4 propositions maximum.
3. "bonneReponse" : Chiffre de 1 à 4 indiquant la position de la bonne réponse.
4. "points" : Entier (1 par défaut).
5. Varie la position de la bonne réponse.

Génère 40 questions sur ces sujets.
</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning shadow-sm border-left-warning">
                <i class="fas fa-exclamation-triangle"></i> Aucune Unité d'Apprentissage (UA) n'a été trouvée pour le projet rattaché à ce QCM.
            </div>
        @endif
    </div>
</section>

<script>
    function copyPrompt(elementId) {
        const textarea = document.getElementById(elementId);
        textarea.select();
        textarea.setSelectionRange(0, 99999); // Mobile
        
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
        }).catch(err => {
            console.error('Erreur lors de la copie : ', err);
        });
    }
</script>
