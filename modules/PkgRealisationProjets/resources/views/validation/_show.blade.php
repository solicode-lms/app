{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('validation-show')
<div id="validation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::transfertCompetence.singular')) }}</small>
                              
      @if($itemValidation->transfertCompetence)
        {{ $itemValidation->transfertCompetence }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::validation.note')) }}</small>
                              
      <span>
        @if(! is_null($itemValidation->note))
          {{ number_format($itemValidation->note, 2, '.', '') }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::validation.message')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemValidation->message) && $itemValidation->message !== '')
    {!! $itemValidation->message !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::validation.is_valide')) }}</small>
                              
      @if($itemValidation->is_valide)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}</small>
                              
      @if($itemValidation->realisationProjet)
        {{ $itemValidation->realisationProjet }}
      @else
        —
      @endif

          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('validations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-validation')
          <x-action-button :entity="$itemValidation" actionName="edit">
          @can('update', $itemValidation)
              <a href="{{ route('validations.edit', ['validation' => $itemValidation->id]) }}" data-id="{{$itemValidation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationProjets::validation.singular") }} : {{ $itemValidation }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show