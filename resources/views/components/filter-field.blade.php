
<div class="filter-field col-md-3 mb-1 mt-1">
    @switch($type)
        @case('String')
            <input type="text" 
                   id="{{ $field }}" 
                   name="{{ $field }}" 
                   class="form-control form-control-sm" 
                   value="{{ request($field) }}" 
                   placeholder="{{ $placeholder ?? ucfirst(str_replace('_', ' ', $field)) }}">
            @break
            @case('Boolean')
            <select id="{{ $field }}" name="{{ $field }}"  data-label="{{$label}}"   class="form-select form-control form-control-sm select2" >
                <option value="">-- Tous --</option>
                <option value="1" {{ request($field) === '1' ? 'selected' : '' }}>Oui</option>
                <option value="0" {{ request($field) === '0' ? 'selected' : '' }}>Non</option>
            </select>
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
                @if(!empty($targetDynamicDropdown)) data-target-dynamic-dropdown="{{ $targetDynamicDropdown }}" @endif
                @if(!empty($targetDynamicDropdownApiUrl)) data-target-dynamic-dropdown-api-url='{{$targetDynamicDropdownApiUrl}}' @endif
                @if(!empty($targetDynamicDropdownFilter)) data-target-dynamic-dropdown-filter='{{$targetDynamicDropdownFilter}}' @endif >
                


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
        @if(!empty($targetDynamicDropdown)) data-target-dynamic-dropdown="{{ $targetDynamicDropdown }}" @endif
        @if(!empty($targetDynamicDropdownApiUrl)) data-target-dynamic-dropdown-api-url='{{$targetDynamicDropdownApiUrl}}' @endif
        @if(!empty($targetDynamicDropdownFilter)) data-target-dynamic-dropdown-filter='{{$targetDynamicDropdownFilter}}' @endif
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
            @if(!empty($targetDynamicDropdown)) data-target-dynamic-dropdown="{{ $targetDynamicDropdown }}" @endif
            @if(!empty($targetDynamicDropdownApiUrl)) data-target-dynamic-dropdown-api-url='{{$targetDynamicDropdownApiUrl}}' @endif
            @if(!empty($targetDynamicDropdownFilter)) data-target-dynamic-dropdown-filter='{{$targetDynamicDropdownFilter}}' @endif
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
            @if(!empty($targetDynamicDropdown)) data-target-dynamic-dropdown="{{ $targetDynamicDropdown }}" @endif
            @if(!empty($targetDynamicDropdownApiUrl)) data-target-dynamic-dropdown-api-url='{{$targetDynamicDropdownApiUrl}}' @endif
            @if(!empty($targetDynamicDropdownFilter)) data-target-dynamic-dropdown-filter='{{$targetDynamicDropdownFilter}}' @endif
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
