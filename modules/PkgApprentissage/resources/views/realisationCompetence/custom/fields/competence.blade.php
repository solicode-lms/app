<div class="realisation-card realisation-competence">
    <div class="realisation-header">
        <h3 class="realisation-titre">
             {{  $entity->competence }} 
        </h3>
        <div class="realisation-soustitre">{{  $entity->competence->module }} </div>
    </div>
    <div class="realisation-infos realisation-lecture">
        {!! $entity->lecture_pedagogique !!}
    </div>
    <div class="realisation-footer">
        <i class="fas fa-user"></i>
        <strong>Apprenant :</strong> {{ $entity->apprenant }}
    </div>
</div>