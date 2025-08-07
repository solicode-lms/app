{{  $entity->tache }}
<br>
<small>{{ $entity->projet_title }}</small>
<br>

@if($entity->is_live_coding)
    <span class="badge bg-indigo" title="Cette tâche a été validée en live coding" data-toggle="tooltip">
        Validé avec live coding
    </span>
@endif