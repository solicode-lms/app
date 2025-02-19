{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysColor-form')
<form class="crud-form custom-form context-state container" id="sysColorForm" action="{{ $itemSysColor->id ? route('sysColors.update', $itemSysColor->id) : route('sysColors.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemSysColor->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
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

        

        <!--   SysModel HasMany --> 

        

        <!--   SysModule HasMany --> 

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
     window.modalTitle = '{{__("Core::sysColor.singular") }} : {{$itemSysColor}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
