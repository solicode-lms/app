{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationTache-show')
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::tache.singular')) }}</small>
                              
      @if($itemRealisationTache->tache)
        {{ $itemRealisationTache->tache }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}</small>
                              
      @if($itemRealisationTache->realisationProjet)
        {{ $itemRealisationTache->realisationProjet }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::realisationTache.dateDebut')) }}</small>
                              
      <span>
        {{ optional($itemRealisationTache->dateDebut)->isoFormat('LLL') ?? '—' }}
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::realisationTache.dateFin')) }}</small>
                              
      <span>
        {{ optional($itemRealisationTache->dateFin)->isoFormat('LLL') ?? '—' }}
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.singular')) }}</small>
                              
      @if($itemRealisationTache->etatRealisationTache)
        {{ $itemRealisationTache->etatRealisationTache }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12">
        <label for="HistoriqueRealisationTache">
                  {{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.plural')) }}
                  
          </label>
        @include('PkgGestionTaches::historiqueRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . $itemRealisationTache->id])
      </div>



      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_formateur')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemRealisationTache->remarques_formateur) && $itemRealisationTache->remarques_formateur !== '')
    {!! nl2br(e($itemRealisationTache->remarques_formateur)) !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_apprenant')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemRealisationTache->remarques_apprenant) && $itemRealisationTache->remarques_apprenant !== '')
    {!! nl2br(e($itemRealisationTache->remarques_apprenant)) !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
         <div class="card-footer">
            <div class="btn-group btn-group-sm">
                <a href="{{ route('realisationTaches.index') }}"  class="btn btn-light">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <a href="{{ route('realisationTaches.edit', $itemRealisationTache) }}" class="btn btn-warning text-white">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>
<script>
    window.modalTitle   = '{{ __("PkgGestionTaches::realisationTache.singular") }} : {{ $itemRealisationTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show