{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationTache-form')
<form class="crud-form custom-form context-state container" id="realisationTacheForm" action="{{ $itemRealisationTache->id ? route('realisationTaches.update', $itemRealisationTache->id) : route('realisationTaches.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemRealisationTache->id)
        @method('PUT')
    @endif

    <div class="card-body row">
      @php $canEdittache_id = Auth::user()->hasAnyRole(explode(',', 'formateur,admin')); @endphp

      <div class="form-group col-12 col-md-6">
          <label for="tache_id">
            {{ ucfirst(__('PkgGestionTaches::tache.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="tache_id" 
            {{ $canEdittache_id ? '' : 'disabled' }}
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
  

      @php $canEditrealisation_projet_id = Auth::user()->hasAnyRole(explode(',', 'formateur,admin')); @endphp

      <div class="form-group col-12 col-md-6">
          <label for="realisation_projet_id">
            {{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_projet_id" 
            {{ $canEditrealisation_projet_id ? '' : 'disabled' }}
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
  


      <div class="form-group col-12 col-md-3">
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
  


      <div class="form-group col-12 col-md-3">
          <label for="dateFin">
            {{ ucfirst(__('PkgGestionTaches::realisationTache.dateFin')) }}
            
          </label>
                      <input
                name="dateFin"
                type="date"
                class="form-control datetimepicker"
                
                
                
                id="dateFin"
                placeholder="{{ __('PkgGestionTaches::realisationTache.dateFin') }}"
                value="{{ $itemRealisationTache ? $itemRealisationTache->dateFin : old('dateFin') }}">

          @error('dateFin')
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
  

      @php $canEditremarques_formateur = Auth::user()->hasAnyRole(explode(',', 'formateur')); @endphp

      <div class="form-group col-12 col-md-6">
          <label for="remarques_formateur">
            {{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_formateur')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="remarques_formateur"
                class="form-control richText"
                {{ $canEditremarques_formateur ? '' : 'disabled' }}
                
                
                
                id="remarques_formateur"
                placeholder="{{ __('PkgGestionTaches::realisationTache.remarques_formateur') }}">{{ $itemRealisationTache ? $itemRealisationTache->remarques_formateur : old('remarques_formateur') }}</textarea>
          @error('remarques_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="remarques_apprenant">
            {{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_apprenant')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="remarques_apprenant"
                class="form-control richText"
                
                
                
                id="remarques_apprenant"
                placeholder="{{ __('PkgGestionTaches::realisationTache.remarques_apprenant') }}">{{ $itemRealisationTache ? $itemRealisationTache->remarques_apprenant : old('remarques_apprenant') }}</textarea>
          @error('remarques_apprenant')
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
