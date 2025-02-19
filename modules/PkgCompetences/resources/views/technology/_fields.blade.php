{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('technology-form')
<form class="crud-form custom-form context-state container" id="technologyForm" action="{{ $itemTechnology->id ? route('technologies.update', $itemTechnology->id) : route('technologies.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemTechnology->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="nom">
                {{ ucfirst(__('PkgCompetences::technology.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                id="nom"
                placeholder="{{ __('PkgCompetences::technology.nom') }}"
                value="{{ $itemTechnology ? $itemTechnology->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
            <div class="form-group col-12 col-md-6">
            <label for="category_technology_id">
                {{ ucfirst(__('PkgCompetences::categoryTechnology.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="category_technology_id" 
            required
            
            name="category_technology_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($categoryTechnologies as $categoryTechnology)
                    <option value="{{ $categoryTechnology->id }}"
                        {{ (isset($itemTechnology) && $itemTechnology->category_technology_id == $categoryTechnology->id) || (old('category_technology_id>') == $categoryTechnology->id) ? 'selected' : '' }}>
                        {{ $categoryTechnology }}
                    </option>
                @endforeach
            </select>
            @error('category_technology_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
                    <div class="form-group col-12 col-md-6">
            <label for="competences">
                {{ ucfirst(__('PkgCompetences::Competence.plural')) }}
            </label>
            <select
                id="competences"
                name="competences[]"
                class="form-control select2"
                
                multiple="multiple">
               
                @foreach ($competences as $competence)
                    <option value="{{ $competence->id }}"
                        {{ (isset($itemTechnology) && $itemTechnology->competences && $itemTechnology->competences->contains('id', $competence->id)) || (is_array(old('competences')) && in_array($competence->id, old('competences'))) ? 'selected' : '' }}>
                        {{ $competence }}
                    </option>
                @endforeach
            </select>
            @error('competences')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgCompetences::technology.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgCompetences::technology.description') }}">
                {{ $itemTechnology ? $itemTechnology->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

    </div>

    <div class="card-footer">
        <a href="{{ route('technologies.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemTechnology->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgCompetences::technology.singular") }} : {{$itemTechnology}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
