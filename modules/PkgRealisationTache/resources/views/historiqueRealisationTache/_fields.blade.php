{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('historiqueRealisationTache-form')
<form 
    class="crud-form custom-form context-state container" 
    id="historiqueRealisationTacheForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('historiqueRealisationTaches.bulkUpdate') : ($itemHistoriqueRealisationTache->id ? route('historiqueRealisationTaches.update', $itemHistoriqueRealisationTache->id) : route('historiqueRealisationTaches.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemHistoriqueRealisationTache->id)
        <input type="hidden" name="id" value="{{ $itemHistoriqueRealisationTache->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($historiqueRealisationTache_ids))
        @foreach ($historiqueRealisationTache_ids as $id)
            <input type="hidden" name="historiqueRealisationTache_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemHistoriqueRealisationTache" field="changement" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="changement" 
              id="bulk_field_changement" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="changement">
            {{ ucfirst(__('PkgRealisationTache::historiqueRealisationTache.changement')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="changement"
                class="form-control richText"
                required
                
                
                id="changement"
                placeholder="{{ __('PkgRealisationTache::historiqueRealisationTache.changement') }}">{{ $itemHistoriqueRealisationTache ? $itemHistoriqueRealisationTache->changement : old('changement') }}</textarea>
          @error('changement')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemHistoriqueRealisationTache" field="dateModification" :bulkEdit="$bulkEdit">
      @php $canEditdateModification = $bulkEdit ? Auth::user()->hasAnyRole(explode(',', 'admin')) : (empty($itemHistoriqueRealisationTache->id) || Auth::user()->hasAnyRole(explode(',', 'admin')) ); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
                {{ $canEditdateModification ? '' : 'disabled' }}
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="dateModification" 
              id="bulk_field_dateModification" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dateModification">
            {{ ucfirst(__('PkgRealisationTache::historiqueRealisationTache.dateModification')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="dateModification"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="dateModification"
                {{ $canEditdateModification ? '' : 'disabled' }}
                placeholder="{{ __('PkgRealisationTache::historiqueRealisationTache.dateModification') }}"
                value="{{ $itemHistoriqueRealisationTache ? $itemHistoriqueRealisationTache->dateModification : old('dateModification') }}">

          @error('dateModification')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemHistoriqueRealisationTache" field="realisation_tache_id" :bulkEdit="$bulkEdit">

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
                        {{ (isset($itemHistoriqueRealisationTache) && $itemHistoriqueRealisationTache->realisation_tache_id == $realisationTache->id) || (old('realisation_tache_id>') == $realisationTache->id) ? 'selected' : '' }}>
                        {{ $realisationTache }}
                    </option>
                @endforeach
            </select>
          @error('realisation_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemHistoriqueRealisationTache" field="user_id" :bulkEdit="$bulkEdit">
      @php $canEdituser_id = $bulkEdit ? Auth::user()->hasAnyRole(explode(',', 'admin')) : (empty($itemHistoriqueRealisationTache->id) || Auth::user()->hasAnyRole(explode(',', 'admin')) ); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
                {{ $canEdituser_id ? '' : 'disabled' }}
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="user_id" 
              id="bulk_field_user_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="user_id">
            {{ ucfirst(__('PkgAutorisation::user.singular')) }}
            
          </label>
                      <select 
            id="user_id" 
            {{ $canEdituser_id ? '' : 'disabled' }}
            
            
            
            name="user_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ (isset($itemHistoriqueRealisationTache) && $itemHistoriqueRealisationTache->user_id == $user->id) || (old('user_id>') == $user->id) ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
          @error('user_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemHistoriqueRealisationTache" field="isFeedback" :bulkEdit="$bulkEdit">
      @php $canEditisFeedback = $bulkEdit ? Auth::user()->hasAnyRole(explode(',', 'admin')) : (empty($itemHistoriqueRealisationTache->id) || Auth::user()->hasAnyRole(explode(',', 'admin')) ); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
                {{ $canEditisFeedback ? '' : 'disabled' }}
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="isFeedback" 
              id="bulk_field_isFeedback" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="isFeedback">
            {{ ucfirst(__('PkgRealisationTache::historiqueRealisationTache.isFeedback')) }}
            
          </label>
                      <input type="hidden" name="isFeedback" value="0">
            <input
                name="isFeedback"
                type="checkbox"
                class="form-control d-block"
                
                
                
                id="isFeedback"
                {{ $canEditisFeedback ? '' : 'disabled' }}
                value="1"
                {{ old('isFeedback', $itemHistoriqueRealisationTache ? $itemHistoriqueRealisationTache->isFeedback : 0) ? 'checked' : '' }}>
          @error('isFeedback')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('historiqueRealisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemHistoriqueRealisationTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgRealisationTache::historiqueRealisationTache.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgRealisationTache::historiqueRealisationTache.singular") }} : {{$itemHistoriqueRealisationTache}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
