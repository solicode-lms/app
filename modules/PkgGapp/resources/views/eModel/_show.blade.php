{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eModel-show')
<div id="eModel-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eModel.name')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEModel->name) && $itemEModel->name !== '')
        {{ $itemEModel->name }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eModel.table_name')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEModel->table_name) && $itemEModel->table_name !== '')
        {{ $itemEModel->table_name }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eModel.icon')) }}</small>
    {{-- Icône centrée --}}
    @if(!is_null($itemEModel->icon) && $itemEModel->icon !== '')
        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
            <i class="{{ $itemEModel->icon }}"></i>
        </div>
    @else
        <span class="text-muted">—</span>
    @endif

                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eModel.is_pivot_table')) }}</small>
                  @if($itemEModel->is_pivot_table)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eModel.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemEModel->description) && $itemEModel->description !== '')
                    {!! $itemEModel->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::ePackage.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemEModel->ePackage)
                  {{ $itemEModel->ePackage }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            @if(auth()->user()?->can('show-eDataField') || auth()->user()?->can('create-eDataField'))
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgGapp::eDataField.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgGapp::eDataField._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'eModel.show_' . $itemEModel->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(auth()->user()?->can('show-eMetadatum') || auth()->user()?->can('create-eMetadatum'))
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgGapp::eMetadatum.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgGapp::eMetadatum._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'eModel.show_' . $itemEModel->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(auth()->user()?->can('show-eRelationship') || auth()->user()?->can('create-eRelationship'))
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgGapp::eRelationship.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgGapp::eRelationship._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'eModel.show_' . $itemEModel->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(auth()->user()?->can('show-eRelationship') || auth()->user()?->can('create-eRelationship'))
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgGapp::eRelationship.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgGapp::eRelationship._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'eModel.show_' . $itemEModel->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('eModels.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-eModel')
          <x-action-button :entity="$itemEModel" actionName="edit">
          @can('update', $itemEModel)
              <a href="{{ route('eModels.edit', ['eModel' => $itemEModel->id]) }}" data-id="{{$itemEModel->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgGapp::eModel.singular") }} : {{ $itemEModel }}';
    window.showUIId = 'eModel-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show