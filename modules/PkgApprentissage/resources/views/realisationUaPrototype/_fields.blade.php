{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationUaPrototype-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationUaPrototypeForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationUaPrototypes.bulkUpdate') : ($itemRealisationUaPrototype->id ? route('realisationUaPrototypes.update', $itemRealisationUaPrototype->id) : route('realisationUaPrototypes.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationUaPrototype->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($realisationUaPrototype_ids))
        @foreach ($realisationUaPrototype_ids as $id)
            <input type="hidden" name="realisationUaPrototype_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemRealisationUaPrototype" field="note" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note" id="bulk_field_note" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note">
            {{ ucfirst(__('PkgApprentissage::realisationUaPrototype.note')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="note"
        type="number"
        class="form-control"
        required
        
        
        id="note"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationUaPrototype.note') }}"
        value="{{ $itemRealisationUaPrototype ? number_format($itemRealisationUaPrototype->note, 2, '.', '') : old('note') }}">
          @error('note')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaPrototype" field="bareme" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="bareme" id="bulk_field_bareme" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="bareme">
            {{ ucfirst(__('PkgApprentissage::realisationUaPrototype.bareme')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="bareme"
        type="number"
        class="form-control"
        required
        
        
        id="bareme"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationUaPrototype.bareme') }}"
        value="{{ $itemRealisationUaPrototype ? number_format($itemRealisationUaPrototype->bareme, 2, '.', '') : old('bareme') }}">
          @error('bareme')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaPrototype" field="remarque_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="remarque_formateur" id="bulk_field_remarque_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="remarque_formateur">
            {{ ucfirst(__('PkgApprentissage::realisationUaPrototype.remarque_formateur')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="remarque_formateur"
                class="form-control richText"
                
                
                
                id="remarque_formateur"
                placeholder="{{ __('PkgApprentissage::realisationUaPrototype.remarque_formateur') }}">{{ $itemRealisationUaPrototype ? $itemRealisationUaPrototype->remarque_formateur : old('remarque_formateur') }}</textarea>
          @error('remarque_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaPrototype" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgApprentissage::realisationUaPrototype.date_debut')) }}
            
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_debut"
                placeholder="{{ __('PkgApprentissage::realisationUaPrototype.date_debut') }}"
                value="{{ $itemRealisationUaPrototype ? $itemRealisationUaPrototype->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaPrototype" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgApprentissage::realisationUaPrototype.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgApprentissage::realisationUaPrototype.date_fin') }}"
                value="{{ $itemRealisationUaPrototype ? $itemRealisationUaPrototype->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaPrototype" field="realisation_ua_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_ua_id" id="bulk_field_realisation_ua_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_ua_id">
            {{ ucfirst(__('PkgApprentissage::realisationUa.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_ua_id" 
            required
            
            
            name="realisation_ua_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationUas as $realisationUa)
                    <option value="{{ $realisationUa->id }}"
                        {{ (isset($itemRealisationUaPrototype) && $itemRealisationUaPrototype->realisation_ua_id == $realisationUa->id) || (old('realisation_ua_id>') == $realisationUa->id) ? 'selected' : '' }}>
                        {{ $realisationUa }}
                    </option>
                @endforeach
            </select>
          @error('realisation_ua_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaPrototype" field="realisation_tache_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_tache_id" id="bulk_field_realisation_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
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
                        {{ (isset($itemRealisationUaPrototype) && $itemRealisationUaPrototype->realisation_tache_id == $realisationTache->id) || (old('realisation_tache_id>') == $realisationTache->id) ? 'selected' : '' }}>
                        {{ $realisationTache }}
                    </option>
                @endforeach
            </select>
          @error('realisation_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('realisationUaPrototypes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationUaPrototype->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprentissage::realisationUaPrototype.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprentissage::realisationUaPrototype.singular") }} : {{$itemRealisationUaPrototype}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
