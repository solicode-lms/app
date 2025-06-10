{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetOperation-form')
<form 
    class="crud-form custom-form context-state container" 
    id="widgetOperationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('widgetOperations.bulkUpdate') : ($itemWidgetOperation->id ? route('widgetOperations.update', $itemWidgetOperation->id) : route('widgetOperations.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemWidgetOperation->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($widgetOperation_ids))
        @foreach ($widgetOperation_ids as $id)
            <input type="hidden" name="widgetOperation_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemWidgetOperation" field="operation" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="operation" id="bulk_field_operation" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="operation">
            {{ ucfirst(__('PkgWidgets::widgetOperation.operation')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="operation"
                type="input"
                class="form-control"
                required
                
                
                id="operation"
                placeholder="{{ __('PkgWidgets::widgetOperation.operation') }}"
                value="{{ $itemWidgetOperation ? $itemWidgetOperation->operation : old('operation') }}">
          @error('operation')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemWidgetOperation" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgWidgets::widgetOperation.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgWidgets::widgetOperation.description') }}">{{ $itemWidgetOperation ? $itemWidgetOperation->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('widgetOperations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemWidgetOperation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgWidgets::widgetOperation.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgWidgets::widgetOperation.singular") }} : {{$itemWidgetOperation}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
