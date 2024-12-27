
<form id="filiereForm" action="{{ $itemFiliere->id ? route('filieres.update', $itemFiliere->id) : route('filieres.store') }}" method="POST">
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
                id="description"
                placeholder="{{ __('PkgCompetences::filiere.description') }}"
                value="{{ $itemFiliere ? $itemFiliere->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        

        



    </div>

    <div class="card-footer">
        <a href="{{ route('filieres.index') }}" id="filiere_form_cancel" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFiliere->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
    ];
</script>
