{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('role-form')
<form class="crud-form custom-form context-state" id="nationaliteForm" action="{{ $itemNationalite->id ? route('nationalites.update', $itemNationalite->id) : route('nationalites.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemNationalite->id)
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="form-group">
            <label for="code">
                {{ ucfirst(__('PkgUtilisateurs::nationalite.code')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="code"
                type="input"
                class="form-control"
                required
                id="code"
                placeholder="{{ __('PkgUtilisateurs::nationalite.code') }}"
                value="{{ $itemNationalite ? $itemNationalite->code : old('code') }}">
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="nom">
                {{ ucfirst(__('PkgUtilisateurs::nationalite.nom')) }}
                
            </label>
            <input
                name="nom"
                type="input"
                class="form-control"
                
                id="nom"
                placeholder="{{ __('PkgUtilisateurs::nationalite.nom') }}"
                value="{{ $itemNationalite ? $itemNationalite->nom : old('nom') }}">
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        <div class="form-group">
            <label for="description">
                {{ ucfirst(__('PkgUtilisateurs::nationalite.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                id="description"
                placeholder="{{ __('PkgUtilisateurs::nationalite.description') }}">
                {{ $itemNationalite ? $itemNationalite->description : old('description') }}
            </textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>


        <!--   Apprenant_HasMany HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('nationalites.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemNationalite->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


