{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationUa-show')
<div id="etatRealisationUa-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationUa.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEtatRealisationUa->nom) && $itemEtatRealisationUa->nom !== '')
          {{ $itemEtatRealisationUa->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationUa.code')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEtatRealisationUa->code) && $itemEtatRealisationUa->code !== '')
          {{ $itemEtatRealisationUa->code }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationUa.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEtatRealisationUa->description) && $itemEtatRealisationUa->description !== '')
    {!! $itemEtatRealisationUa->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationUa.is_editable_only_by_formateur')) }}</small>
                              
      @if($itemEtatRealisationUa->is_editable_only_by_formateur)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemEtatRealisationUa->sysColor)
        @php
          $related = $itemEtatRealisationUa->sysColor;
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
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationUa.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgApprentissage::realisationUa._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationUa.show_' . $itemEtatRealisationUa->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('etatRealisationUas.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-etatRealisationUa')
          <x-action-button :entity="$itemEtatRealisationUa" actionName="edit">
          @can('update', $itemEtatRealisationUa)
              <a href="{{ route('etatRealisationUas.edit', ['etatRealisationUa' => $itemEtatRealisationUa->id]) }}" data-id="{{$itemEtatRealisationUa->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::etatRealisationUa.singular") }} : {{ $itemEtatRealisationUa }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show