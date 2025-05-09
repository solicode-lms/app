{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysColor-form')
<form 
    class="crud-form custom-form context-state container" 
    id="sysColorForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('sysColors.bulkUpdate') : ($itemSysColor->id ? route('sysColors.update', $itemSysColor->id) : route('sysColors.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemSysColor->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($sysColor_ids))
        @foreach ($sysColor_ids as $id)
            <input type="hidden" name="sysColor_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        
      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="name" id="bulk_field_name" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="name">
            {{ ucfirst(__('Core::sysColor.name')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="name"
                type="input"
                class="form-control"
                required
                
                
                id="name"
                placeholder="{{ __('Core::sysColor.name') }}"
                value="{{ $itemSysColor ? $itemSysColor->name : old('name') }}">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="hex" id="bulk_field_hex" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="hex">
            {{ ucfirst(__('Core::sysColor.hex')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="hex"
                type="input"
                class="form-control"
                required
                
                
                id="hex"
                placeholder="{{ __('Core::sysColor.hex') }}"
                value="{{ $itemSysColor ? $itemSysColor->hex : old('hex') }}">
          @error('hex')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('sysColors.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSysColor->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("Core::sysColor.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("Core::sysColor.singular") }} : {{$itemSysColor}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
