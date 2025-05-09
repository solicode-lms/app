{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('feature-show')
<div id="feature-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::feature.name')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFeature->name) && $itemFeature->name !== '')
          {{ $itemFeature->name }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::feature.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemFeature->description) && $itemFeature->description !== '')
    {!! $itemFeature->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::featureDomain.singular')) }}</small>
                              
      @if($itemFeature->featureDomain)
        {{ $itemFeature->featureDomain }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::permission.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemFeature->permissions->isNotEmpty())
          <div>
            @foreach($itemFeature->permissions as $permission)
              <span class="badge badge-info mr-1">
                {{ $permission }}
              </span>
            @endforeach
          </div>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('features.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-feature')
          <x-action-button :entity="$itemFeature" actionName="edit">
          @can('update', $itemFeature)
              <a href="{{ route('features.edit', ['feature' => $itemFeature->id]) }}" data-id="{{$itemFeature->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("Core::feature.singular") }} : {{ $itemFeature }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show