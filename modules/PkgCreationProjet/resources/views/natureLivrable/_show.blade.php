{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('natureLivrable-show')
<div id="natureLivrable-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::natureLivrable.nom')) }}</small>
                              @if(! is_null($itemNatureLivrable->nom) && $itemNatureLivrable->nom !== '')
        {{ $itemNatureLivrable->nom }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::natureLivrable.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemNatureLivrable->description) && $itemNatureLivrable->description !== '')
    {!! $itemNatureLivrable->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgSessions::livrableSession.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgSessions::livrableSession._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'natureLivrable.show_' . $itemNatureLivrable->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgCreationProjet::livrable.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgCreationProjet::livrable._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'natureLivrable.show_' . $itemNatureLivrable->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('natureLivrables.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-natureLivrable')
          <x-action-button :entity="$itemNatureLivrable" actionName="edit">
          @can('update', $itemNatureLivrable)
              <a href="{{ route('natureLivrables.edit', ['natureLivrable' => $itemNatureLivrable->id]) }}" data-id="{{$itemNatureLivrable->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCreationProjet::natureLivrable.singular") }} : {{ $itemNatureLivrable }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show