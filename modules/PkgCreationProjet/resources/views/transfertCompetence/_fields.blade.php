{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('transfertCompetence-form')
<form 
    class="crud-form custom-form context-state container" 
    id="transfertCompetenceForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('transfertCompetences.bulkUpdate') : ($itemTransfertCompetence->id ? route('transfertCompetences.update', $itemTransfertCompetence->id) : route('transfertCompetences.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemTransfertCompetence->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($transfertCompetence_ids))
        @foreach ($transfertCompetence_ids as $id)
            <input type="hidden" name="transfertCompetence_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

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
                        {{ (isset($itemTransfertCompetence) && $itemTransfertCompetence->competence_id == $competence->id) || (old('competence_id>') == $competence->id) ? 'selected' : '' }}>
                        {{ $competence }}
                    </option>
                @endforeach
            </select>
          @error('competence_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="niveau_difficulte_id" id="bulk_field_niveau_difficulte_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="niveau_difficulte_id">
            {{ ucfirst(__('PkgCompetences::niveauDifficulte.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="niveau_difficulte_id" 
            required
            
            
            name="niveau_difficulte_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($niveauDifficultes as $niveauDifficulte)
                    <option value="{{ $niveauDifficulte->id }}"
                        {{ (isset($itemTransfertCompetence) && $itemTransfertCompetence->niveau_difficulte_id == $niveauDifficulte->id) || (old('niveau_difficulte_id>') == $niveauDifficulte->id) ? 'selected' : '' }}>
                        {{ $niveauDifficulte }}
                    </option>
                @endforeach
            </select>
          @error('niveau_difficulte_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="technologies" id="bulk_field_technologies" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="technologies">
            {{ ucfirst(__('PkgCompetences::technology.plural')) }}
            
          </label>
                      <select
                id="technologies"
                name="technologies[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($technologies as $technology)
                    <option value="{{ $technology->id }}"
                        {{ (isset($itemTransfertCompetence) && $itemTransfertCompetence->technologies && $itemTransfertCompetence->technologies->contains('id', $technology->id)) || (is_array(old('technologies')) && in_array($technology->id, old('technologies'))) ? 'selected' : '' }}>
                        {{ $technology }}
                    </option>
                @endforeach
            </select>
          @error('technologies')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note" id="bulk_field_note" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note">
            {{ ucfirst(__('PkgCreationProjet::transfertCompetence.note')) }}
            
          </label>
              <input
        name="note"
        type="number"
        class="form-control"
        
        
        
        id="note"
        step="0.01"
        placeholder="{{ __('PkgCreationProjet::transfertCompetence.note') }}"
        value="{{ $itemTransfertCompetence ? number_format($itemTransfertCompetence->note, 2, '.', '') : old('note') }}">
          @error('note')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   Validation HasMany --> 


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="projet_id" id="bulk_field_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="projet_id">
            {{ ucfirst(__('PkgCreationProjet::projet.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="projet_id" 
            required
            
            
            name="projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($projets as $projet)
                    <option value="{{ $projet->id }}"
                        {{ (isset($itemTransfertCompetence) && $itemTransfertCompetence->projet_id == $projet->id) || (old('projet_id>') == $projet->id) ? 'selected' : '' }}>
                        {{ $projet }}
                    </option>
                @endforeach
            </select>
          @error('projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="question" id="bulk_field_question" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="question">
            {{ ucfirst(__('PkgCreationProjet::transfertCompetence.question')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="question"
                class="form-control richText"
                
                data-calcul='true'
                
                id="question"
                placeholder="{{ __('PkgCreationProjet::transfertCompetence.question') }}">{{ $itemTransfertCompetence ? $itemTransfertCompetence->question : old('question') }}</textarea>
          @error('question')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('transfertCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemTransfertCompetence->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgCreationProjet::transfertCompetence.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCreationProjet::transfertCompetence.singular") }} : {{$itemTransfertCompetence}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
