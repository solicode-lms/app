@php
    $bareme = floatval($entity->bareme_cache) > 0 ? floatval($entity->bareme_cache) : 1;
    $note = floatval($entity->note_cache);
    $pourcentage = round(($note / $bareme) * 100, 2);
    $noteSur40 = round(($note / $bareme) * 40, 2);
    
    if ($pourcentage >= 75) {
        $colorClass = 'success';
    } elseif ($pourcentage >= 50) {
        $colorClass = 'warning';
    } else {
        $colorClass = 'danger';
    }
@endphp

<div class="d-flex flex-column align-items-center" style="min-width: 140px;">
    <div class="mb-2 w-100 d-flex flex-column align-items-center">
        <span class="badge badge-primary px-2 py-1 mb-1" title="Note originale">{{ $note }} / {{ $entity->bareme_cache }}</span>
        <span class="badge badge-info px-2 py-1" title="Note sur 40">{{ $noteSur40 }} / 40</span>
    </div>
    
    <small class="text-{{ $colorClass }} font-weight-bold">{{ $pourcentage }}%</small>
</div>