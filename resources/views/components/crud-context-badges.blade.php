@props(['scopeVariables' => []])

@if(!empty($scopeVariables))
<div class="context-badges d-flex align-items-center flex-wrap mt-2">
    <h5 class="mb-0 mr-3"><i class="fas fa-filter text-secondary"></i></h5>
    <div class="context-badges-items">
    @foreach($scopeVariables as $key => $value)
        <span class="badge badge-secondary mr-2 p-1" style="margin-bottom: 4px" title="Filtre actif">
            {{ __($key) }} : {{ $value }}
        </span>
    @endforeach
    </div>
</div>
@endif
