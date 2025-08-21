{{-- Affichage Date (format court) --}}
<span>
    {{ optional($entity->{$column})->isoFormat('LL') ?? '—' }}
</span>