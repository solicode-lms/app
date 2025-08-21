{{-- Affichage Date + Heure --}}
<span>
    {{ optional($entity->{$column})->isoFormat('LLL') ?? '—' }}
</span>