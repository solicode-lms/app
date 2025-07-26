{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('featureDomain-form')
<form 
    class="crud-form custom-form context-state container" 
    id="featureDomainForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('featureDomains.bulkUpdate') : ($itemFeatureDomain->id ? route('featureDomains.update', $itemFeatureDomain->id) : route('featureDomains.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemFeatureDomain->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($featureDomain_ids))
        @foreach ($featureDomain_ids as $id)
            <input type="hidden" name="featureDomain_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemFeatureDomain" field="name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="name" id="bulk_field_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('Core::featureDomain.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('Core::featureDomain.name') }}"
                value="{{ $itemFeatureDomain ? $itemFeatureDomain->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemFeatureDomain" field="slug" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="slug" id="bulk_field_slug" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="slug">
            {{ ucfirst(__('Core::featureDomain.slug')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="slug"
                type="input"
                class="form-control"
                required
                
                
                id="slug"
                placeholder="{{ __('Core::featureDomain.slug') }}"
                value="{{ $itemFeatureDomain ? $itemFeatureDomain->slug : old('slug') }}">
          @error('slug')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemFeatureDomain" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('Core::featureDomain.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('Core::featureDomain.description') }}">{{ $itemFeatureDomain ? $itemFeatureDomain->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemFeatureDomain" field="sys_module_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sys_module_id" id="bulk_field_sys_module_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="sys_module_id">
            {{ ucfirst(__('Core::sysModule.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="sys_module_id" 
            required
            
            
            name="sys_module_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysModules as $sysModule)
                    <option value="{{ $sysModule->id }}"
                        {{ (isset($itemFeatureDomain) && $itemFeatureDomain->sys_module_id == $sysModule->id) || (old('sys_module_id>') == $sysModule->id) ? 'selected' : '' }}>
                        {{ $sysModule }}
                    </option>
                @endforeach
            </select>
          @error('sys_module_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('featureDomains.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFeatureDomain->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("Core::featureDomain.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("Core::featureDomain.singular") }} : {{$itemFeatureDomain}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
