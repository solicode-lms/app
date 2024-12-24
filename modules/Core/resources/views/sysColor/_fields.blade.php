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
        

        

        
        <div class="form-group">
            <label for="sysModules">
                {{ ucfirst(__('Core::SysModule.plural')) }}
            </label>
            <select
                id="sysModules"
                name="sysModules[]"
                class="form-control select2"
                multiple="multiple">
                @foreach ($sysModules as $sysModule)
                    <option value="{{ $sysModule->id }}"
                        {{ (isset($item) && $item->sysModules && $item->sysModules->contains('id', $sysModule->id)) || (is_array(old('sysModules')) && in_array($sysModule->id, old('sysModules'))) ? 'selected' : '' }}>
                        {{ $sysModule->name }}
                    </option>
                @endforeach
            </select>
            @error('sysModules')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        
        <div class="form-group">
            <label for="sysModels">
                {{ ucfirst(__('Core::SysModel.plural')) }}
            </label>
            <select
                id="sysModels"
                name="sysModels[]"
                class="form-control select2"
                multiple="multiple">
                @foreach ($sysModels as $sysModel)
                    <option value="{{ $sysModel->id }}"
                        {{ (isset($item) && $item->sysModels && $item->sysModels->contains('id', $sysModel->id)) || (is_array(old('sysModels')) && in_array($sysModel->id, old('sysModels'))) ? 'selected' : '' }}>
                        {{ $sysModel->name }}
                    </option>
                @endforeach
            </select>
            @error('sysModels')
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
