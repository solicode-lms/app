{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatChapitre-form')
<form 
    class="crud-form custom-form context-state container" 
    id="etatChapitreForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('etatChapitres.bulkUpdate') : ($itemEtatChapitre->id ? route('etatChapitres.update', $itemEtatChapitre->id) : route('etatChapitres.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEtatChapitre->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($etatChapitre_ids))
        @foreach ($etatChapitre_ids as $id)
            <input type="hidden" name="etatChapitre_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemEtatChapitre" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgAutoformation::etatChapitre.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgAutoformation::etatChapitre.nom') }}"
                value="{{ $itemEtatChapitre ? $itemEtatChapitre->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEtatChapitre" field="workflow_chapitre_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="workflow_chapitre_id" id="bulk_field_workflow_chapitre_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="workflow_chapitre_id">
            {{ ucfirst(__('PkgAutoformation::workflowChapitre.singular')) }}
            
          </label>
                      <select 
            id="workflow_chapitre_id" 
            
            
            
            name="workflow_chapitre_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($workflowChapitres as $workflowChapitre)
                    <option value="{{ $workflowChapitre->id }}"
                        {{ (isset($itemEtatChapitre) && $itemEtatChapitre->workflow_chapitre_id == $workflowChapitre->id) || (old('workflow_chapitre_id>') == $workflowChapitre->id) ? 'selected' : '' }}>
                        {{ $workflowChapitre }}
                    </option>
                @endforeach
            </select>
          @error('workflow_chapitre_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEtatChapitre" field="sys_color_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sys_color_id" id="bulk_field_sys_color_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="sys_color_id">
            {{ ucfirst(__('Core::sysColor.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="sys_color_id" 
            required
            
            
            name="sys_color_id" 
            class="form-control select2Color">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysColors as $sysColor)
                    <option value="{{ $sysColor->id }}" data-color="{{ $sysColor->hex }}" 
                        {{ (isset($itemEtatChapitre) && $itemEtatChapitre->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
          @error('sys_color_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEtatChapitre" field="is_editable_only_by_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_editable_only_by_formateur" id="bulk_field_is_editable_only_by_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_editable_only_by_formateur">
            {{ ucfirst(__('PkgAutoformation::etatChapitre.is_editable_only_by_formateur')) }}
            
          </label>
                      <input type="hidden" name="is_editable_only_by_formateur" value="0">
            <input
                name="is_editable_only_by_formateur"
                type="checkbox"
                class="form-control"
                
                
                
                id="is_editable_only_by_formateur"
                value="1"
                {{ old('is_editable_only_by_formateur', $itemEtatChapitre ? $itemEtatChapitre->is_editable_only_by_formateur : 0) ? 'checked' : '' }}>
          @error('is_editable_only_by_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEtatChapitre" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgAutoformation::etatChapitre.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgAutoformation::etatChapitre.description') }}">{{ $itemEtatChapitre ? $itemEtatChapitre->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEtatChapitre" field="formateur_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="formateur_id" id="bulk_field_formateur_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="formateur_id">
            {{ ucfirst(__('PkgFormation::formateur.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="formateur_id" 
            required
            
            
            name="formateur_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemEtatChapitre) && $itemEtatChapitre->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('etatChapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEtatChapitre->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgAutoformation::etatChapitre.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgAutoformation::etatChapitre.singular") }} : {{$itemEtatChapitre}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
