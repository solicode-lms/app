{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('sysColors.update', $item->id) : route('sysColors.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('Core::sysColor.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                id="name"
                placeholder="{{ __('Core::sysColor.name') }}"
                value="{{ $item ? $item->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="hex">
                {{ ucfirst(__('Core::sysColor.hex')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="hex"
                type="input"
                class="form-control"
                id="hex"
                placeholder="{{ __('Core::sysColor.hex') }}"
                value="{{ $item ? $item->hex : old('hex') }}">
            @error('hex')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        



    </div>

    <div class="card-footer">
        <a href="{{ route('sysColors.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
    ];
</script>
