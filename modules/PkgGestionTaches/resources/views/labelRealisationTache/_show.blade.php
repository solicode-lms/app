{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('labelRealisationTache-show')
<div id="labelRealisationTache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::labelRealisationTache.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemLabelRealisationTache->nom) && $itemLabelRealisationTache->nom !== '')
          {{ $itemLabelRealisationTache->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::labelRealisationTache.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemLabelRealisationTache->description) && $itemLabelRealisationTache->description !== '')
    {!! $itemLabelRealisationTache->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>
                              
      @if($itemLabelRealisationTache->formateur)
        {{ $itemLabelRealisationTache->formateur }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemLabelRealisationTache->sysColor)
        @php
          $related = $itemLabelRealisationTache->sysColor;
        @endphp
        <span 
          class="badge" 
          style="background-color: {{ $related->hex }}; color: #fff;"
        >
          {{ $related->sysColor }}
        </span>
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('labelRealisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-labelRealisationTache')
          <x-action-button :entity="$itemLabelRealisationTache" actionName="edit">
          @can('update', $itemLabelRealisationTache)
              <a href="{{ route('labelRealisationTaches.edit', ['labelRealisationTache' => $itemLabelRealisationTache->id]) }}" data-id="{{$itemLabelRealisationTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgGestionTaches::labelRealisationTache.singular") }} : {{ $itemLabelRealisationTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show