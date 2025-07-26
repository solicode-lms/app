{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('competence-form')
<form 
    class="crud-form custom-form context-state container" 
    id="competenceForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('competences.bulkUpdate') : ($itemCompetence->id ? route('competences.update', $itemCompetence->id) : route('competences.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemCompetence->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($competence_ids))
        @foreach ($competence_ids as $id)
            <input type="hidden" name="competence_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemCompetence" field="code" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="code" id="bulk_field_code" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="code">
            {{ ucfirst(__('PkgCompetences::competence.code')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="code"
                type="input"
                class="form-control"
                required
                
                
                id="code"
                placeholder="{{ __('PkgCompetences::competence.code') }}"
                value="{{ $itemCompetence ? $itemCompetence->code : old('code') }}">
          @error('code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemCompetence" field="mini_code" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="mini_code" id="bulk_field_mini_code" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="mini_code">
            {{ ucfirst(__('PkgCompetences::competence.mini_code')) }}
            
          </label>
           <input
                name="mini_code"
                type="input"
                class="form-control"
                
                
                
                id="mini_code"
                placeholder="{{ __('PkgCompetences::competence.mini_code') }}"
                value="{{ $itemCompetence ? $itemCompetence->mini_code : old('mini_code') }}">
          @error('mini_code')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemCompetence" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgCompetences::competence.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgCompetences::competence.nom') }}"
                value="{{ $itemCompetence ? $itemCompetence->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemCompetence" field="module_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="module_id" id="bulk_field_module_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="module_id">
            {{ ucfirst(__('PkgFormation::module.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="module_id" 
            required
            
            
            name="module_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($modules as $module)
                    <option value="{{ $module->id }}"
                        {{ (isset($itemCompetence) && $itemCompetence->module_id == $module->id) || (old('module_id>') == $module->id) ? 'selected' : '' }}>
                        {{ $module }}
                    </option>
                @endforeach
            </select>
          @error('module_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemCompetence" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgCompetences::competence.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgCompetences::competence.description') }}">{{ $itemCompetence ? $itemCompetence->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('competences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemCompetence->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgCompetences::competence.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCompetences::competence.singular") }} : {{$itemCompetence}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
