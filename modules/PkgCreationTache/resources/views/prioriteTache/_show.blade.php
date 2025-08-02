{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('prioriteTache-show')
<div id="prioriteTache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::prioriteTache.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemPrioriteTache->ordre))
          {{ $itemPrioriteTache->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::prioriteTache.nom')) }}</small>
                              @if(! is_null($itemPrioriteTache->nom) && $itemPrioriteTache->nom !== '')
        {{ $itemPrioriteTache->nom }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::prioriteTache.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemPrioriteTache->description) && $itemPrioriteTache->description !== '')
    {!! $itemPrioriteTache->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>
                              
      @if($itemPrioriteTache->formateur)
        {{ $itemPrioriteTache->formateur }}
      @else
        —
      @endif

          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('prioriteTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-prioriteTache')
          <x-action-button :entity="$itemPrioriteTache" actionName="edit">
          @can('update', $itemPrioriteTache)
              <a href="{{ route('prioriteTaches.edit', ['prioriteTache' => $itemPrioriteTache->id]) }}" data-id="{{$itemPrioriteTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCreationTache::prioriteTache.singular") }} : {{ $itemPrioriteTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show