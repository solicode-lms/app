{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('sysModules.update', $item->id) : route('sysModules.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('Core::sysModule.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                id="name"
                placeholder="{{ __('Core::sysModule.name') }}"
                value="{{ $item ? $item->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="slug">
                {{ ucfirst(__('Core::sysModule.slug')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="slug"
                type="input"
                class="form-control"
                id="slug"
                placeholder="{{ __('Core::sysModule.slug') }}"
                value="{{ $item ? $item->slug : old('slug') }}">
            @error('slug')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('Core::sysModule.description')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('Core::sysModule.description') }}"
                value="{{ $item ? $item->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="is_active">
                {{ ucfirst(__('Core::sysModule.is_active')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="is_active"
                type="input"
                class="form-control"
                id="is_active"
                placeholder="{{ __('Core::sysModule.is_active') }}"
                value="{{ $item ? $item->is_active : old('is_active') }}">
            @error('is_active')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="order">
                {{ ucfirst(__('Core::sysModule.order')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="order"
                type="input"
                class="form-control"
                id="order"
                placeholder="{{ __('Core::sysModule.order') }}"
                value="{{ $item ? $item->order : old('order') }}">
            @error('order')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="version">
                {{ ucfirst(__('Core::sysModule.version')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="version"
                type="input"
                class="form-control"
                id="version"
                placeholder="{{ __('Core::sysModule.version') }}"
                value="{{ $item ? $item->version : old('version') }}">
            @error('version')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        
        <div class="form-group">
            <label for="sysColors">
                {{ ucfirst(__('Core::SysColor.plural')) }}
            </label>
            <select
                id="sysColors"
                name="sysColors[]"
                class="form-control select2"
                multiple="multiple">
                @foreach ($sysColors as $sysColor)
                    <option value="{{ $sysColor->id }}"
                        {{ (isset($item) && $item->sysColors && $item->sysColors->contains('id', $sysColor->id)) || (is_array(old('sysColors')) && in_array($sysColor->id, old('sysColors'))) ? 'selected' : '' }}>
                        {{ $sysColor->name }}
                    </option>
                @endforeach
            </select>
            @error('sysColors')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        



    </div>

    <div class="card-footer">
        <a href="{{ route('sysModules.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
    ];
</script>
