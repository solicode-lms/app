{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('filiere-show')
<div id="filiere-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::filiere.code')) }}</small>
                              @if(! is_null($itemFiliere->code) && $itemFiliere->code !== '')
        {{ $itemFiliere->code }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::filiere.nom')) }}</small>
                              @if(! is_null($itemFiliere->nom) && $itemFiliere->nom !== '')
        {{ $itemFiliere->nom }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::filiere.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemFiliere->description) && $itemFiliere->description !== '')
    {!! $itemFiliere->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgApprenants::groupe.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgApprenants::groupe._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'filiere.show_' . $itemFiliere->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgFormation::module.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgFormation::module._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'filiere.show_' . $itemFiliere->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgCreationProjet::projet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgCreationProjet::projet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'filiere.show_' . $itemFiliere->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgSessions::sessionFormation.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgSessions::sessionFormation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'filiere.show_' . $itemFiliere->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('filieres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-filiere')
          <x-action-button :entity="$itemFiliere" actionName="edit">
          @can('update', $itemFiliere)
              <a href="{{ route('filieres.edit', ['filiere' => $itemFiliere->id]) }}" data-id="{{$itemFiliere->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgFormation::filiere.singular") }} : {{ $itemFiliere }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show