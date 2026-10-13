{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('remediation-form')
<form 
    class="crud-form custom-form context-state container" 
    id="remediationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('remediations.bulkUpdate') : ($itemRemediation->id ? route('remediations.update', $itemRemediation->id) : route('remediations.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRemediation->id)
        <input type="hidden" name="id" value="{{ $itemRemediation->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($remediation_ids))
        @foreach ($remediation_ids as $id)
            <input type="hidden" name="remediation_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemRemediation" field="realisation_tache_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="realisation_tache_id" 
              id="bulk_field_realisation_tache_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_tache_id">
            {{ ucfirst(__('PkgRealisationTache::realisationTache.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_tache_id" 
            required
            
            
            name="realisation_tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationTaches as $realisationTache)
                    <option value="{{ $realisationTache->id }}" 
                        {{ (isset($itemRemediation) && $itemRemediation->realisation_tache_id == $realisationTache->id) || (old('realisation_tache_id>') == $realisationTache->id) ? 'selected' : '' }}>
                        {{ $realisationTache }}
                    </option>
                @endforeach
            </select>
          @error('realisation_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRemediation" field="formateur_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="formateur_id" 
              id="bulk_field_formateur_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
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
                        {{ (isset($itemRemediation) && $itemRemediation->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
          @error('formateur_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRemediation" field="type_remediation" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="type_remediation" 
              id="bulk_field_type_remediation" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="type_remediation">
            {{ ucfirst(__('PkgTableauBord::remediation.type_remediation')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="type_remediation"
                type="input"
                class="form-control"
                required
                
                
                id="type_remediation"
                placeholder="{{ __('PkgTableauBord::remediation.type_remediation') }}"
                value="{{ $itemRemediation ? $itemRemediation->type_remediation : old('type_remediation') }}">
          @error('type_remediation')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRemediation" field="diagnostic" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="diagnostic" 
              id="bulk_field_diagnostic" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="diagnostic">
            {{ ucfirst(__('PkgTableauBord::remediation.diagnostic')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="diagnostic"
                class="form-control richText"
                
                
                
                id="diagnostic">
                {!! \App\Helpers\TextHelper::sanitizeTextarea(old('diagnostic', $itemRemediation->diagnostic ?? '')) !!}
                </textarea>
          @error('diagnostic')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRemediation" field="est_resolu" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="est_resolu" 
              id="bulk_field_est_resolu" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="est_resolu">
            {{ ucfirst(__('PkgTableauBord::remediation.est_resolu')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="est_resolu" value="0">
            <input
                name="est_resolu"
                type="checkbox"
                class="form-control d-block"
                required
                
                
                id="est_resolu"
                value="1"
                {{ old('est_resolu', $itemRemediation ? $itemRemediation->est_resolu : 0) ? 'checked' : '' }}>
          @error('est_resolu')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('remediations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRemediation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgTableauBord::remediation.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgTableauBord::remediation.singular") }} : {{$itemRemediation}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
