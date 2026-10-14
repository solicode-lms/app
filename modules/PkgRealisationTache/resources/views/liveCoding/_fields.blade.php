{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('liveCoding-form')
<form 
    class="crud-form custom-form context-state container" 
    id="liveCodingForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('liveCodings.bulkUpdate') : ($itemLiveCoding->id ? route('liveCodings.update', $itemLiveCoding->id) : route('liveCodings.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemLiveCoding->id)
        <input type="hidden" name="id" value="{{ $itemLiveCoding->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($liveCoding_ids))
        @foreach ($liveCoding_ids as $id)
            <input type="hidden" name="liveCoding_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemLiveCoding" field="realisation_tache_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemLiveCoding) && $itemLiveCoding->realisation_tache_id == $realisationTache->id) || (old('realisation_tache_id>') == $realisationTache->id) ? 'selected' : '' }}>
                        {{ $realisationTache }}
                    </option>
                @endforeach
            </select>
          @error('realisation_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLiveCoding" field="tentative" :bulkEdit="$bulkEdit">

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
            {{ ucfirst(__('PkgRealisationTache::liveCoding.tentative')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="tentative"
                type="number"
                class="form-control"
                required
                
                
                id="tentative"
                placeholder="{{ __('PkgRealisationTache::liveCoding.tentative') }}"
                value="{{ $itemLiveCoding ? $itemLiveCoding->tentative : old('tentative') }}">
          @error('tentative')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLiveCoding" field="est_valide" :bulkEdit="$bulkEdit">

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
            {{ ucfirst(__('PkgRealisationTache::liveCoding.est_valide')) }}
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
                {{ old('est_valide', $itemLiveCoding ? $itemLiveCoding->est_valide : 0) ? 'checked' : '' }}>
          @error('est_valide')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLiveCoding" field="date_passage" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="date_passage" 
              id="bulk_field_date_passage" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_passage">
            {{ ucfirst(__('PkgRealisationTache::liveCoding.date_passage')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_passage"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="date_passage"
                placeholder="{{ __('PkgRealisationTache::liveCoding.date_passage') }}"
                value="{{ $itemLiveCoding ? $itemLiveCoding->date_passage : old('date_passage') }}">

          @error('date_passage')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLiveCoding" field="commentaire" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="commentaire" 
              id="bulk_field_commentaire" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="commentaire">
            {{ ucfirst(__('PkgRealisationTache::liveCoding.commentaire')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="commentaire"
                class="form-control richText"
                
                
                
                id="commentaire">
                {!! \App\Helpers\TextHelper::sanitizeTextarea(old('commentaire', $itemLiveCoding->commentaire ?? '')) !!}
                </textarea>
          @error('commentaire')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('liveCodings.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemLiveCoding->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgRealisationTache::liveCoding.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgRealisationTache::liveCoding.singular") }} : {{$itemLiveCoding}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
