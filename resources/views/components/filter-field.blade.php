<div class="col-md-3 mb-3">
    @switch($type)
        @case('String')
            <input type="text" 
                   name="{{ $field }}" 
                   class="form-control form-control-sm" 
                   value="{{ request($field) }}" 
                   placeholder="{{ $placeholder ?? ucfirst(str_replace('_', ' ', $field)) }}">
            @break

        @case('Date')
            <input type="date" 
                   name="{{ $field }}" 
                   class="form-control form-control-sm" 
                   value="{{ request($field) }}">
            @break

        @case('ManyToOne')
            <select name="{{ $field }}" class="form-select form-control form-control-sm select2">
                <option value="">{{ $label }}</option>
                @foreach ($options as $option)
                    <option value="{{ $option['id'] }}" 
                            {{ request($field) == $option['id'] ? 'selected' : '' }}>
                        {{ $option['label'] }}
                    </option>
                @endforeach
            </select>
            @break
        @case('Polymorphic')
            <select name="{{ $field }}" class="form-select form-control form-control-sm select2">
                <option value="">{{ $label }}</option>
                @foreach ($options as $option)
                    <option value="{{ $option['id'] }}" 
                            {{ request($field) == $option['id'] ? 'selected' : '' }}>
                        {{ $option['label'] }}
                    </option>
                @endforeach
            </select>
            @break
    @endswitch
</div>
