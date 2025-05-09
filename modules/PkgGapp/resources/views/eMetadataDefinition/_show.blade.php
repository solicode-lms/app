{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eMetadataDefinition-show')
<div id="eMetadataDefinition-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadataDefinition.name')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEMetadataDefinition->name) && $itemEMetadataDefinition->name !== '')
          {{ $itemEMetadataDefinition->name }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadataDefinition.groupe')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEMetadataDefinition->groupe) && $itemEMetadataDefinition->groupe !== '')
          {{ $itemEMetadataDefinition->groupe }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadataDefinition.type')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEMetadataDefinition->type) && $itemEMetadataDefinition->type !== '')
          {{ $itemEMetadataDefinition->type }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadataDefinition.scope')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEMetadataDefinition->scope) && $itemEMetadataDefinition->scope !== '')
          {{ $itemEMetadataDefinition->scope }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadataDefinition.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEMetadataDefinition->description) && $itemEMetadataDefinition->description !== '')
    {!! $itemEMetadataDefinition->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadataDefinition.default_value')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEMetadataDefinition->default_value) && $itemEMetadataDefinition->default_value !== '')
    {!! $itemEMetadataDefinition->default_value !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('eMetadataDefinitions.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-eMetadataDefinition')
          <x-action-button :entity="$itemEMetadataDefinition" actionName="edit">
          @can('update', $itemEMetadataDefinition)
              <a href="{{ route('eMetadataDefinitions.edit', ['eMetadataDefinition' => $itemEMetadataDefinition->id]) }}" data-id="{{$itemEMetadataDefinition->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgGapp::eMetadataDefinition.singular") }} : {{ $itemEMetadataDefinition }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show