{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form id="categorieTechnologyForm" action="{{ $itemCategorieTechnology->id ? route('categorieTechnologies.update', $itemCategorieTechnology->id) : route('categorieTechnologies.store') }}" method="POST">
    @csrf

    @if ($itemCategorieTechnology->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgCompetences::categorieTechnology.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                id="nom"
                placeholder="{{ __('PkgCompetences::categorieTechnology.nom') }}"
                value="{{ $itemCategorieTechnology ? $itemCategorieTechnology->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCompetences::categorieTechnology.description')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('PkgCompetences::categorieTechnology.description') }}"
                value="{{ $itemCategorieTechnology ? $itemCategorieTechnology->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        



    </div>


    <div class="card-footer">
        <a href="{{ route('categorieTechnologies.index') }}" id="categorieTechnology_form_cancel" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemCategorieTechnology->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
    ];
</script>
