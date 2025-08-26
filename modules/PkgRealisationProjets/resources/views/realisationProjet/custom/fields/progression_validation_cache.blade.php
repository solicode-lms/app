@php
    $validation = $entity->progression_validation_cache ?? 0;
    $execution = $entity->progression_execution_cache ?? 0;
    $nonValide = max(0, $execution - $validation); // en cours mais pas validé
    $nonCommence = max(0, 100 - $execution);       // reste à faire
    $showTextThreshold = 9; // largeur min pour afficher texte %

@endphp

<div class="progress progress-sm" style="height: 0.85rem;">
    
     {{-- ✅ Validé --}}
    <div class="progress-bar bg-success" 
         style="width: {{ $validation }}%;" 
         data-toggle="tooltip"
         title="Validé ({{ $validation }}%)">
          @if($validation > $showTextThreshold)
                {{ $validation }}%
          @endif
    </div>

    {{-- ⚠️ En cours (exécuté mais non validé) --}}
    @if($nonValide > 0)
    <div class="progress-bar bg-danger" 
         style="width: {{ $nonValide }}%;" 
         data-toggle="tooltip"
         title="Exécuté non validé ({{ $nonValide }}%)">
          @if($nonValide > $showTextThreshold)
                {{ $nonValide }}%
          @endif
    </div>
    @endif

    {{-- 🕓 Non commencé --}}
   
</div>