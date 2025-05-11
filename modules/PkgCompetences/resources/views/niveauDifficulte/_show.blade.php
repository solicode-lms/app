{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauDifficulte-show')
<div id="niveauDifficulte-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::niveauDifficulte.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemNiveauDifficulte->nom) && $itemNiveauDifficulte->nom !== '')
          {{ $itemNiveauDifficulte->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::niveauDifficulte.noteMin')) }}</small>
                              
      <span>
        @if(! is_null($itemNiveauDifficulte->noteMin))
          {{ number_format($itemNiveauDifficulte->noteMin, 2, '.', '') }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::niveauDifficulte.noteMax')) }}</small>
                              
      <span>
        @if(! is_null($itemNiveauDifficulte->noteMax))
          {{ number_format($itemNiveauDifficulte->noteMax, 2, '.', '') }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>
                              
      @if($itemNiveauDifficulte->formateur)
        {{ $itemNiveauDifficulte->formateur }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::niveauDifficulte.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemNiveauDifficulte->description) && $itemNiveauDifficulte->description !== '')
    {!! $itemNiveauDifficulte->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('niveauDifficultes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-niveauDifficulte')
          <x-action-button :entity="$itemNiveauDifficulte" actionName="edit">
          @can('update', $itemNiveauDifficulte)
              <a href="{{ route('niveauDifficultes.edit', ['niveauDifficulte' => $itemNiveauDifficulte->id]) }}" data-id="{{$itemNiveauDifficulte->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCompetences::niveauDifficulte.singular") }} : {{ $itemNiveauDifficulte }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show