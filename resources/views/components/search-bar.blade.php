<div class="input-group input-group-sm float-sm-right p-0">
    <input
        type="text"
        value="{{ $search ?? '' }}"
        name="{{ $name ?? 'search_input' }}"
        id="{{ $id ?? 'search_input' }}"
        class="form-control float-right crud-search-input"
        placeholder="{{ $placeholder ?? 'Recherche' }}"
    >
    <div class="input-group-append">
        <button type="submit" class="btn btn-default">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>