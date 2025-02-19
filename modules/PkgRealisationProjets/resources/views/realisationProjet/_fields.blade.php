{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationProjet-form')
<form class="crud-form custom-form context-state container" id="realisationProjetForm" action="{{ $itemRealisationProjet->id ? route('realisationProjets.update', $itemRealisationProjet->id) : route('realisationProjets.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemRealisationProjet->id)
        @method('PUT')
    @endif

    <div class="card-body row">
        
            <div class="form-group col-12 col-md-6">
            <label for="affectation_projet_id">
                {{ ucfirst(__('PkgRealisationProjets::affectationProjet.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="affectation_projet_id" 
            required
            
            name="affectation_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($affectationProjets as $affectationProjet)
                    <option value="{{ $affectationProjet->id }}"
                        {{ (isset($itemRealisationProjet) && $itemRealisationProjet->affectation_projet_id == $affectationProjet->id) || (old('affectation_projet_id>') == $affectationProjet->id) ? 'selected' : '' }}>
                        {{ $affectationProjet }}
                    </option>
                @endforeach
            </select>
            @error('affectation_projet_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
            <div class="form-group col-12 col-md-6">
            <label for="apprenant_id">
                {{ ucfirst(__('PkgApprenants::apprenant.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="apprenant_id" 
            required
            
            name="apprenant_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($apprenants as $apprenant)
                    <option value="{{ $apprenant->id }}"
                        {{ (isset($itemRealisationProjet) && $itemRealisationProjet->apprenant_id == $apprenant->id) || (old('apprenant_id>') == $apprenant->id) ? 'selected' : '' }}>
                        {{ $apprenant }}
                    </option>
                @endforeach
            </select>
            @error('apprenant_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
            <div class="form-group col-12 col-md-6">
            <label for="etats_realisation_projet_id">
                {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="etats_realisation_projet_id" 
            required
            
            name="etats_realisation_projet_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatsRealisationProjets as $etatsRealisationProjet)
                    <option value="{{ $etatsRealisationProjet->id }}"
                        {{ (isset($itemRealisationProjet) && $itemRealisationProjet->etats_realisation_projet_id == $etatsRealisationProjet->id) || (old('etats_realisation_projet_id>') == $etatsRealisationProjet->id) ? 'selected' : '' }}>
                        {{ $etatsRealisationProjet }}
                    </option>
                @endforeach
            </select>
            @error('etats_realisation_projet_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        <div class="form-group col-12 col-md-6">
            <label for="date_debut">
                {{ ucfirst(__('PkgRealisationProjets::realisationProjet.date_debut')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="date_debut"
                type="date"
                class="form-control datetimepicker"
                required
                
                id="date_debut"
                placeholder="{{ __('PkgRealisationProjets::realisationProjet.date_debut') }}"
                value="{{ $itemRealisationProjet ? $itemRealisationProjet->date_debut : old('date_debut') }}">
            @error('date_debut')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>





        
        <div class="form-group col-12 col-md-6">
            <label for="date_fin">
                {{ ucfirst(__('PkgRealisationProjets::realisationProjet.date_fin')) }}
                
            </label>
            <input
                name="date_fin"
                type="date"
                class="form-control datetimepicker"
                
                
                id="date_fin"
                placeholder="{{ __('PkgRealisationProjets::realisationProjet.date_fin') }}"
                value="{{ $itemRealisationProjet ? $itemRealisationProjet->date_fin : old('date_fin') }}">
            @error('date_fin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>





        

        <!--   LivrablesRealisation HasMany --> 

        

        <!--   Validation HasMany --> 

        
        <div class="form-group col-12 col-md-12">
            <label for="rapport">
                {{ ucfirst(__('PkgRealisationProjets::realisationProjet.rapport')) }}
                
            </label>
            <textarea rows="" cols=""
                name="rapport"
                class="form-control richText"
                
                
                id="rapport"
                placeholder="{{ __('PkgRealisationProjets::realisationProjet.rapport') }}">
                {{ $itemRealisationProjet ? $itemRealisationProjet->rapport : old('rapport') }}
            </textarea>
            @error('rapport')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

    </div>

    <div class="card-footer">
        <a href="{{ route('realisationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationProjet->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgRealisationProjets::realisationProjet.singular") }} : {{$itemRealisationProjet}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
