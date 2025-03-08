<div class="col-md-3 mb-3">
    @switch($type)
        @case('String')
            <input type="text" 
                   id="{{ $field }}" 
                   name="{{ $field }}" 
                   class="form-control form-control-sm" 
                   value="{{ request($field) }}" 
                   placeholder="{{ $placeholder ?? ucfirst(str_replace('_', ' ', $field)) }}">
            @break

        @case('Date')
            <input type="date" 
                   id="{{ $field }}" 
                   name="{{ $field }}" 
                   class="form-control form-control-sm" 
                   value="{{ request($field) }}">
            @break

        @case('ManyToOne')
            
            <select id="filter_{{ $field }}" name="{{ $field }}" class="form-select form-control form-control-sm select2"
                data-label="{{$label}}" 
                data-target-dynamic-dropdown='{{$targetDynamicDropdown}}'
                data-target-dynamic-dropdown-api-url='{{$targetDynamicDropdownApiUrl}}' 
                >
                <option value="">{{ $label }}</option>
                @foreach ($options as $option)
                    <option value="{{ $option['id'] }}" 
                            {{ request($field) == $option['id'] ? 'selected' : '' }}>
                        {{ $option['label'] }}
                    </option>
                @endforeach
            </select>
            @break
        @case('Relation')
        
        <select id="filter_{{ $field }}" 
        data-label="{{$label}}" 
        data-target-dynamic-dropdown='{{$targetDynamicDropdown}}'
        data-target-dynamic-dropdown-api-url='{{$targetDynamicDropdownApiUrl}}' 
        name="{{ $field }}" class="form-select form-control form-control-sm select2">
            <option value="">{{ $label }}</option>
            @foreach ($options as $option)
                <option value="{{ $option['id'] }}" 
                        {{ request($field) == $option['id'] ? 'selected' : '' }}>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>
        @break
        @case('ManyToMany')
            <select id="filter_{{ $field }}"  
            name="{{ $field }}" 
            data-target-dynamic-dropdown='{{$targetDynamicDropdown}}'
            data-target-dynamic-dropdown-api-url='{{$targetDynamicDropdownApiUrl}}' 
            class="form-select form-control form-control-sm select2">
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
            <select id="{{ $field }}"  
            name="{{ $field }}" 
            data-target-dynamic-dropdown='{{$targetDynamicDropdown}}'
            data-target-dynamic-dropdown-api-url='{{$targetDynamicDropdownApiUrl}}' 
            class="form-select form-control form-control-sm select2">
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
