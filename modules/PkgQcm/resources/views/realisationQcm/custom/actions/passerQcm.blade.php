@php
    $isSoumis = !empty($entity->date_soumission) || ($entity->etatRealisationQcm && $entity->etatRealisationQcm->reference === 'SOUMIS');
@endphp

@if(!$isSoumis)
<a
    data-toggle="tooltip"
    title="Passer le QCM"
    href="{{ route('passerQcm.index', [
            'realisation_qcm_id' => $entity->id,
    ]) }}"
    class="btn btn-info btn-sm actionEntity"
    data-id="{{ $entity->id }}">
    <i class="fas fa-play-circle"></i>
</a>
@endif
