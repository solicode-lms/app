{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('specialite-form')
<form class="crud-form custom-form context-state" id="specialiteForm" action="{{ $itemSpecialite->id ? route('specialites.update', $itemSpecialite->id) : route('specialites.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemSpecialite->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgFormation::specialite.nom')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                id="nom"
                placeholder="{{ __('PkgFormation::specialite.nom') }}"
                value="{{ $itemSpecialite ? $itemSpecialite->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgFormation::specialite.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgFormation::specialite.description') }}">
                {{ $itemSpecialite ? $itemSpecialite->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
                <div class="form-group">
            <label for="formateurs">
                {{ ucfirst(__('PkgFormation::Formateur.plural')) }}
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
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgFormation::specialite.singular") }} : {{$itemSpecialite}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
