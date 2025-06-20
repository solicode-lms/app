{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('permission-form')
<form 
    class="crud-form custom-form context-state container" 
    id="permissionForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('permissions.bulkUpdate') : ($itemPermission->id ? route('permissions.update', $itemPermission->id) : route('permissions.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemPermission->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($permission_ids))
        @foreach ($permission_ids as $id)
            <input type="hidden" name="permission_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemPermission" field="name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="name" id="bulk_field_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('PkgAutorisation::permission.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('PkgAutorisation::permission.name') }}"
                value="{{ $itemPermission ? $itemPermission->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemPermission" field="guard_name" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="guard_name" id="bulk_field_guard_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="guard_name">
            {{ ucfirst(__('PkgAutorisation::permission.guard_name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="guard_name"
                type="input"
                class="form-control"
                required
                
                
                id="guard_name"
                placeholder="{{ __('PkgAutorisation::permission.guard_name') }}"
                value="{{ $itemPermission ? $itemPermission->guard_name : old('guard_name') }}">
          @error('guard_name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemPermission" field="controller_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="controller_id" id="bulk_field_controller_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="controller_id">
            {{ ucfirst(__('Core::sysController.singular')) }}
            
          </label>
                      <select 
            id="controller_id" 
            
            
            
            name="controller_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sysControllers as $sysController)
                    <option value="{{ $sysController->id }}"
                        {{ (isset($itemPermission) && $itemPermission->controller_id == $sysController->id) || (old('controller_id>') == $sysController->id) ? 'selected' : '' }}>
                        {{ $sysController }}
                    </option>
                @endforeach
            </select>
          @error('controller_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemPermission" field="features" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="features" id="bulk_field_features" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="features">
            {{ ucfirst(__('Core::feature.plural')) }}
            
          </label>
                      <select
                id="features"
                name="features[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($features as $feature)
                    <option value="{{ $feature->id }}"
                        {{ (isset($itemPermission) && $itemPermission->features && $itemPermission->features->contains('id', $feature->id)) || (is_array(old('features')) && in_array($feature->id, old('features'))) ? 'selected' : '' }}>
                        {{ $feature }}
                    </option>
                @endforeach
            </select>
          @error('features')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemPermission" field="roles" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="roles" id="bulk_field_roles" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="roles">
            {{ ucfirst(__('PkgAutorisation::role.plural')) }}
            
          </label>
                      <select
                id="roles"
                name="roles[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ (isset($itemPermission) && $itemPermission->roles && $itemPermission->roles->contains('id', $role->id)) || (is_array(old('roles')) && in_array($role->id, old('roles'))) ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
          @error('roles')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('permissions.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemPermission->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgAutorisation::permission.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgAutorisation::permission.singular") }} : {{$itemPermission}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
