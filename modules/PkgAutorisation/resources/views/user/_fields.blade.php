{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('user-form')
<form 
    class="crud-form custom-form context-state container" 
    id="userForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('users.bulkUpdate') : ($itemUser->id ? route('users.update', $itemUser->id) : route('users.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemUser->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($user_ids))
        @foreach ($user_ids as $id)
            <input type="hidden" name="user_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="name" id="bulk_field_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('PkgAutorisation::user.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('PkgAutorisation::user.name') }}"
                value="{{ $itemUser ? $itemUser->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="email" id="bulk_field_email" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="email">
            {{ ucfirst(__('PkgAutorisation::user.email')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="email"
                type="input"
                class="form-control"
                required
                
                
                id="email"
                placeholder="{{ __('PkgAutorisation::user.email') }}"
                value="{{ $itemUser ? $itemUser->email : old('email') }}">
          @error('email')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="email_verified_at" id="bulk_field_email_verified_at" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="email_verified_at">
            {{ ucfirst(__('PkgAutorisation::user.email_verified_at')) }}
            
          </label>
                      <input
                name="email_verified_at"
                type="date"
                class="form-control datetimepicker"
                
                
                
                id="email_verified_at"
                placeholder="{{ __('PkgAutorisation::user.email_verified_at') }}"
                value="{{ $itemUser ? $itemUser->email_verified_at : old('email_verified_at') }}">

          @error('email_verified_at')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="password" id="bulk_field_password" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="password">
            {{ ucfirst(__('PkgAutorisation::user.password')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="password"
                type="input"
                class="form-control"
                required
                
                
                id="password"
                placeholder="{{ __('PkgAutorisation::user.password') }}"
                value="{{ $itemUser ? $itemUser->password : old('password') }}">
          @error('password')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="must_change_password" id="bulk_field_must_change_password" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="must_change_password">
            {{ ucfirst(__('PkgAutorisation::user.must_change_password')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="must_change_password" value="0">
            <input
                name="must_change_password"
                type="checkbox"
                class="form-control"
                required
                
                
                id="must_change_password"
                value="1"
                {{ old('must_change_password', $itemUser ? $itemUser->must_change_password : 0) ? 'checked' : '' }}>
          @error('must_change_password')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="remember_token" id="bulk_field_remember_token" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="remember_token">
            {{ ucfirst(__('PkgAutorisation::user.remember_token')) }}
            
          </label>
           <input
                name="remember_token"
                type="input"
                class="form-control"
                
                
                
                id="remember_token"
                placeholder="{{ __('PkgAutorisation::user.remember_token') }}"
                value="{{ $itemUser ? $itemUser->remember_token : old('remember_token') }}">
          @error('remember_token')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   Apprenant HasMany --> 


<!--   Formateur HasMany --> 


<!--   Profile HasMany --> 


<!--   WidgetUtilisateur HasMany --> 


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="roles" id="bulk_field_roles" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="roles">
            {{ ucfirst(__('PkgAutorisation::Role.plural')) }}
            
          </label>
                      <select
                id="roles"
                name="roles[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ (isset($itemUser) && $itemUser->roles && $itemUser->roles->contains('id', $role->id)) || (is_array(old('roles')) && in_array($role->id, old('roles'))) ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
          @error('roles')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('users.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemUser->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgAutorisation::user.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgAutorisation::user.singular") }} : {{$itemUser}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
