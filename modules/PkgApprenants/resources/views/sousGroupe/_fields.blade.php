{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sousGroupe-form')
<form 
    class="crud-form custom-form context-state container" 
    id="sousGroupeForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('sousGroupes.bulkUpdate') : ($itemSousGroupe->id ? route('sousGroupes.update', $itemSousGroupe->id) : route('sousGroupes.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemSousGroupe->id)
        <input type="hidden" name="id" value="{{ $itemSousGroupe->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($sousGroupe_ids))
        @foreach ($sousGroupe_ids as $id)
            <input type="hidden" name="sousGroupe_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemSousGroupe" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgApprenants::sousGroupe.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgApprenants::sousGroupe.nom') }}"
                value="{{ $itemSousGroupe ? $itemSousGroupe->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSousGroupe" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgApprenants::sousGroupe.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgApprenants::sousGroupe.description') }}">{{ $itemSousGroupe ? $itemSousGroupe->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSousGroupe" field="groupe_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="groupe_id" id="bulk_field_groupe_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="groupe_id">
            {{ ucfirst(__('PkgApprenants::groupe.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="groupe_id" 
            data-target-dynamic-dropdown='#apprenants'
            data-target-dynamic-dropdown-api-url='{{route('apprenants.getData')}}'
            data-target-dynamic-dropdown-filter='groupes.id'
            required
            
            
            name="groupe_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($groupes as $groupe)
                    <option value="{{ $groupe->id }}"
                        {{ (isset($itemSousGroupe) && $itemSousGroupe->groupe_id == $groupe->id) || (old('groupe_id>') == $groupe->id) ? 'selected' : '' }}>
                        {{ $groupe }}
                    </option>
                @endforeach
            </select>
          @error('groupe_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemSousGroupe" field="apprenants" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="apprenants" id="bulk_field_apprenants" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="apprenants">
            {{ ucfirst(__('PkgApprenants::apprenant.plural')) }}
            
          </label>
                      <select
                id="apprenants"
                name="apprenants[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($apprenants as $apprenant)
                    <option value="{{ $apprenant->id }}"
                        {{ (isset($itemSousGroupe) && $itemSousGroupe->apprenants && $itemSousGroupe->apprenants->contains('id', $apprenant->id)) || (is_array(old('apprenants')) && in_array($apprenant->id, old('apprenants'))) ? 'selected' : '' }}>
                        {{ $apprenant }}
                    </option>
                @endforeach
            </select>
          @error('apprenants')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('sousGroupes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSousGroupe->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprenants::sousGroupe.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprenants::sousGroupe.singular") }} : {{$itemSousGroupe}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
