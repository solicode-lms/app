<div class="realisation-card realisation-microCompetence">
    <div class="realisation-header">
        <h3 class="realisation-titre">
            @if($entity->lien_livrable)
            <a href="{{ $entity->lien_livrable }}" target="_blank">{{  $entity->microCompetence }}</a>
            @else
            {{  $entity->microCompetence }}
            @endif
        </h3>
        <div class="realisation-soustitre">{{  $entity->microCompetence->competence }}</div>
        <div class="realisation-soustitre">{{  $entity->microCompetence->competence->module }}</div>
    </div>
    <div class="realisation-infos realisation-lecture">
        {!! $entity->lecture_pedagogique !!}
    </div>
    <div class="realisation-footer">
        <i class="fas fa-user"></i>
        <strong>Apprenant :</strong> {{ $entity->apprenant }}
    </div>
</div>