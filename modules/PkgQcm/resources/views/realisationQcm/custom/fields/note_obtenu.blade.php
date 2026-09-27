@if(!is_null($entity->note_obtenu))
    @php
        $bareme = $entity->qcm ? $entity->qcm->questions()->sum('bareme') : 0;
        $note = number_format($entity->note_obtenu, 2, '.', '');
        $baremeText = number_format($bareme, 0, '.', '');
        $percentage = $bareme > 0 ? ($entity->note_obtenu / $bareme) * 100 : 0;
        $colorClass = $percentage >= 50 ? 'text-success' : 'text-danger';

        $isAuto = false;
        $realisationTaches = collect();
        
        $affectationQcmProjet = $entity->affectationQcmProjet;
        if ($affectationQcmProjet && $affectationQcmProjet->saise_automatique_note_qcm == true) {
            $isAuto = true;
            $realisationProjet = \Modules\PkgRealisationProjets\Models\RealisationProjet::where('affectation_projet_id', $affectationQcmProjet->affectation_projet_id)
                ->where('apprenant_id', $entity->apprenant_id)
                ->first();
                
            if ($realisationProjet) {
                $realisationTaches = $realisationProjet->realisationTaches()->with('tache.phaseEvaluation')->get();
            }
        }
    @endphp
    
    <div>
        <span class="font-weight-bold {{ $colorClass }}" style="font-size: 1.1em;">{{ $note }}</span> 
        <span class="text-muted">/ {{ $baremeText }}</span>
    </div>

    @if($isAuto && $realisationTaches->count() > 0)
        @php
            $protoTache = $realisationTaches->first(function($rt) {
                return $rt->tache && $rt->tache->phaseEvaluation && $rt->tache->phaseEvaluation->reference === 'N2';
            });
            $projetTache = $realisationTaches->first(function($rt) {
                return $rt->tache && $rt->tache->phaseEvaluation && $rt->tache->phaseEvaluation->reference === 'N3';
            });
        @endphp
        
        @if($protoTache || $projetTache)
        <div class="border-top pt-2 mt-2 text-left" style="font-size: 0.85em; min-width: 160px;">
            <div class="text-xs text-muted mb-1"><i class="fas fa-magic"></i> Auto-calcul actif</div>
            <div class="mb-2 p-1 bg-light rounded">
                @if($protoTache)
                <div class="d-flex justify-content-between" title="{{ $protoTache->tache->titre ?? 'Prototype' }}">
                    <span>Prototype:</span>
                    <span class="font-weight-bold {{ $protoTache->note !== null && $protoTache->note >= (($protoTache->tache->note ?? 20)/2) ? 'text-success' : ($protoTache->note === null ? 'text-muted' : 'text-danger') }}">
                        {{ $protoTache->note ?? '-' }} / {{ $protoTache->tache->note ?? 20 }}
                    </span>
                </div>
                @endif
                
                @if($projetTache)
                <div class="d-flex justify-content-between {{ $protoTache ? 'border-top pt-1 mt-1' : '' }}" title="{{ $projetTache->tache->titre ?? 'Projet' }}">
                    <span>Projet:</span>
                    <span class="font-weight-bold {{ $projetTache->note !== null && $projetTache->note >= (($projetTache->tache->note ?? 20)/2) ? 'text-success' : ($projetTache->note === null ? 'text-muted' : 'text-danger') }}">
                        {{ $projetTache->note ?? '-' }} / {{ $projetTache->tache->note ?? 20 }}
                    </span>
                </div>
                @endif
            </div>
        </div>
        @endif
    @endif
@else
    <span class="text-muted">—</span>
@endif
