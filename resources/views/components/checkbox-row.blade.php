@props([
    'item',
    'bulkEdit' => false,
    'valueKey' => 'id',
])

@if ($bulkEdit)
    <td>
        @can('update', $item)
        <input type="checkbox" class="check-row"
            value="{{ data_get($item, $valueKey) }}"
            data-id="{{ data_get($item, $valueKey) }}">
        @endcan
    </td>
@endif

