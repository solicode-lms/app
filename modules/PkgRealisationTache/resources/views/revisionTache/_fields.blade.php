{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('revisionTache-form')
<form 
    class="crud-form custom-form context-state container" 
    id="revisionTacheForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('revisionTaches.bulkUpdate') : ($itemRevisionTache->id ? route('revisionTaches.update', $itemRevisionTache->id) : route('revisionTaches.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRevisionTache->id)
        <input type="hidden" name="id" value="{{ $itemRevisionTache->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($revisionTache_ids))
        @foreach ($revisionTache_ids as $id)
            <input type="hidden" name="revisionTache_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemRevisionTache" field="realisation_tache_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemRevisionTache) && $itemRevisionTache->realisation_tache_id == $realisationTache->id) || (old('realisation_tache_id>') == $realisationTache->id) ? 'selected' : '' }}>
                        {{ $realisationTache }}
                    </option>
                @endforeach
            </select>
          @error('realisation_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRevisionTache" field="tentative" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="tentative" 
              id="bulk_field_tentative" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="tentative">
            {{ ucfirst(__('PkgRealisationTache::revisionTache.tentative')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="tentative"
                type="number"
                class="form-control"
                required
                
                
                id="tentative"
                placeholder="{{ __('PkgRealisationTache::revisionTache.tentative') }}"
                value="{{ $itemRevisionTache ? $itemRevisionTache->tentative : old('tentative') }}">
          @error('tentative')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRevisionTache" field="date_demande" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_demande" 
              id="bulk_field_date_demande" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_demande">
            {{ ucfirst(__('PkgRealisationTache::revisionTache.date_demande')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_demande"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="date_demande"
                placeholder="{{ __('PkgRealisationTache::revisionTache.date_demande') }}"
                value="{{ $itemRevisionTache ? $itemRevisionTache->date_demande : old('date_demande') }}">

          @error('date_demande')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRevisionTache" field="message_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="message_formateur" 
              id="bulk_field_message_formateur" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="message_formateur">
            {{ ucfirst(__('PkgRealisationTache::revisionTache.message_formateur')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="message_formateur"
                class="form-control richText"
                
                
                
                id="message_formateur">
                {!! \App\Helpers\TextHelper::sanitizeTextarea(old('message_formateur', $itemRevisionTache->message_formateur ?? '')) !!}
                </textarea>
          @error('message_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRevisionTache" field="date_soumission" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_soumission" 
              id="bulk_field_date_soumission" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_soumission">
            {{ ucfirst(__('PkgRealisationTache::revisionTache.date_soumission')) }}
            
          </label>
                      <input
                name="date_soumission"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_soumission"
                placeholder="{{ __('PkgRealisationTache::revisionTache.date_soumission') }}"
                value="{{ $itemRevisionTache ? $itemRevisionTache->date_soumission : old('date_soumission') }}">

          @error('date_soumission')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRevisionTache" field="date_validation" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_validation" 
              id="bulk_field_date_validation" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_validation">
            {{ ucfirst(__('PkgRealisationTache::revisionTache.date_validation')) }}
            
          </label>
                      <input
                name="date_validation"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_validation"
                placeholder="{{ __('PkgRealisationTache::revisionTache.date_validation') }}"
                value="{{ $itemRevisionTache ? $itemRevisionTache->date_validation : old('date_validation') }}">

          @error('date_validation')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemRevisionTache" field="est_valide" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="est_valide" 
              id="bulk_field_est_valide" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="est_valide">
            {{ ucfirst(__('PkgRealisationTache::revisionTache.est_valide')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="est_valide" value="0">
            <input
                name="est_valide"
                type="checkbox"
                class="form-control d-block"
                required
                
                
                id="est_valide"
                value="1"
                {{ old('est_valide', $itemRevisionTache ? $itemRevisionTache->est_valide : 0) ? 'checked' : '' }}>
          @error('est_valide')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('revisionTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRevisionTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgRealisationTache::revisionTache.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgRealisationTache::revisionTache.singular") }} : {{$itemRevisionTache}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
