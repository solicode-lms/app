{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form action="{{ $item->id ? route('appreciations.update', $item->id) : route('appreciations.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgCompetences::appreciation.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                id="nom"
                placeholder="{{ __('PkgCompetences::appreciation.nom') }}"
                value="{{ $item ? $item->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCompetences::appreciation.description')) }}
                
            </label>
            <input
                name="description"
                type="input"
                class="form-control"
                id="description"
                placeholder="{{ __('PkgCompetences::appreciation.description') }}"
                value="{{ $item ? $item->description : old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="noteMin">
                {{ ucfirst(__('PkgCompetences::appreciation.noteMin')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="noteMin"
                type="input"
                class="form-control"
                id="noteMin"
                placeholder="{{ __('PkgCompetences::appreciation.noteMin') }}"
                value="{{ $item ? $item->noteMin : old('noteMin') }}">
            @error('noteMin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="noteMax">
                {{ ucfirst(__('PkgCompetences::appreciation.noteMax')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="noteMax"
                type="input"
                class="form-control"
                id="noteMax"
                placeholder="{{ __('PkgCompetences::appreciation.noteMax') }}"
                value="{{ $item ? $item->noteMax : old('noteMax') }}">
            @error('noteMax')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="formateur_id">
                {{ ucfirst(__('PkgUtilisateurs::formateur.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select id="formateur_id" name="formateur_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('formateur_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        



    </div>

    <div class="card-footer">
        <a href="{{ route('appreciations.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'formateur_id',
            fetchUrl: "{{ route('formateurs.all') }}",
            selectedValue: {{ $item->formateur_id ? $item->formateur_id : 'undefined' }},
            fieldValue: 'nom'
        }
        
    ];
</script>
