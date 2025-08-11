{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationModule-show')
<div id="etatRealisationModule-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationModule.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemEtatRealisationModule->ordre))
                      {{ $itemEtatRealisationModule->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationModule.code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEtatRealisationModule->code) && $itemEtatRealisationModule->code !== '')
        {{ $itemEtatRealisationModule->code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationModule.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEtatRealisationModule->nom) && $itemEtatRealisationModule->nom !== '')
        {{ $itemEtatRealisationModule->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationModule.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemEtatRealisationModule->description) && $itemEtatRealisationModule->description !== '')
                    {!! $itemEtatRealisationModule->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                  @if($itemEtatRealisationModule->sysColor)
                  @php
                    $related = $itemEtatRealisationModule->sysColor;
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
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationModule.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationModule.show_' . $itemEtatRealisationModule->id])
                  </div>
                  </div>
            </div>

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('etatRealisationModules.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-etatRealisationModule')
          <x-action-button :entity="$itemEtatRealisationModule" actionName="edit">
          @can('update', $itemEtatRealisationModule)
              <a href="{{ route('etatRealisationModules.edit', ['etatRealisationModule' => $itemEtatRealisationModule->id]) }}" data-id="{{$itemEtatRealisationModule->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::etatRealisationModule.singular") }} : {{ $itemEtatRealisationModule }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show