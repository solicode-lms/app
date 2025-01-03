{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<form class="crud-form context-state" id="appreciationForm" action="{{ $itemAppreciation->id ? route('appreciations.update', $itemAppreciation->id) : route('appreciations.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemAppreciation->id)
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
                required
                id="nom"
                placeholder="{{ __('PkgCompetences::appreciation.nom') }}"
                value="{{ $itemAppreciation ? $itemAppreciation->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCompetences::appreciation.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgCompetences::appreciation.description') }}">
                {{ $itemAppreciation ? $itemAppreciation->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>


        <!--   noteMin Float --> 


        <!--   noteMax Float --> 

        
    <div class="form-group">
            <label for="formateur_id">
                {{ ucfirst(__('PkgUtilisateurs::formateur.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="formateur_id" 
            required
            name="formateur_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($formateurs as $formateur)
                    <option value="{{ $formateur->id }}"
                        {{ (isset($itemAppreciation) && $itemAppreciation->formateur_id == $formateur->id) || (old('formateur_id>') == $formateur->id) ? 'selected' : '' }}>
                        {{ $formateur }}
                    </option>
                @endforeach
            </select>
            @error('formateur_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>



        <!--   TransfertCompetence_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('appreciations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemAppreciation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>


