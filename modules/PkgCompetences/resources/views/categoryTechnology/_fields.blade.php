{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('categoryTechnology-form')
<form class="crud-form custom-form context-state" id="categoryTechnologyForm" action="{{ $itemCategoryTechnology->id ? route('categoryTechnologies.update', $itemCategoryTechnology->id) : route('categoryTechnologies.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemCategoryTechnology->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgCompetences::categoryTechnology.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                id="nom"
                placeholder="{{ __('PkgCompetences::categoryTechnology.nom') }}"
                value="{{ $itemCategoryTechnology ? $itemCategoryTechnology->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgCompetences::categoryTechnology.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgCompetences::categoryTechnology.description') }}">
                {{ $itemCategoryTechnology ? $itemCategoryTechnology->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        

        <!--   Technology HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('categoryTechnologies.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemCategoryTechnology->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgCompetences::categoryTechnology.singular") }} : {{$itemCategoryTechnology}}'
</script>
