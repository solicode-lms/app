{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form" id="specialiteForm" action="{{ $itemSpecialite->id ? route('specialites.update', $itemSpecialite->id) : route('specialites.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemSpecialite->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgUtilisateurs::specialite.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                id="nom"
                placeholder="{{ __('PkgUtilisateurs::specialite.nom') }}"
                value="{{ $itemSpecialite ? $itemSpecialite->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>
        
        
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgUtilisateurs::specialite.description')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                required
                id="description"
                placeholder="{{ __('PkgUtilisateurs::specialite.description') }}"
                value="{{ $itemSpecialite ? $itemSpecialite->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>
        
        
        
            <div class="form-group">
            <label for="formateurs">
                {{ ucfirst(__('PkgUtilisateurs::Formateur.plural')) }}
            </label>
            <select
                id="formateurs"
                name="formateurs[]"
                class="form-control select2"
                multiple="multiple">
               
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemSpecialite) && $itemSpecialite->formateurs && $itemSpecialite->formateurs->contains('id', $formateur->id)) || (is_array(old('formateurs')) && in_array($formateur->id, old('formateurs'))) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
            @error('formateurs')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>

        
        
    </div>

    <div class="card-footer">
        <a href="{{ route('specialites.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemSpecialite->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


