{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatsRealisationProjet-form')
<form 
    class="crud-form custom-form context-state container" 
    id="etatsRealisationProjetForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('etatsRealisationProjets.bulkUpdate') : ($itemEtatsRealisationProjet->id ? route('etatsRealisationProjets.update', $itemEtatsRealisationProjet->id) : route('etatsRealisationProjets.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEtatsRealisationProjet->id)
        <input type="hidden" name="id" value="{{ $itemEtatsRealisationProjet->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($etatsRealisationProjet_ids))
        @foreach ($etatsRealisationProjet_ids as $id)
            <input type="hidden" name="etatsRealisationProjet_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatsRealisationProjet" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.ordre')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                
                id="ordre"
                placeholder="{{ __('PkgRealisationProjets::etatsRealisationProjet.ordre') }}"
                value="{{ $itemEtatsRealisationProjet ? $itemEtatsRealisationProjet->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatsRealisationProjet" field="titre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="titre" id="bulk_field_titre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgRealisationProjets::etatsRealisationProjet.titre') }}"
                value="{{ $itemEtatsRealisationProjet ? $itemEtatsRealisationProjet->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatsRealisationProjet" field="code" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="code" id="bulk_field_code" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="code">
            {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.code')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                required
                
                
                id="code"
                placeholder="{{ __('PkgRealisationProjets::etatsRealisationProjet.code') }}"
                value="{{ $itemEtatsRealisationProjet ? $itemEtatsRealisationProjet->code : old('code') }}">
          @error('code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatsRealisationProjet" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgRealisationProjets::etatsRealisationProjet.description') }}">{{ $itemEtatsRealisationProjet ? $itemEtatsRealisationProjet->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatsRealisationProjet" field="sys_color_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sys_color_id" id="bulk_field_sys_color_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
                        {{ (isset($itemEtatsRealisationProjet) && $itemEtatsRealisationProjet->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
          @error('sys_color_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemEtatsRealisationProjet" field="is_editable_by_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_editable_by_formateur" id="bulk_field_is_editable_by_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_editable_by_formateur">
            {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.is_editable_by_formateur')) }}
            
          </label>
                      <input type="hidden" name="is_editable_by_formateur" value="0">
            <input
                name="is_editable_by_formateur"
                type="checkbox"
                class="form-control d-block"
                
                
                
                id="is_editable_by_formateur"
                value="1"
                {{ old('is_editable_by_formateur', $itemEtatsRealisationProjet ? $itemEtatsRealisationProjet->is_editable_by_formateur : 0) ? 'checked' : '' }}>
          @error('is_editable_by_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('etatsRealisationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEtatsRealisationProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgRealisationProjets::etatsRealisationProjet.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgRealisationProjets::etatsRealisationProjet.singular") }} : {{$itemEtatsRealisationProjet}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
