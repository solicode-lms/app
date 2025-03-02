<div class="{{ $count == 0 ? 'col-md-1' : 'col-md-10' }}">
    <div class="row">
        <div class="d-flex align-items-center mb-3">
            <i class="fas text-info filter-icon fa-times-circle mr-3" title="Réinitialiser les filtres"></i>
        </div>
        <div class="col-11">
            <div class="row">
            {{ $slot }}
            </div>
        </div>
    </div>
</div>