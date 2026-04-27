{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('labelProjet-show')
<div id="labelProjet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::labelProjet.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemLabelProjet->nom) && $itemLabelProjet->nom !== '')
        {{ $itemLabelProjet->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::labelProjet.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemLabelProjet->description) && $itemLabelProjet->description !== '')
                    {!! $itemLabelProjet->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemLabelProjet->projet)
                  {{ $itemLabelProjet->projet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                  @if($itemLabelProjet->sysColor)
                  @php
                    $related = $itemLabelProjet->sysColor;
                  @endphp
                  <span 
                    class="badge" 
                    style="background-color: {{ $related->hex }}; color: #fff;"
                  >
                    {{ $related }}
                  </span>
                  @else
                  <span class="text-muted">—</span>
                  @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemLabelProjet->realisationTaches->isNotEmpty())
                  <div>
                    @foreach($itemLabelProjet->realisationTaches as $realisationTache)
                      <span class="badge badge-info mr-1">
                        {{ $realisationTache }}
                      </span>
                    @endforeach
                  </div>
                  @else
                  <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::tache.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemLabelProjet->taches->isNotEmpty())
                  <div>
                    @foreach($itemLabelProjet->taches as $tache)
                      <span class="badge badge-info mr-1">
                        {{ $tache }}
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
          <a href="{{ route('labelProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-labelProjet')
          <x-action-button :entity="$itemLabelProjet" actionName="edit">
          @can('update', $itemLabelProjet)
              <a href="{{ route('labelProjets.edit', ['labelProjet' => $itemLabelProjet->id]) }}" data-id="{{$itemLabelProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCreationProjet::labelProjet.singular") }} : {{ $itemLabelProjet }}';
    window.showUIId = 'labelProjet-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show