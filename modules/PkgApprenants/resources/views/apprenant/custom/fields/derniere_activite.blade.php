@php
use Carbon\Carbon;

$derniereActivite = $entity->derniere_activite ? Carbon::parse($entity->derniere_activite) : null;
$dureeDepuisDerniereActivite = $derniereActivite ? $derniereActivite->diffInMinutes(now()) / 60 : null;
$tooltipDate = $derniereActivite ? $derniereActivite->format('d/m/Y H:i') : 'Non définie';
@endphp

<div class="realisation-etat" style="--etat-color:#6c757d">

    {{-- 🕒 Dernière activité --}}
    @if($derniereActivite)
        <span class="etat-meta" title="Dernière activité" data-toggle="tooltip">
            <i class="fas fa-history"></i>
            <div>
                <x-duree-affichage :heures="$dureeDepuisDerniereActivite" /><br>
                <small class="text-secondary">Dernière activité</small>
            </div>
        </span>
    @else
        <span class="etat-meta text-muted">
            <i class="fas fa-history"></i>
            Aucune activité enregistrée
        </span>
    @endif

    {{-- ✅ Dernière tâche finie --}}
    @if(!is_null($entity->duree_sans_terminer_tache))
        <span class="etat-meta" title="Depuis la dernière tâche terminée" data-toggle="tooltip">
            <i class="fas fa-check text-success"></i>
            <div>
               
                <x-duree-affichage :heures="$entity->duree_sans_terminer_tache" /><br>
                <small class="text-secondary">Dernière tâche terminée</small>
            </div>
        </span>
    @else
        <span class="etat-meta text-muted">
            <i class="fas fa-check text-muted"></i>
            Aucune tâche terminée
        </span>
    @endif

</div>
