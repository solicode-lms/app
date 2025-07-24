{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationUaProjet-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationUaProjetForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationUaProjets.bulkUpdate') : ($itemRealisationUaProjet->id ? route('realisationUaProjets.update', $itemRealisationUaProjet->id) : route('realisationUaProjets.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationUaProjet->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($realisationUaProjet_ids))
        @foreach ($realisationUaProjet_ids as $id)
            <input type="hidden" name="realisationUaProjet_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemRealisationUaProjet" field="note" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note" id="bulk_field_note" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note">
            {{ ucfirst(__('PkgApprentissage::realisationUaProjet.note')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="note"
        type="number"
        class="form-control"
        required
        
        
        id="note"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationUaProjet.note') }}"
        value="{{ $itemRealisationUaProjet ? number_format($itemRealisationUaProjet->note, 2, '.', '') : old('note') }}">
          @error('note')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaProjet" field="bareme" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="bareme" id="bulk_field_bareme" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="bareme">
            {{ ucfirst(__('PkgApprentissage::realisationUaProjet.bareme')) }}
            <span class="text-danger">*</span>
          </label>
              <input
        name="bareme"
        type="number"
        class="form-control"
        required
        
        
        id="bareme"
        step="0.01"
        placeholder="{{ __('PkgApprentissage::realisationUaProjet.bareme') }}"
        value="{{ $itemRealisationUaProjet ? number_format($itemRealisationUaProjet->bareme, 2, '.', '') : old('bareme') }}">
          @error('bareme')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaProjet" field="remarque_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="remarque_formateur" id="bulk_field_remarque_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="remarque_formateur">
            {{ ucfirst(__('PkgApprentissage::realisationUaProjet.remarque_formateur')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="remarque_formateur"
                class="form-control richText"
                
                
                
                id="remarque_formateur"
                placeholder="{{ __('PkgApprentissage::realisationUaProjet.remarque_formateur') }}">{{ $itemRealisationUaProjet ? $itemRealisationUaProjet->remarque_formateur : old('remarque_formateur') }}</textarea>
          @error('remarque_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaProjet" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgApprentissage::realisationUaProjet.date_debut')) }}
            
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_debut"
                placeholder="{{ __('PkgApprentissage::realisationUaProjet.date_debut') }}"
                value="{{ $itemRealisationUaProjet ? $itemRealisationUaProjet->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaProjet" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgApprentissage::realisationUaProjet.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgApprentissage::realisationUaProjet.date_fin') }}"
                value="{{ $itemRealisationUaProjet ? $itemRealisationUaProjet->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaProjet" field="realisation_ua_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemRealisationUaProjet) && $itemRealisationUaProjet->realisation_ua_id == $realisationUa->id) || (old('realisation_ua_id>') == $realisationUa->id) ? 'selected' : '' }}>
                        {{ $realisationUa }}
                    </option>
                @endforeach
            </select>
          @error('realisation_ua_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationUaProjet" field="realisation_tache_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemRealisationUaProjet) && $itemRealisationUaProjet->realisation_tache_id == $realisationTache->id) || (old('realisation_tache_id>') == $realisationTache->id) ? 'selected' : '' }}>
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
        <a href="{{ route('realisationUaProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationUaProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprentissage::realisationUaProjet.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprentissage::realisationUaProjet.singular") }} : {{$itemRealisationUaProjet}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
