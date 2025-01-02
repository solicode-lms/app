{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form context-state" id="widgetOperationForm" action="{{ $itemWidgetOperation->id ? route('widgetOperations.update', $itemWidgetOperation->id) : route('widgetOperations.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemWidgetOperation->id)
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
                required
                id="operation"
                placeholder="{{ __('PkgWidgets::widgetOperation.operation') }}"
                value="{{ $itemWidgetOperation ? $itemWidgetOperation->operation : old('operation') }}">
            @error('operation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgWidgets::widgetOperation.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgWidgets::widgetOperation.description') }}">
                {{ $itemWidgetOperation ? $itemWidgetOperation->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>


        <!--   Widget_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('widgetOperations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemWidgetOperation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


