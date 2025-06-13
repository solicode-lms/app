{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('notification-form')
<form 
    class="crud-form custom-form context-state container" 
    id="notificationForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('notifications.bulkUpdate') : ($itemNotification->id ? route('notifications.update', $itemNotification->id) : route('notifications.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemNotification->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($notification_ids))
        @foreach ($notification_ids as $id)
            <input type="hidden" name="notification_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemNotification" field="title" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="title" id="bulk_field_title" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="title">
            {{ ucfirst(__('PkgNotification::notification.title')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="title"
                type="input"
                class="form-control"
                required
                
                
                id="title"
                placeholder="{{ __('PkgNotification::notification.title') }}"
                value="{{ $itemNotification ? $itemNotification->title : old('title') }}">
          @error('title')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemNotification" field="message" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="message" id="bulk_field_message" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="message">
            {{ ucfirst(__('PkgNotification::notification.message')) }}
            <span class="text-danger">*</span>
          </label>
                      <textarea rows="" cols=""
                name="message"
                class="form-control richText"
                required
                
                
                id="message"
                placeholder="{{ __('PkgNotification::notification.message') }}">{{ $itemNotification ? $itemNotification->message : old('message') }}</textarea>
          @error('message')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemNotification" field="sent_at" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="sent_at" id="bulk_field_sent_at" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="sent_at">
            {{ ucfirst(__('PkgNotification::notification.sent_at')) }}
            
          </label>
                      <input
                name="sent_at"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="sent_at"
                placeholder="{{ __('PkgNotification::notification.sent_at') }}"
                value="{{ $itemNotification ? $itemNotification->sent_at : old('sent_at') }}">

          @error('sent_at')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemNotification" field="is_read" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="is_read" id="bulk_field_is_read" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="is_read">
            {{ ucfirst(__('PkgNotification::notification.is_read')) }}
            <span class="text-danger">*</span>
          </label>
                      <input type="hidden" name="is_read" value="0">
            <input
                name="is_read"
                type="checkbox"
                class="form-control"
                required
                
                
                id="is_read"
                value="1"
                {{ old('is_read', $itemNotification ? $itemNotification->is_read : 0) ? 'checked' : '' }}>
          @error('is_read')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemNotification" field="user_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="user_id" id="bulk_field_user_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="user_id">
            {{ ucfirst(__('PkgAutorisation::user.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="user_id" 
            required
            
            
            name="user_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ (isset($itemNotification) && $itemNotification->user_id == $user->id) || (old('user_id>') == $user->id) ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
          @error('user_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('notifications.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNotification->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgNotification::notification.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgNotification::notification.singular") }} : {{$itemNotification}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
