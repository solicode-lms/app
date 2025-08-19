<div class="realisation-card realisation-module">
    <div class="realisation-header">
        <h3 class="realisation-titre">
            <a href="#">{{  $entity->module }}</a>
        </h3>
        <div class="realisation-soustitre"></div>
    </div>
    <div class="realisation-infos realisation-lecture">
        {!! $entity->lecture_pedagogique !!}
    </div>
    <div class="realisation-infos">
        <span class="realisation-badge realisation-etat">
            Rythme :   {{ $entity->taux_rythme_cache }} %
        </span>
    </div>
    <div class="realisation-footer">
        <i class="fas fa-user"></i>
        <strong>Apprenant :</strong> {{ $entity->apprenant }}
    </div>
</div>