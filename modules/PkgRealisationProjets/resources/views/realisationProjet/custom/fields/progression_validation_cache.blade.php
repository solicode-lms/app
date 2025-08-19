@php
    $validation = $entity->progression_validation_cache ?? 0;
    $execution = $entity->progression_execution_cache ?? 0;
    $nonValide = max(0, $execution - $validation); // en cours mais pas validé
    $nonCommence = max(0, 100 - $execution);       // reste à faire
@endphp

<div class="progress progress-sm" style="height: 0.5rem;">
    {{-- ✅ Validé --}}
    <div class="progress-bar bg-success" 
         style="width: {{ $validation }}%;" 
         data-toggle="tooltip"
         title="Validé ({{ $validation }}%)">
    </div>

    {{-- ⚠️ En cours (exécuté mais non validé) --}}
    @if($nonValide > 0)
    <div class="progress-bar bg-danger" 
         style="width: {{ $nonValide }}%;" 
         data-toggle="tooltip"
         title="Exécuté non validé ({{ $nonValide }}%)">
    </div>
    @endif

    {{-- 🕓 Non commencé --}}
    @if($nonCommence > 0)
    <div class="progress-bar bg-light" 
         style="width: {{ $nonCommence }}%;" 
         data-toggle="tooltip"
         title="Non commencé ({{ $nonCommence }}%)">
    </div>
    @endif
</div>

@if($validation > 0 || $nonValide> 0)
<small class="text-muted">
    <span data-toggle="tooltip" title="{{ $validation }}% validé">✔️ {{ $validation }}% </span>
    @if( $nonValide> 0)
    <span  data-toggle="tooltip" title="{{ $nonValide }}% non validé">❌ {{ $nonValide }}%</span>
    @endif
</small>
@else
<small class="text-muted">
     0% 
</small>
@endif