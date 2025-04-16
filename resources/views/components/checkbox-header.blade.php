@props(['bulkEdit' => false])

@if ($bulkEdit)
    <th style="width: 10px;">
        <input type="checkbox" class="check-all-rows" />
    </th>
@endif
