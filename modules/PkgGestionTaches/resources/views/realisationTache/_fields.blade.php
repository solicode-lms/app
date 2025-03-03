{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationTache-form')
<form class="crud-form custom-form context-state container" id="realisationTacheForm" action="{{ $itemRealisationTache->id ? route('realisationTaches.update', $itemRealisationTache->id) : route('realisationTaches.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemRealisationTache->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
        <div class="form-group col-12 col-md-6">
            <label for="dateDebut">
                {{ ucfirst(__('PkgGestionTaches::realisationTache.dateDebut')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="dateDebut"
                type="date"
                class="form-control datetimepicker"
                required
                
                id="dateDebut"
                placeholder="{{ __('PkgGestionTaches::realisationTache.dateDebut') }}"
                value="{{ $itemRealisationTache ? $itemRealisationTache->dateDebut : old('dateDebut') }}">
            @error('dateDebut')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>





        
        <div class="form-group col-12 col-md-6">
            <label for="dateFin">
                {{ ucfirst(__('PkgGestionTaches::realisationTache.dateFin')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="dateFin"
                type="date"
                class="form-control datetimepicker"
                required
                
                id="dateFin"
                placeholder="{{ __('PkgGestionTaches::realisationTache.dateFin') }}"
                value="{{ $itemRealisationTache ? $itemRealisationTache->dateFin : old('dateFin') }}">
            @error('dateFin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>





        
        <div class="form-group col-12 col-md-6">
            <label for="tache_id">
                {{ ucfirst(__('PkgGestionTaches::tache.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="tache_id" 
            required
            
            name="tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($taches as $tache)
                    <option value="{{ $tache->id }}"
                        {{ (isset($itemRealisationTache) && $itemRealisationTache->tache_id == $tache->id) || (old('tache_id>') == $tache->id) ? 'selected' : '' }}>
                        {{ $tache }}
                    </option>
                @endforeach
            </select>
            @error('tache_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-6">
            <label for="realisation_projet_id">
                {{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="realisation_projet_id" 
            required
            
            name="realisation_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationProjets as $realisationProjet)
                    <option value="{{ $realisationProjet->id }}"
                        {{ (isset($itemRealisationTache) && $itemRealisationTache->realisation_projet_id == $realisationProjet->id) || (old('realisation_projet_id>') == $realisationProjet->id) ? 'selected' : '' }}>
                        {{ $realisationProjet }}
                    </option>
                @endforeach
            </select>
            @error('realisation_projet_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-6">
            <label for="etat_realisation_tache_id">
                {{ ucfirst(__('PkgGestionTaches::etatRealisationTache.singular')) }}
                
            </label>
            <select 
            id="etat_realisation_tache_id" 
            
            
            name="etat_realisation_tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatRealisationTaches as $etatRealisationTache)
                    <option value="{{ $etatRealisationTache->id }}"
                        {{ (isset($itemRealisationTache) && $itemRealisationTache->etat_realisation_tache_id == $etatRealisationTache->id) || (old('etat_realisation_tache_id>') == $etatRealisationTache->id) ? 'selected' : '' }}>
                        {{ $etatRealisationTache }}
                    </option>
                @endforeach
            </select>
            @error('etat_realisation_tache_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        

        <!--   CommentaireRealisationTache HasMany --> 

        

        <!--   HistoriqueRealisationTache HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('realisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationTache->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgGestionTaches::realisationTache.singular") }} : {{$itemRealisationTache}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
