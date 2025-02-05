{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('validation-form')
<form class="crud-form custom-form context-state" id="validationForm" action="{{ $itemValidation->id ? route('validations.update', $itemValidation->id) : route('validations.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemValidation->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
    <label for="note">
        {{ ucfirst(__('PkgRealisationProjets::validation.note')) }}
        
    </label>
    <input
        name="note"
        type="number"
        class="form-control"
        
        
        id="note"
        step="0.01"
        placeholder="{{ __('PkgRealisationProjets::validation.note') }}"
        value="{{ $itemValidation ? number_format($itemValidation->note, 2, '.', '') : old('note') }}">
    @error('note')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>



        
        <div class="form-group">
            <label for="message">
                {{ ucfirst(__('PkgRealisationProjets::validation.message')) }}
                
            </label>
            <input
                name="message"
                type="input"
                class="form-control"
                
                
                id="message"
                placeholder="{{ __('PkgRealisationProjets::validation.message') }}"
                value="{{ $itemValidation ? $itemValidation->message : old('message') }}">
            @error('message')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        <div class="form-group">
            <label for="is_valide">
                {{ ucfirst(__('PkgRealisationProjets::validation.is_valide')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="is_valide"
                type="number"
                class="form-control"
                required
                
                id="is_valide"
                placeholder="{{ __('PkgRealisationProjets::validation.is_valide') }}"
                value="{{ $itemValidation ? $itemValidation->is_valide : old('is_valide') }}">
            @error('is_valide')
                <div class="text-danger">{{ $message }}</div>
            @enderror
</div>

        
        
    <div class="form-group">
            <label for="transfert_competence_id">
                {{ ucfirst(__('PkgCreationProjet::transfertCompetence.singular')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <select 
            id="transfert_competence_id" 
            required
            
            name="transfert_competence_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($transfertCompetences as $transfertCompetence)
                    <option value="{{ $transfertCompetence->id }}"
                        {{ (isset($itemValidation) && $itemValidation->transfert_competence_id == $transfertCompetence->id) || (old('transfert_competence_id>') == $transfertCompetence->id) ? 'selected' : '' }}>
                        {{ $transfertCompetence }}
                    </option>
                @endforeach
            </select>
            @error('transfert_competence_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


        
        
    <div class="form-group">
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
                        {{ (isset($itemValidation) && $itemValidation->realisation_projet_id == $realisationProjet->id) || (old('realisation_projet_id>') == $realisationProjet->id) ? 'selected' : '' }}>
                        {{ $realisationProjet }}
                    </option>
                @endforeach
            </select>
            @error('realisation_projet_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>


    </div>

    <div class="card-footer">
        <a href="{{ route('validations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemValidation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgRealisationProjets::validation.singular") }} : {{$itemValidation}}'
</script>
