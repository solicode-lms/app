{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('feature-form')
<form 
    class="crud-form custom-form context-state container" 
    id="featureForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('features.bulkUpdate') : ($itemFeature->id ? route('features.update', $itemFeature->id) : route('features.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemFeature->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($feature_ids))
        @foreach ($feature_ids as $id)
            <input type="hidden" name="feature_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemFeature" field="name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="name" id="bulk_field_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('Core::feature.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('Core::feature.name') }}"
                value="{{ $itemFeature ? $itemFeature->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemFeature" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('Core::feature.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('Core::feature.description') }}">{{ $itemFeature ? $itemFeature->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemFeature" field="feature_domain_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="feature_domain_id" id="bulk_field_feature_domain_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="feature_domain_id">
            {{ ucfirst(__('Core::featureDomain.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="feature_domain_id" 
            required
            
            
            name="feature_domain_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($featureDomains as $featureDomain)
                    <option value="{{ $featureDomain->id }}"
                        {{ (isset($itemFeature) && $itemFeature->feature_domain_id == $featureDomain->id) || (old('feature_domain_id>') == $featureDomain->id) ? 'selected' : '' }}>
                        {{ $featureDomain }}
                    </option>
                @endforeach
            </select>
          @error('feature_domain_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemFeature" field="permissions" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="permissions" id="bulk_field_permissions" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="permissions">
            {{ ucfirst(__('PkgAutorisation::permission.plural')) }}
            
          </label>
                      <select
                id="permissions"
                name="permissions[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($permissions as $permission)
                    <option value="{{ $permission->id }}"
                        {{ (isset($itemFeature) && $itemFeature->permissions && $itemFeature->permissions->contains('id', $permission->id)) || (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'selected' : '' }}>
                        {{ $permission }}
                    </option>
                @endforeach
            </select>
          @error('permissions')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('features.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFeature->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("Core::feature.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("Core::feature.singular") }} : {{$itemFeature}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
