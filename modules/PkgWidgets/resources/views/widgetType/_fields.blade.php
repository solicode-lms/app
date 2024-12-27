{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form id="widgetTypeForm" action="{{ $itemWidgetType->id ? route('widgetTypes.update', $itemWidgetType->id) : route('widgetTypes.store') }}" method="POST">
    @csrf

    @if ($itemWidgetType->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="type">
                {{ ucfirst(__('PkgWidgets::widgetType.type')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="type"
                type="input"
                class="form-control"
                id="type"
                placeholder="{{ __('PkgWidgets::widgetType.type') }}"
                value="{{ $itemWidgetType ? $itemWidgetType->type : old('type') }}">
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgWidgets::widgetType.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('PkgWidgets::widgetType.description') }}"
                value="{{ $itemWidgetType ? $itemWidgetType->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        



    </div>


    <div class="card-footer">
        <a href="{{ route('widgetTypes.index') }}" id="widgetType_form_cancel" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemWidgetType->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
    ];
</script>
