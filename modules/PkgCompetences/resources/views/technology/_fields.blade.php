{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('technology-form')
<form 
    class="crud-form custom-form context-state container" 
    id="technologyForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('technologies.bulkUpdate') : ($itemTechnology->id ? route('technologies.update', $itemTechnology->id) : route('technologies.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemTechnology->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($technology_ids))
        @foreach ($technology_ids as $id)
            <input type="hidden" name="technology_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemTechnology" field="nom" bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgCompetences::technology.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgCompetences::technology.nom') }}"
                value="{{ $itemTechnology ? $itemTechnology->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemTechnology" field="category_technology_id" bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="category_technology_id" id="bulk_field_category_technology_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="category_technology_id">
            {{ ucfirst(__('PkgCompetences::categoryTechnology.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="category_technology_id" 
            required
            
            
            name="category_technology_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($categoryTechnologies as $categoryTechnology)
                    <option value="{{ $categoryTechnology->id }}"
                        {{ (isset($itemTechnology) && $itemTechnology->category_technology_id == $categoryTechnology->id) || (old('category_technology_id>') == $categoryTechnology->id) ? 'selected' : '' }}>
                        {{ $categoryTechnology }}
                    </option>
                @endforeach
            </select>
          @error('category_technology_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemTechnology" field="competences" bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="competences" id="bulk_field_competences" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="competences">
            {{ ucfirst(__('PkgCompetences::competence.plural')) }}
            
          </label>
                      <select
                id="competences"
                name="competences[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($competences as $competence)
                    <option value="{{ $competence->id }}"
                        {{ (isset($itemTechnology) && $itemTechnology->competences && $itemTechnology->competences->contains('id', $competence->id)) || (is_array(old('competences')) && in_array($competence->id, old('competences'))) ? 'selected' : '' }}>
                        {{ $competence }}
                    </option>
                @endforeach
            </select>
          @error('competences')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemTechnology" field="description" bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgCompetences::technology.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgCompetences::technology.description') }}">{{ $itemTechnology ? $itemTechnology->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('technologies.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemTechnology->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgCompetences::technology.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCompetences::technology.singular") }} : {{$itemTechnology}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
