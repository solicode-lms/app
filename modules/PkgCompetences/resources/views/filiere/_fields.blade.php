{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="filiereForm" action="{{ $itemFiliere->id ? route('filieres.update', $itemFiliere->id) : route('filieres.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemFiliere->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        
        <div class="form-group">
            <label for="code">
                {{ ucfirst(__('PkgCompetences::filiere.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                id="code"
                placeholder="{{ __('PkgCompetences::filiere.code') }}"
                value="{{ $itemFiliere ? $itemFiliere->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>
        
        
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgCompetences::filiere.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                id="nom"
                placeholder="{{ __('PkgCompetences::filiere.nom') }}"
                value="{{ $itemFiliere ? $itemFiliere->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>
        
        
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCompetences::filiere.description')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                required
                id="description"
                placeholder="{{ __('PkgCompetences::filiere.description') }}"
                value="{{ $itemFiliere ? $itemFiliere->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>
        
        
        
        <!--   Groupe_HasMany HasMany --> 
        
        
        
        <!--   Module_HasMany HasMany --> 
        
        
    </div>

    <div class="card-footer">
        <a href="{{ route('filieres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFiliere->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


