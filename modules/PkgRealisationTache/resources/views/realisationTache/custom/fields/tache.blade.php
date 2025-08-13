

@if($entity->realisationChapitres && $entity->realisationChapitres->isNotEmpty())
    @php
        $realisationChapitre = $entity->realisationChapitres->first() ?? null;
    @endphp

    @if($realisationChapitre)
        <a href="{{  $realisationChapitre->chapitre->lien }}" target="_blank">
           {{ $entity->tache }}
        </a>
    @else
        {{ $entity->tache }}
    @endif
@else
    {{ $entity->tache }}
@endif
<br>
<small>{{ $entity->projet_title }}</small>
<br>

@if($entity->is_live_coding)
    <span class="badge bg-indigo" title="Cette tâche a été validée en live coding" data-toggle="tooltip">
        Validé avec live coding
    </span>
@endif