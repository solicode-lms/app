@extends('layouts.admin')

@section('title', 'Générer un prompt IA')

@section('content')
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
                        <pre id="prompt-content-{{ $ua->id }}" class="bg-dark p-3 rounded text-white" style="white-space: pre-wrap; font-family: monospace;">
                            
# 🧠 **Prompt — Génération de QCM JSON**

Génère un **QCM** au **format JSON** suivant, **sans aucun texte additionnel ni explication**.

L'objectif est d'évaluer les compétences de l'unité suivante :
- **Unité d'Apprentissage** : {{ $ua->nom ?? 'Inconnue' }}
- **Description** : {{ $ua->description ?? 'Non renseignée' }}

Chaque objet représente **une question**, avec ses **réponses possibles**, le **numéro de la bonne réponse**, et la **valeur en points**.

---

## 🧩 **Structure à respecter**

```json
[
  {
    "question": "Texte clair et concis de la question",
    "reponses": [
      "Réponse A",
      "Réponse B",
      "Réponse C",
      "Réponse D"
    ],
    "bonneReponse": 1,
    "points": 1
  }
]
```

---

## 📘 **Règles obligatoires**

1. Le fichier JSON doit contenir **un tableau d’objets**, sans clé racine supplémentaire.
2. Chaque objet doit contenir exactement :
   * `"question"` → texte clair et précis de la question sur l'unité mentionnée.
   * `"reponses"` → tableau de **maximum 4 propositions** (ordre aléatoire).
   * `"bonneReponse"` → **numéro de la bonne réponse** (1 à 4).
   * `"points"` → valeur entière (souvent `1`).
3. Aucune explication, commentaire ou texte hors JSON.
4. Les valeurs doivent être **en français clair**, sans balises HTML, emoji ni texte décoratif.
5. **La bonne réponse ne doit pas toujours être la première.**

---

## 🧾 **Exemple attendu**

```json
[
  {
    "question": "Comment déclare-t-on une classe en Kotlin ?",
    "reponses": [
      "class NomClasse",
      "function NomClasse",
      "object NomClasse",
      "struct NomClasse"
    ],
    "bonneReponse": 1,
    "points": 1
  }
]
```

---

## 🧮 **Instruction finale**

➡️ Génère **40 questions** conformes à ces règles sur l'Unité d'Apprentissage demandée.
Aucune phrase ou texte hors du bloc JSON.

</pre>



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
    function copyPrompt(elementId) {
        const text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(() => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Copié !',
                    text: 'Le prompt a été copié dans le presse-papier.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert('Prompt copié avec succès !');
            }
        }).catch(err => {
            console.error('Erreur lors de la copie : ', err);
        });
    }
</script>
@endpush
