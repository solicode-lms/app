{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('equipeProjet-show')
<div id="equipeProjet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemEquipeProjet->projet)
                  {{ $itemEquipeProjet->projet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::equipeProjet.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEquipeProjet->nom) && $itemEquipeProjet->nom !== '')
        {{ $itemEquipeProjet->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemEquipeProjet->apprenants->isNotEmpty())
                  <div>
                    @foreach($itemEquipeProjet->apprenants as $apprenant)
                      <span class="badge badge-info mr-1">
                        {{ $apprenant }}
                      </span>
                    @endforeach
                  </div>
                  @else
                  <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('equipeProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-equipeProjet')
          <x-action-button :entity="$itemEquipeProjet" actionName="edit">
          @can('update', $itemEquipeProjet)
              <a href="{{ route('equipeProjets.edit', ['equipeProjet' => $itemEquipeProjet->id]) }}" data-id="{{$itemEquipeProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCreationProjet::equipeProjet.singular") }} : {{ $itemEquipeProjet }}';
    window.showUIId = 'equipeProjet-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show