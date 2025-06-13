{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetUtilisateur-form')
<form 
    class="crud-form custom-form context-state container" 
    id="widgetUtilisateurForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('widgetUtilisateurs.bulkUpdate') : ($itemWidgetUtilisateur->id ? route('widgetUtilisateurs.update', $itemWidgetUtilisateur->id) : route('widgetUtilisateurs.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemWidgetUtilisateur->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($widgetUtilisateur_ids))
        @foreach ($widgetUtilisateur_ids as $id)
            <input type="hidden" name="widgetUtilisateur_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemWidgetUtilisateur" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgWidgets::widgetUtilisateur.ordre')) }}
            
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                
                
                
                id="ordre"
                placeholder="{{ __('PkgWidgets::widgetUtilisateur.ordre') }}"
                value="{{ $itemWidgetUtilisateur ? $itemWidgetUtilisateur->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemWidgetUtilisateur" field="sys_module_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sys_module_id" id="bulk_field_sys_module_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="sys_module_id">
            {{ ucfirst(__('PkgWidgets::widgetUtilisateur.sys_module_id')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="sys_module_id"
        type="number"
        class="form-control"
        required
        
        
        id="sys_module_id"
        step="0.01"
        placeholder="{{ __('PkgWidgets::widgetUtilisateur.sys_module_id') }}"
        value="{{ $itemWidgetUtilisateur ? number_format($itemWidgetUtilisateur->sys_module_id, 2, '.', '') : old('sys_module_id') }}">
          @error('sys_module_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemWidgetUtilisateur" field="user_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="user_id" id="bulk_field_user_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="user_id">
            {{ ucfirst(__('PkgAutorisation::user.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="user_id" 
            required
            
            
            name="user_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ (isset($itemWidgetUtilisateur) && $itemWidgetUtilisateur->user_id == $user->id) || (old('user_id>') == $user->id) ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
          @error('user_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemWidgetUtilisateur" field="widget_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="widget_id" id="bulk_field_widget_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="widget_id">
            {{ ucfirst(__('PkgWidgets::widget.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="widget_id" 
            required
            data-calcul='true'
            
            name="widget_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($widgets as $widget)
                    <option value="{{ $widget->id }}"
                        {{ (isset($itemWidgetUtilisateur) && $itemWidgetUtilisateur->widget_id == $widget->id) || (old('widget_id>') == $widget->id) ? 'selected' : '' }}>
                        {{ $widget }}
                    </option>
                @endforeach
            </select>
          @error('widget_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemWidgetUtilisateur" field="visible" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="visible" id="bulk_field_visible" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="visible">
            {{ ucfirst(__('PkgWidgets::widgetUtilisateur.visible')) }}
            
          </label>
                      <input type="hidden" name="visible" value="0">
            <input
                name="visible"
                type="checkbox"
                class="form-control"
                
                
                
                id="visible"
                value="1"
                {{ old('visible', $itemWidgetUtilisateur ? $itemWidgetUtilisateur->visible : 0) ? 'checked' : '' }}>
          @error('visible')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('widgetUtilisateurs.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemWidgetUtilisateur->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgWidgets::widgetUtilisateur.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgWidgets::widgetUtilisateur.singular") }} : {{$itemWidgetUtilisateur}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
