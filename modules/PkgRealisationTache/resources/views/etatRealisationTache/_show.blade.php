{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationTache-show')
<div id="etatRealisationTache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::etatRealisationTache.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemEtatRealisationTache->ordre))
                      {{ $itemEtatRealisationTache->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::etatRealisationTache.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEtatRealisationTache->nom) && $itemEtatRealisationTache->nom !== '')
        {{ $itemEtatRealisationTache->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::workflowTache.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemEtatRealisationTache->workflowTache)
                  {{ $itemEtatRealisationTache->workflowTache }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                  @if($itemEtatRealisationTache->sysColor)
                  @php
                    $related = $itemEtatRealisationTache->sysColor;
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
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::etatRealisationTache.is_editable_only_by_formateur')) }}</small>
                  @if($itemEtatRealisationTache->is_editable_only_by_formateur)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemEtatRealisationTache->formateur)
                  {{ $itemEtatRealisationTache->formateur }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::etatRealisationTache.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemEtatRealisationTache->description) && $itemEtatRealisationTache->description !== '')
                    {!! $itemEtatRealisationTache->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('etatRealisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-etatRealisationTache')
          <x-action-button :entity="$itemEtatRealisationTache" actionName="edit">
          @can('update', $itemEtatRealisationTache)
              <a href="{{ route('etatRealisationTaches.edit', ['etatRealisationTache' => $itemEtatRealisationTache->id]) }}" data-id="{{$itemEtatRealisationTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationTache::etatRealisationTache.singular") }} : {{ $itemEtatRealisationTache }}';
    window.showUIId = 'etatRealisationTache-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show