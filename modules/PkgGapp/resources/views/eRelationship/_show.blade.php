{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eRelationship-show')
<div id="eRelationship-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.name')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemERelationship->name) && $itemERelationship->name !== '')
        {{ $itemERelationship->name }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.type')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemERelationship->type) && $itemERelationship->type !== '')
        {{ $itemERelationship->type }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eModel.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemERelationship->eModel)
                  {{ $itemERelationship->eModel }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eModel.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemERelationship->eModel)
                  {{ $itemERelationship->eModel }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.cascade_on_delete')) }}</small>
                  @if($itemERelationship->cascade_on_delete)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.is_cascade')) }}</small>
                  @if($itemERelationship->is_cascade)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemERelationship->description) && $itemERelationship->description !== '')
                    {!! $itemERelationship->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.column_name')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemERelationship->column_name) && $itemERelationship->column_name !== '')
        {{ $itemERelationship->column_name }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.referenced_table')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemERelationship->referenced_table) && $itemERelationship->referenced_table !== '')
        {{ $itemERelationship->referenced_table }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.referenced_column')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemERelationship->referenced_column) && $itemERelationship->referenced_column !== '')
        {{ $itemERelationship->referenced_column }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.through')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemERelationship->through) && $itemERelationship->through !== '')
        {{ $itemERelationship->through }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.with_column')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemERelationship->with_column) && $itemERelationship->with_column !== '')
        {{ $itemERelationship->with_column }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.morph_name')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemERelationship->morph_name) && $itemERelationship->morph_name !== '')
        {{ $itemERelationship->morph_name }}
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
                        @include('PkgGapp::eDataField._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'eRelationship.show_' . $itemERelationship->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('eRelationships.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-eRelationship')
          <x-action-button :entity="$itemERelationship" actionName="edit">
          @can('update', $itemERelationship)
              <a href="{{ route('eRelationships.edit', ['eRelationship' => $itemERelationship->id]) }}" data-id="{{$itemERelationship->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgGapp::eRelationship.singular") }} : {{ $itemERelationship }}';
    window.showUIId = 'eRelationship-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show