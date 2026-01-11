{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('mobilisationUa-form')
<form 
    class="crud-form custom-form context-state container" 
    id="mobilisationUaForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('mobilisationUas.bulkUpdate') : ($itemMobilisationUa->id ? route('mobilisationUas.update', $itemMobilisationUa->id) : route('mobilisationUas.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemMobilisationUa->id)
        <input type="hidden" name="id" value="{{ $itemMobilisationUa->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($mobilisationUa_ids))
        @foreach ($mobilisationUa_ids as $id)
            <input type="hidden" name="mobilisationUa_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemMobilisationUa" field="unite_apprentissage_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="unite_apprentissage_id" 
              id="bulk_field_unite_apprentissage_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
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
                        {{ (isset($itemMobilisationUa) && $itemMobilisationUa->unite_apprentissage_id == $uniteApprentissage->id) || (old('unite_apprentissage_id>') == $uniteApprentissage->id) ? 'selected' : '' }}>
                        {{ $uniteApprentissage }}
                    </option>
                @endforeach
            </select>
          @error('unite_apprentissage_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemMobilisationUa" field="bareme_evaluation_prototype" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="bareme_evaluation_prototype" 
              id="bulk_field_bareme_evaluation_prototype" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="bareme_evaluation_prototype">
            {{ ucfirst(__('PkgCreationProjet::mobilisationUa.bareme_evaluation_prototype')) }}
            
          </label>
              <input
        name="bareme_evaluation_prototype"
        type="number"
        class="form-control"
        
        
        
        id="bareme_evaluation_prototype"
        step="0.01"
        placeholder="{{ __('PkgCreationProjet::mobilisationUa.bareme_evaluation_prototype') }}"
        value="{{ $itemMobilisationUa ? number_format($itemMobilisationUa->bareme_evaluation_prototype, 2, '.', '') : old('bareme_evaluation_prototype') }}">
          @error('bareme_evaluation_prototype')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemMobilisationUa" field="bareme_evaluation_projet" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="bareme_evaluation_projet" 
              id="bulk_field_bareme_evaluation_projet" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="bareme_evaluation_projet">
            {{ ucfirst(__('PkgCreationProjet::mobilisationUa.bareme_evaluation_projet')) }}
            
          </label>
              <input
        name="bareme_evaluation_projet"
        type="number"
        class="form-control"
        
        
        
        id="bareme_evaluation_projet"
        step="0.01"
        placeholder="{{ __('PkgCreationProjet::mobilisationUa.bareme_evaluation_projet') }}"
        value="{{ $itemMobilisationUa ? number_format($itemMobilisationUa->bareme_evaluation_projet, 2, '.', '') : old('bareme_evaluation_projet') }}">
          @error('bareme_evaluation_projet')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemMobilisationUa" field="criteres_evaluation_prototype" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="criteres_evaluation_prototype" 
              id="bulk_field_criteres_evaluation_prototype" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="criteres_evaluation_prototype">
            {{ ucfirst(__('PkgCreationProjet::mobilisationUa.criteres_evaluation_prototype')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="criteres_evaluation_prototype"
                class="form-control richText"
                
                
                
                id="criteres_evaluation_prototype">
                {!! \App\Helpers\TextHelper::sanitizeTextarea(old('criteres_evaluation_prototype', $itemMobilisationUa->criteres_evaluation_prototype ?? '')) !!}
                </textarea>
          @error('criteres_evaluation_prototype')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemMobilisationUa" field="criteres_evaluation_projet" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="criteres_evaluation_projet" 
              id="bulk_field_criteres_evaluation_projet" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="criteres_evaluation_projet">
            {{ ucfirst(__('PkgCreationProjet::mobilisationUa.criteres_evaluation_projet')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="criteres_evaluation_projet"
                class="form-control richText"
                
                
                
                id="criteres_evaluation_projet">
                {!! \App\Helpers\TextHelper::sanitizeTextarea(old('criteres_evaluation_projet', $itemMobilisationUa->criteres_evaluation_projet ?? '')) !!}
                </textarea>
          @error('criteres_evaluation_projet')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemMobilisationUa" field="projet_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="projet_id" 
              id="bulk_field_projet_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
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
                        {{ (isset($itemMobilisationUa) && $itemMobilisationUa->projet_id == $projet->id) || (old('projet_id>') == $projet->id) ? 'selected' : '' }}>
                        {{ $projet }}
                    </option>
                @endforeach
            </select>
          @error('projet_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('mobilisationUas.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemMobilisationUa->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgCreationProjet::mobilisationUa.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCreationProjet::mobilisationUa.singular") }} : {{$itemMobilisationUa}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
