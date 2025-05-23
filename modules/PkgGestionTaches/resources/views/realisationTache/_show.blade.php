{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationTache-show')
<div id="realisationTache-crud-show">
        <div class="card-body">
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('Informations générales') }}
            </h6>
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
  


            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('Dates de réalisation') }}
            </h6>
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::realisationTache.dateDebut')) }}</small>
                            
    <span>
      @if ($itemRealisationTache->dateDebut)
        {{ \Carbon\Carbon::parse($itemRealisationTache->dateDebut)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::realisationTache.dateFin')) }}</small>
                            
    <span>
      @if ($itemRealisationTache->dateFin)
        {{ \Carbon\Carbon::parse($itemRealisationTache->dateFin)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  


            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('État') }}
            </h6>
            <div class="row no-gutters mb-4">
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
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::realisationTache.note')) }}</small>
                              
      <span>
        @if(! is_null($itemRealisationTache->note))
          {{ number_format($itemRealisationTache->note, 2, '.', '') }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  


            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('Remarques') }}
            </h6>
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_formateur')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemRealisationTache->remarques_formateur) && $itemRealisationTache->remarques_formateur !== '')
    {!! $itemRealisationTache->remarques_formateur !!}
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
    {!! $itemRealisationTache->remarques_apprenant !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::realisationTache.remarque_evaluateur')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemRealisationTache->remarque_evaluateur) && $itemRealisationTache->remarque_evaluateur !== '')
    {!! $itemRealisationTache->remarque_evaluateur !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgValidationProjets::evaluationRealisationTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgValidationProjets::evaluationRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.show_' . $itemRealisationTache->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgGestionTaches::historiqueRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.show_' . $itemRealisationTache->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationTache')
          <x-action-button :entity="$itemRealisationTache" actionName="edit">
          @can('update', $itemRealisationTache)
              <a href="{{ route('realisationTaches.edit', ['realisationTache' => $itemRealisationTache->id]) }}" data-id="{{$itemRealisationTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgGestionTaches::realisationTache.singular") }} : {{ $itemRealisationTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show