{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('tache-form')
<form class="crud-form custom-form context-state container" id="tacheForm" action="{{ $itemTache->id ? route('taches.update', $itemTache->id) : route('taches.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemTache->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="titre">
                {{ ucfirst(__('PkgGestionTaches::tache.titre')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                id="titre"
                placeholder="{{ __('PkgGestionTaches::tache.titre') }}"
                value="{{ $itemTache ? $itemTache->titre : old('titre') }}">
            @error('titre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-12">
            <label for="description">
                {{ ucfirst(__('PkgGestionTaches::tache.description')) }}
                
            </label>
            <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                id="description"
                placeholder="{{ __('PkgGestionTaches::tache.description') }}">{{ $itemTache ? $itemTache->description : old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group col-12 col-md-6">
            <label for="dateDebut">
                {{ ucfirst(__('PkgGestionTaches::tache.dateDebut')) }}
                
            </label>
            <input
                name="dateDebut"
                type="date"
                class="form-control datetimepicker"
                
                
                id="dateDebut"
                placeholder="{{ __('PkgGestionTaches::tache.dateDebut') }}"
                value="{{ $itemTache ? $itemTache->dateDebut : old('dateDebut') }}">
            @error('dateDebut')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>





        
        <div class="form-group col-12 col-md-6">
            <label for="dateFin">
                {{ ucfirst(__('PkgGestionTaches::tache.dateFin')) }}
                
            </label>
            <input
                name="dateFin"
                type="date"
                class="form-control datetimepicker"
                
                
                id="dateFin"
                placeholder="{{ __('PkgGestionTaches::tache.dateFin') }}"
                value="{{ $itemTache ? $itemTache->dateFin : old('dateFin') }}">
            @error('dateFin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>





        
        <div class="form-group col-12 col-md-6">
            <label for="projet_id">
                {{ ucfirst(__('PkgCreationProjet::projet.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="projet_id" 
            required
            
            name="projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($projets as $projet)
                    <option value="{{ $projet->id }}"
                        {{ (isset($itemTache) && $itemTache->projet_id == $projet->id) || (old('projet_id>') == $projet->id) ? 'selected' : '' }}>
                        {{ $projet }}
                    </option>
                @endforeach
            </select>
            @error('projet_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-6">
            <label for="priorite_tache_id">
                {{ ucfirst(__('PkgGestionTaches::prioriteTache.singular')) }}
                
            </label>
            <select 
            id="priorite_tache_id" 
            
            
            name="priorite_tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($prioriteTaches as $prioriteTache)
                    <option value="{{ $prioriteTache->id }}"
                        {{ (isset($itemTache) && $itemTache->priorite_tache_id == $prioriteTache->id) || (old('priorite_tache_id>') == $prioriteTache->id) ? 'selected' : '' }}>
                        {{ $prioriteTache }}
                    </option>
                @endforeach
            </select>
            @error('priorite_tache_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        

        <!--   DependanceTache HasMany --> 

        

        <!--   DependanceTache HasMany --> 

        
                    <div class="form-group col-12 col-md-6">
            <label for="livrables">
                {{ ucfirst(__('PkgCreationProjet::Livrable.plural')) }}
            </label>
            <select
                id="livrables"
                name="livrables[]"
                class="form-control select2"
                
                multiple="multiple">
               
                @foreach ($livrables as $livrable)
                    <option value="{{ $livrable->id }}"
                        {{ (isset($itemTache) && $itemTache->livrables && $itemTache->livrables->contains('id', $livrable->id)) || (is_array(old('livrables')) && in_array($livrable->id, old('livrables'))) ? 'selected' : '' }}>
                        {{ $livrable }}
                    </option>
                @endforeach
            </select>
            @error('livrables')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


        

        <!--   RealisationTache HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('taches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgGestionTaches::tache.singular") }} : {{$itemTache}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
