{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('alignementUa-show')
<div id="alignementUa-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::alignementUa.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemAlignementUa->ordre))
          {{ $itemAlignementUa->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::uniteApprentissage.singular')) }}</small>
                              
      @if($itemAlignementUa->uniteApprentissage)
        {{ $itemAlignementUa->uniteApprentissage }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.singular')) }}</small>
                              
      @if($itemAlignementUa->sessionFormation)
        {{ $itemAlignementUa->sessionFormation }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::alignementUa.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemAlignementUa->description) && $itemAlignementUa->description !== '')
    {!! $itemAlignementUa->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('alignementUas.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-alignementUa')
          <x-action-button :entity="$itemAlignementUa" actionName="edit">
          @can('update', $itemAlignementUa)
              <a href="{{ route('alignementUas.edit', ['alignementUa' => $itemAlignementUa->id]) }}" data-id="{{$itemAlignementUa->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgSessions::alignementUa.singular") }} : {{ $itemAlignementUa }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show