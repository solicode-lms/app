{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationProjet-show')
<div id="realisationProjet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.singular')) }}</small>
                              
      @if($itemRealisationProjet->affectationProjet)
        {{ $itemRealisationProjet->affectationProjet }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.singular')) }}</small>
                              
      @if($itemRealisationProjet->apprenant)
        {{ $itemRealisationProjet->apprenant }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.date_debut')) }}</small>
                            
    <span>
      @if ($itemRealisationProjet->date_debut)
        {{ \Carbon\Carbon::parse($itemRealisationProjet->date_debut)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.date_fin')) }}</small>
                            
    <span>
      @if ($itemRealisationProjet->date_fin)
        {{ \Carbon\Carbon::parse($itemRealisationProjet->date_fin)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.singular')) }}</small>
                              
      @if($itemRealisationProjet->etatsRealisationProjet)
        {{ $itemRealisationProjet->etatsRealisationProjet }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.avancement_projet')) }}</small>
                              
      <span>
        @if(! is_null($itemRealisationProjet->avancement_projet))
          {{ number_format($itemRealisationProjet->avancement_projet, 2, '.', '') }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.rapport')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemRealisationProjet->rapport) && $itemRealisationProjet->rapport !== '')
    {!! $itemRealisationProjet->rapport !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationTache::realisationTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationTache::realisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationProjet.show_' . $itemRealisationProjet->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationProjets::livrablesRealisation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationProjet.show_' . $itemRealisationProjet->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgValidationProjets::evaluationRealisationProjet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgValidationProjets::evaluationRealisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationProjet.show_' . $itemRealisationProjet->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationProjet')
          <x-action-button :entity="$itemRealisationProjet" actionName="edit">
          @can('update', $itemRealisationProjet)
              <a href="{{ route('realisationProjets.edit', ['realisationProjet' => $itemRealisationProjet->id]) }}" data-id="{{$itemRealisationProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationProjets::realisationProjet.singular") }} : {{ $itemRealisationProjet }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show