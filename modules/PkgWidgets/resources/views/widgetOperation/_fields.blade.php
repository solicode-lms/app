{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('widgetOperations.update', $item->id) : route('widgetOperations.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="operation">
                {{ ucfirst(__('PkgWidgets::widgetOperation.operation')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="operation"
                type="input"
                class="form-control"
                id="operation"
                placeholder="{{ __('PkgWidgets::widgetOperation.operation') }}"
                value="{{ $item ? $item->operation : old('operation') }}">
            @error('operation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgWidgets::widgetOperation.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('PkgWidgets::widgetOperation.description') }}"
                value="{{ $item ? $item->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        



    </div>

    <div class="card-footer">
        <a href="{{ route('widgetOperations.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
    ];
</script>
