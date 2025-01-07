{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="transfertCompetenceForm" action="{{ $itemTransfertCompetence->id ? route('transfertCompetences.update', $itemTransfertCompetence->id) : route('transfertCompetences.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemTransfertCompetence->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCreationProjet::transfertCompetence.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgCreationProjet::transfertCompetence.description') }}">
                {{ $itemTransfertCompetence ? $itemTransfertCompetence->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
    <div class="form-group">
            <label for="projet_id">
                {{ ucfirst(__('PkgCreationProjet::projet.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="projet_id" 
            required
            name="projet_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($projets as $projet)
                    <option value="{{ $projet->id }}"
                        {{ (isset($itemTransfertCompetence) && $itemTransfertCompetence->projet_id == $projet->id) || (old('projet_id>') == $projet->id) ? 'selected' : '' }}>
                        {{ $projet }}
                    </option>
                @endforeach
            </select>
            @error('projet_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
    <div class="form-group">
            <label for="competence_id">
                {{ ucfirst(__('PkgCompetences::competence.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="competence_id" 
            required
            name="competence_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($competences as $competence)
                    <option value="{{ $competence->id }}"
                        {{ (isset($itemTransfertCompetence) && $itemTransfertCompetence->competence_id == $competence->id) || (old('competence_id>') == $competence->id) ? 'selected' : '' }}>
                        {{ $competence }}
                    </option>
                @endforeach
            </select>
            @error('competence_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
    <div class="form-group">
            <label for="appreciation_id">
                {{ ucfirst(__('PkgCompetences::appreciation.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="appreciation_id" 
            required
            name="appreciation_id" 
            class="form-control">
             <option value="">Sélectionnez une option</option>
                @foreach ($appreciations as $appreciation)
                    <option value="{{ $appreciation->id }}"
                        {{ (isset($itemTransfertCompetence) && $itemTransfertCompetence->appreciation_id == $appreciation->id) || (old('appreciation_id>') == $appreciation->id) ? 'selected' : '' }}>
                        {{ $appreciation }}
                    </option>
                @endforeach
            </select>
            @error('appreciation_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


                <div class="form-group">
            <label for="technologies">
                {{ ucfirst(__('PkgCompetences::Technology.plural')) }}
            </label>
            <select
                id="technologies"
                name="technologies[]"
                class="form-control select2"
                multiple="multiple">
               
                @foreach ($technologies as $technology)
                    <option value="{{ $technology->id }}"
                        {{ (isset($itemTransfertCompetence) && $itemTransfertCompetence->technologies && $itemTransfertCompetence->technologies->contains('id', $technology->id)) || (is_array(old('technologies')) && in_array($technology->id, old('technologies'))) ? 'selected' : '' }}>
                        {{ $technology }}
                    </option>
                @endforeach
            </select>
            @error('technologies')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('transfertCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemTransfertCompetence->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


