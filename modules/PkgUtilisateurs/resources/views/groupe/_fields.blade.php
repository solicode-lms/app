{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('groupes.update', $item->id) : route('groupes.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="code">
                {{ ucfirst(__('PkgUtilisateurs::groupe.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                id="code"
                placeholder="{{ __('PkgUtilisateurs::groupe.code') }}"
                value="{{ $item ? $item->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgUtilisateurs::groupe.nom')) }}
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                id="nom"
                placeholder="{{ __('PkgUtilisateurs::groupe.nom') }}"
                value="{{ $item ? $item->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgUtilisateurs::groupe.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('PkgUtilisateurs::groupe.description') }}"
                value="{{ $item ? $item->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="filiere_id">
                {{ ucfirst(__('PkgCompetences::filiere.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="filiere_id" name="filiere_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('filiere_id')
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
                        {{ (isset($item) && $item->formateurs && $item->formateurs->contains('id', $formateur->id)) || (is_array(old('formateurs')) && in_array($formateur->id, old('formateurs'))) ? 'selected' : '' }}>
                        {{ $formateur->nom }}
                    </option>
                @endforeach
            </select>
            @error('formateurs')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        



    </div>

    <div class="card-footer">
        <a href="{{ route('groupes.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'filiere_id',
            fetchUrl: "{{ route('filieres.all') }}",
            selectedValue: {{ $item->filiere_id ? $item->filiere_id : 'undefined' }},
            fieldValue: 'code'
        }
        
    ];
</script>
