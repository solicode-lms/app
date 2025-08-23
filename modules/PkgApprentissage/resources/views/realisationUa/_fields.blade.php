{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationUa-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationUaForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationUas.bulkUpdate') : ($itemRealisationUa->id ? route('realisationUas.update', $itemRealisationUa->id) : route('realisationUas.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationUa->id)
        <input type="hidden" name="id" value="{{ $itemRealisationUa->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($realisationUa_ids))
        @foreach ($realisationUa_ids as $id)
            <input type="hidden" name="realisationUa_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="unite_apprentissage_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="unite_apprentissage_id" id="bulk_field_unite_apprentissage_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="unite_apprentissage_id">
            {{ ucfirst(__('PkgCompetences::uniteApprentissage.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="unite_apprentissage_id" 
            required
            
            
            name="unite_apprentissage_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($uniteApprentissages as $uniteApprentissage)
                    <option value="{{ $uniteApprentissage->id }}"
                        {{ (isset($itemRealisationUa) && $itemRealisationUa->unite_apprentissage_id == $uniteApprentissage->id) || (old('unite_apprentissage_id>') == $uniteApprentissage->id) ? 'selected' : '' }}>
                        {{ $uniteApprentissage }}
                    </option>
                @endforeach
            </select>
          @error('unite_apprentissage_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="realisation_micro_competence_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_micro_competence_id" id="bulk_field_realisation_micro_competence_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_micro_competence_id">
            {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_micro_competence_id" 
            required
            
            
            name="realisation_micro_competence_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationMicroCompetences as $realisationMicroCompetence)
                    <option value="{{ $realisationMicroCompetence->id }}"
                        {{ (isset($itemRealisationUa) && $itemRealisationUa->realisation_micro_competence_id == $realisationMicroCompetence->id) || (old('realisation_micro_competence_id>') == $realisationMicroCompetence->id) ? 'selected' : '' }}>
                        {{ $realisationMicroCompetence }}
                    </option>
                @endforeach
            </select>
          @error('realisation_micro_competence_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="etat_realisation_ua_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="etat_realisation_ua_id" id="bulk_field_etat_realisation_ua_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="etat_realisation_ua_id">
            {{ ucfirst(__('PkgApprentissage::realisationUa.etat_realisation_ua_id')) }}
            
          </label>
                      <select 
            id="etat_realisation_ua_id" 
            
            
            
            name="etat_realisation_ua_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatRealisationUas as $etatRealisationUa)
                    <option value="{{ $etatRealisationUa->id }}"
                        {{ (isset($itemRealisationUa) && $itemRealisationUa->etat_realisation_ua_id == $etatRealisationUa->id) || (old('etat_realisation_ua_id>') == $etatRealisationUa->id) ? 'selected' : '' }}>
                        {{ $etatRealisationUa }}
                    </option>
                @endforeach
            </select>
          @error('etat_realisation_ua_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="progression_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="progression_cache" id="bulk_field_progression_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="progression_cache">
            {{ ucfirst(__('PkgApprentissage::realisationUa.progression_cache')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="progression_cache"
        type="number"
        class="form-control"
        required
        
        
        id="progression_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationUa.progression_cache') }}"
        value="{{ $itemRealisationUa ? number_format($itemRealisationUa->progression_cache, 2, '.', '') : old('progression_cache') }}">
          @error('progression_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="note_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note_cache" id="bulk_field_note_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note_cache">
            {{ ucfirst(__('PkgApprentissage::realisationUa.note_cache')) }}
            
          </label>
              <input
        name="note_cache"
        type="number"
        class="form-control"
        
        
        
        id="note_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationUa.note_cache') }}"
        value="{{ $itemRealisationUa ? number_format($itemRealisationUa->note_cache, 2, '.', '') : old('note_cache') }}">
          @error('note_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="bareme_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="bareme_cache" id="bulk_field_bareme_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="bareme_cache">
            {{ ucfirst(__('PkgApprentissage::realisationUa.bareme_cache')) }}
            
          </label>
              <input
        name="bareme_cache"
        type="number"
        class="form-control"
        
        
        
        id="bareme_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationUa.bareme_cache') }}"
        value="{{ $itemRealisationUa ? number_format($itemRealisationUa->bareme_cache, 2, '.', '') : old('bareme_cache') }}">
          @error('bareme_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="dernier_update" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="dernier_update" id="bulk_field_dernier_update" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dernier_update">
            {{ ucfirst(__('PkgApprentissage::realisationUa.dernier_update')) }}
            
          </label>
                      <input
                name="dernier_update"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="dernier_update"
                placeholder="{{ __('PkgApprentissage::realisationUa.dernier_update') }}"
                value="{{ $itemRealisationUa ? $itemRealisationUa->dernier_update : old('dernier_update') }}">

          @error('dernier_update')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgApprentissage::realisationUa.date_debut')) }}
            
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_debut"
                placeholder="{{ __('PkgApprentissage::realisationUa.date_debut') }}"
                value="{{ $itemRealisationUa ? $itemRealisationUa->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgApprentissage::realisationUa.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgApprentissage::realisationUa.date_fin') }}"
                value="{{ $itemRealisationUa ? $itemRealisationUa->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="commentaire_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="commentaire_formateur" id="bulk_field_commentaire_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="commentaire_formateur">
            {{ ucfirst(__('PkgApprentissage::realisationUa.commentaire_formateur')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="commentaire_formateur"
                class="form-control richText"
                
                
                
                id="commentaire_formateur"
                placeholder="{{ __('PkgApprentissage::realisationUa.commentaire_formateur') }}">{{ $itemRealisationUa ? $itemRealisationUa->commentaire_formateur : old('commentaire_formateur') }}</textarea>
          @error('commentaire_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="progression_ideal_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="progression_ideal_cache" id="bulk_field_progression_ideal_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="progression_ideal_cache">
            {{ ucfirst(__('PkgApprentissage::realisationUa.progression_ideal_cache')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="progression_ideal_cache"
        type="number"
        class="form-control"
        required
        
        
        id="progression_ideal_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationUa.progression_ideal_cache') }}"
        value="{{ $itemRealisationUa ? number_format($itemRealisationUa->progression_ideal_cache, 2, '.', '') : old('progression_ideal_cache') }}">
          @error('progression_ideal_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRealisationUa" field="taux_rythme_cache" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="taux_rythme_cache" id="bulk_field_taux_rythme_cache" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="taux_rythme_cache">
            {{ ucfirst(__('PkgApprentissage::realisationUa.taux_rythme_cache')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="taux_rythme_cache"
        type="number"
        class="form-control"
        required
        
        
        id="taux_rythme_cache"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationUa.taux_rythme_cache') }}"
        value="{{ $itemRealisationUa ? number_format($itemRealisationUa->taux_rythme_cache, 2, '.', '') : old('taux_rythme_cache') }}">
          @error('taux_rythme_cache')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('realisationUas.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationUa->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprentissage::realisationUa.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprentissage::realisationUa.singular") }} : {{$itemRealisationUa}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
