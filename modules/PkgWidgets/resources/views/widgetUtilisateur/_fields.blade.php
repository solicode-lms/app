{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetUtilisateur-form')
<form class="crud-form custom-form context-state container" id="widgetUtilisateurForm" action="{{ $itemWidgetUtilisateur->id ? route('widgetUtilisateurs.update', $itemWidgetUtilisateur->id) : route('widgetUtilisateurs.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemWidgetUtilisateur->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
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
            <label for="ordre">
                {{ ucfirst(__('PkgWidgets::widgetUtilisateur.ordre')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                id="ordre"
                placeholder="{{ __('PkgWidgets::widgetUtilisateur.ordre') }}"
                value="{{ $itemWidgetUtilisateur ? $itemWidgetUtilisateur->ordre : old('ordre') }}">
            @error('ordre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="titre">
                {{ ucfirst(__('PkgWidgets::widgetUtilisateur.titre')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                id="titre"
                placeholder="{{ __('PkgWidgets::widgetUtilisateur.titre') }}"
                value="{{ $itemWidgetUtilisateur ? $itemWidgetUtilisateur->titre : old('titre') }}">
            @error('titre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="sous_titre">
                {{ ucfirst(__('PkgWidgets::widgetUtilisateur.sous_titre')) }}
                
            </label>
            <input
                name="sous_titre"
                type="input"
                class="form-control"
                
                
                id="sous_titre"
                placeholder="{{ __('PkgWidgets::widgetUtilisateur.sous_titre') }}"
                value="{{ $itemWidgetUtilisateur ? $itemWidgetUtilisateur->sous_titre : old('sous_titre') }}">
            @error('sous_titre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
    <label for="config">
        {{ ucfirst(__('PkgWidgets::widgetUtilisateur.config')) }}
        
    </label>
    
    <div class="form-control editeur_json code-editor"
        contenteditable="true">{{ $itemWidgetUtilisateur ? $itemWidgetUtilisateur->config : old('config') }}</div>
    
    <input
        type="hidden"
        name="config"
        class="form-control"
        id="config"
         
        
        value = "{{ $itemWidgetUtilisateur ? $itemWidgetUtilisateur->config : old('config') }}"
    >


    @error('config')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>



        
        <div class="form-group col-12 col-md-6">
            <label for="visible">
                {{ ucfirst(__('PkgWidgets::widgetUtilisateur.visible')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input type="hidden" name="visible" value="0">
            <input
                name="visible"
                type="checkbox"
                class="form-control"
                required
                
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
