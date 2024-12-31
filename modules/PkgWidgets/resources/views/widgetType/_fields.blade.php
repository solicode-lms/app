{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="widgetTypeForm" action="{{ $itemWidgetType->id ? route('widgetTypes.update', $itemWidgetType->id) : route('widgetTypes.store') }}" method="POST" novalidate>
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
                required
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
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgWidgets::widgetType.description') }}">
                {{ $itemWidgetType ? $itemWidgetType->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>


        <!--   Widget_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('widgetTypes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemWidgetType->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


