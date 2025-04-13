{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sectionWidget-form')
<form class="crud-form custom-form context-state container" id="sectionWidgetForm" action="{{ $itemSectionWidget->id ? route('sectionWidgets.update', $itemSectionWidget->id) : route('sectionWidgets.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemSectionWidget->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          <label for="titre">
            {{ ucfirst(__('PkgWidgets::sectionWidget.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgWidgets::sectionWidget.titre') }}"
                value="{{ $itemSectionWidget ? $itemSectionWidget->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="sous_titre">
            {{ ucfirst(__('PkgWidgets::sectionWidget.sous_titre')) }}
            
          </label>
           <input
                name="sous_titre"
                type="input"
                class="form-control"
                
                
                
                id="sous_titre"
                placeholder="{{ __('PkgWidgets::sectionWidget.sous_titre') }}"
                value="{{ $itemSectionWidget ? $itemSectionWidget->sous_titre : old('sous_titre') }}">
          @error('sous_titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="icone">
            {{ ucfirst(__('PkgWidgets::sectionWidget.icone')) }}
            
          </label>
           <input
                name="icone"
                type="input"
                class="form-control"
                
                
                
                id="icone"
                placeholder="{{ __('PkgWidgets::sectionWidget.icone') }}"
                value="{{ $itemSectionWidget ? $itemSectionWidget->icone : old('icone') }}">
          @error('icone')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="ordre">
            {{ ucfirst(__('PkgWidgets::sectionWidget.ordre')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                
                id="ordre"
                placeholder="{{ __('PkgWidgets::sectionWidget.ordre') }}"
                value="{{ $itemSectionWidget ? $itemSectionWidget->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="sys_color_id">
            {{ ucfirst(__('Core::sysColor.singular')) }}
            
          </label>
                      <select 
            id="sys_color_id" 
            
            
            
            name="sys_color_id" 
            class="form-control select2Color">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysColors as $sysColor)
                    <option value="{{ $sysColor->id }}" data-color="{{ $sysColor->hex }}" 
                        {{ (isset($itemSectionWidget) && $itemSectionWidget->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
          @error('sys_color_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   Widget HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('sectionWidgets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSectionWidget->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgWidgets::sectionWidget.singular") }} : {{$itemSectionWidget}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
