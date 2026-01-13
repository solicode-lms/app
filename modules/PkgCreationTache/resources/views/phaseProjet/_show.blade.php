{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('phaseProjet-show')
<div id="phaseProjet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::phaseProjet.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemPhaseProjet->ordre))
                      {{ $itemPhaseProjet->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::phaseProjet.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemPhaseProjet->nom) && $itemPhaseProjet->nom !== '')
        {{ $itemPhaseProjet->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::phaseProjet.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemPhaseProjet->description) && $itemPhaseProjet->description !== '')
                    {!! $itemPhaseProjet->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::phaseProjet.code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemPhaseProjet->code) && $itemPhaseProjet->code !== '')
        {{ $itemPhaseProjet->code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-tache') && $itemPhaseProjet->taches->isNotEmpty())  
                  || auth()->user()?->can('create-tache')
                  || (auth()->user()?->can('edit-tache')  && $itemPhaseProjet->taches->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgCreationTache::tache.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgCreationTache::tache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'phaseProjet.show_' . $itemPhaseProjet->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('phaseProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-phaseProjet')
          <x-action-button :entity="$itemPhaseProjet" actionName="edit">
          @can('update', $itemPhaseProjet)
              <a href="{{ route('phaseProjets.edit', ['phaseProjet' => $itemPhaseProjet->id]) }}" data-id="{{$itemPhaseProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCreationTache::phaseProjet.singular") }} : {{ $itemPhaseProjet }}';
    window.showUIId = 'phaseProjet-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show