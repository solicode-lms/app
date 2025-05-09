{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('projet-show')
<div id="projet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.titre')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemProjet->titre) && $itemProjet->titre !== '')
          {{ $itemProjet->titre }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.travail_a_faire')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemProjet->travail_a_faire) && $itemProjet->travail_a_faire !== '')
    {!! $itemProjet->travail_a_faire !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.critere_de_travail')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemProjet->critere_de_travail) && $itemProjet->critere_de_travail !== '')
    {!! $itemProjet->critere_de_travail !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.nombre_jour')) }}</small>
                              
      <span>
        @if(! is_null($itemProjet->nombre_jour))
          {{ $itemProjet->nombre_jour }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::filiere.singular')) }}</small>
                              
      @if($itemProjet->filiere)
        {{ $itemProjet->filiere }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>
                              
      @if($itemProjet->formateur)
        {{ $itemProjet->formateur }}
      @else
        —
      @endif

          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('projets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-projet')
          <x-action-button :entity="$itemProjet" actionName="edit">
          @can('update', $itemProjet)
              <a href="{{ route('projets.edit', ['projet' => $itemProjet->id]) }}" data-id="{{$itemProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCreationProjet::projet.singular") }} : {{ $itemProjet }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show