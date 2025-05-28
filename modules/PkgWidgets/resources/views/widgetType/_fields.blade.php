{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetType-form')
<form 
    class="crud-form custom-form context-state container" 
    id="widgetTypeForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('widgetTypes.bulkUpdate') : ($itemWidgetType->id ? route('widgetTypes.update', $itemWidgetType->id) : route('widgetTypes.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemWidgetType->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($widgetType_ids))
        @foreach ($widgetType_ids as $id)
            <input type="hidden" name="widgetType_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemWidgetType" field="type" bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="type" id="bulk_field_type" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="type">
            {{ ucfirst(__('PkgWidgets::widgetType.type')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="type"
                type="input"
                class="form-control"
                required
                
                
                id="type"
                placeholder="{{ __('PkgWidgets::widgetType.type') }}"
                value="{{ $itemWidgetType ? $itemWidgetType->type : old('type') }}">
          @error('type')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemWidgetType" field="description" bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgWidgets::widgetType.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgWidgets::widgetType.description') }}">{{ $itemWidgetType ? $itemWidgetType->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('widgetTypes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemWidgetType->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgWidgets::widgetType.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgWidgets::widgetType.singular") }} : {{$itemWidgetType}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
