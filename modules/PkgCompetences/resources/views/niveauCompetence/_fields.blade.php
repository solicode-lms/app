{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauCompetence-form')
<form 
    class="crud-form custom-form context-state container" 
    id="niveauCompetenceForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('niveauCompetences.bulkUpdate') : ($itemNiveauCompetence->id ? route('niveauCompetences.update', $itemNiveauCompetence->id) : route('niveauCompetences.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemNiveauCompetence->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($niveauCompetence_ids))
        @foreach ($niveauCompetence_ids as $id)
            <input type="hidden" name="niveauCompetence_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgCompetences::niveauCompetence.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgCompetences::niveauCompetence.nom') }}"
                value="{{ $itemNiveauCompetence ? $itemNiveauCompetence->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgCompetences::niveauCompetence.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgCompetences::niveauCompetence.description') }}">{{ $itemNiveauCompetence ? $itemNiveauCompetence->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="competence_id" id="bulk_field_competence_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="competence_id">
            {{ ucfirst(__('PkgCompetences::competence.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="competence_id" 
            required
            
            
            name="competence_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($competences as $competence)
                    <option value="{{ $competence->id }}"
                        {{ (isset($itemNiveauCompetence) && $itemNiveauCompetence->competence_id == $competence->id) || (old('competence_id>') == $competence->id) ? 'selected' : '' }}>
                        {{ $competence }}
                    </option>
                @endforeach
            </select>
          @error('competence_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('niveauCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNiveauCompetence->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgCompetences::niveauCompetence.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCompetences::niveauCompetence.singular") }} : {{$itemNiveauCompetence}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
