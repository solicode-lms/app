{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowProjet-form')
<form 
    class="crud-form custom-form context-state container" 
    id="workflowProjetForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('workflowProjets.bulkUpdate') : ($itemWorkflowProjet->id ? route('workflowProjets.update', $itemWorkflowProjet->id) : route('workflowProjets.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemWorkflowProjet->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($workflowProjet_ids))
        @foreach ($workflowProjet_ids as $id)
            <input type="hidden" name="workflowProjet_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemWorkflowProjet" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgRealisationProjets::workflowProjet.ordre')) }}
            
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                
                
                
                id="ordre"
                placeholder="{{ __('PkgRealisationProjets::workflowProjet.ordre') }}"
                value="{{ $itemWorkflowProjet ? $itemWorkflowProjet->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemWorkflowProjet" field="code" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="code" id="bulk_field_code" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="code">
            {{ ucfirst(__('PkgRealisationProjets::workflowProjet.code')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                required
                
                
                id="code"
                placeholder="{{ __('PkgRealisationProjets::workflowProjet.code') }}"
                value="{{ $itemWorkflowProjet ? $itemWorkflowProjet->code : old('code') }}">
          @error('code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemWorkflowProjet" field="titre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="titre" id="bulk_field_titre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgRealisationProjets::workflowProjet.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgRealisationProjets::workflowProjet.titre') }}"
                value="{{ $itemWorkflowProjet ? $itemWorkflowProjet->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemWorkflowProjet" field="sys_color_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemWorkflowProjet) && $itemWorkflowProjet->sys_color_id == $sysColor->id) || (old('sys_color_id>') == $sysColor->id) ? 'selected' : '' }}>
                        {{ $sysColor }}
                    </option>
                @endforeach
            </select>
          @error('sys_color_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemWorkflowProjet" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgRealisationProjets::workflowProjet.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgRealisationProjets::workflowProjet.description') }}">{{ $itemWorkflowProjet ? $itemWorkflowProjet->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('workflowProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemWorkflowProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgRealisationProjets::workflowProjet.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgRealisationProjets::workflowProjet.singular") }} : {{$itemWorkflowProjet}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
