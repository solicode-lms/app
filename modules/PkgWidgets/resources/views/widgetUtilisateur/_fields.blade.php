{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetUtilisateur-form')
<form class="crud-form custom-form context-state container" id="widgetUtilisateurForm" action="{{ $itemWidgetUtilisateur->id ? route('widgetUtilisateurs.update', $itemWidgetUtilisateur->id) : route('widgetUtilisateurs.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemWidgetUtilisateur->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
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
  


      <div class="form-group col-12 col-md-6">
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
  


      <div class="form-group col-12 col-md-6">
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
  


      <div class="form-group col-12 col-md-6">
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
  


      <div class="form-group col-12 col-md-6">
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
     window.modalTitle = '{{__("PkgWidgets::widgetUtilisateur.singular") }} : {{$itemWidgetUtilisateur}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
