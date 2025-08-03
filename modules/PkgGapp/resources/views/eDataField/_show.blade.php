{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eDataField-show')
<div id="eDataField-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.name')) }}</small>
                              @if(! is_null($itemEDataField->name) && $itemEDataField->name !== '')
        {{ $itemEDataField->name }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eModel.singular')) }}</small>
                              
      @if($itemEDataField->eModel)
        {{ $itemEDataField->eModel }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.data_type')) }}</small>
                              @if(! is_null($itemEDataField->data_type) && $itemEDataField->data_type !== '')
        {{ $itemEDataField->data_type }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.default_value')) }}</small>
                              @if(! is_null($itemEDataField->default_value) && $itemEDataField->default_value !== '')
        {{ $itemEDataField->default_value }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.column_name')) }}</small>
                              @if(! is_null($itemEDataField->column_name) && $itemEDataField->column_name !== '')
        {{ $itemEDataField->column_name }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eRelationship.singular')) }}</small>
                              
      @if($itemEDataField->eRelationship)
        {{ $itemEDataField->eRelationship }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.field_order')) }}</small>
                              
      <span>
        @if(! is_null($itemEDataField->field_order))
          {{ $itemEDataField->field_order }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-2 col-lg-2 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.db_primaryKey')) }}</small>
                              
      @if($itemEDataField->db_primaryKey)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-2 col-lg-2 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.db_nullable')) }}</small>
                              
      @if($itemEDataField->db_nullable)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-2 col-lg-2 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.db_unique')) }}</small>
                              
      @if($itemEDataField->db_unique)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-2 col-lg-2 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.calculable')) }}</small>
                              
      @if($itemEDataField->calculable)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgGapp::eMetadatum.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgGapp::eMetadatum._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'eDataField.show_' . $itemEDataField->id])
            </div>
          </div>
      </div>
   

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.calculable_sql')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEDataField->calculable_sql) && $itemEDataField->calculable_sql !== '')
    {!! $itemEDataField->calculable_sql !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEDataField->description) && $itemEDataField->description !== '')
    {!! $itemEDataField->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('eDataFields.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-eDataField')
          <x-action-button :entity="$itemEDataField" actionName="edit">
          @can('update', $itemEDataField)
              <a href="{{ route('eDataFields.edit', ['eDataField' => $itemEDataField->id]) }}" data-id="{{$itemEDataField->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgGapp::eDataField.singular") }} : {{ $itemEDataField }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show