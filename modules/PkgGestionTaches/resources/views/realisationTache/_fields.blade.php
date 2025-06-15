{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationTache-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationTacheForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationTaches.bulkUpdate') : ($itemRealisationTache->id ? route('realisationTaches.update', $itemRealisationTache->id) : route('realisationTaches.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationTache->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($realisationTache_ids))
        @foreach ($realisationTache_ids as $id)
            <input type="hidden" name="realisationTache_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
      <h5 class="debut-groupe-title text-info">{{ __('Informations générales') }}</h5>
      <hr class="debut-groupe-hr">
    
    <div class="row">
        <x-form-field :entity="$itemRealisationTache" field="tache_id" :bulkEdit="$bulkEdit">
      @php $canEdittache_id = !$itemRealisationTache || !$itemRealisationTache->id || Auth::user()->hasAnyRole(explode(',', 'formateur,admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="tache_id" id="bulk_field_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :entity="$itemRealisationTache" field="realisation_projet_id" :bulkEdit="$bulkEdit">
      @php $canEditrealisation_projet_id = !$itemRealisationTache || !$itemRealisationTache->id || Auth::user()->hasAnyRole(explode(',', 'formateur,admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_projet_id" id="bulk_field_realisation_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>


    </div>
  
    

    
      <h5 class="debut-groupe-title text-info">{{ __('Dates de réalisation') }}</h5>
      <hr class="debut-groupe-hr">
    
    <div class="row">
        <x-form-field :entity="$itemRealisationTache" field="dateDebut" :bulkEdit="$bulkEdit">
      @php $canEditdateDebut = !$itemRealisationTache || !$itemRealisationTache->id || Auth::user()->hasAnyRole(explode(',', 'apprenant,formateur,admin')); @endphp

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="dateDebut" id="bulk_field_dateDebut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dateDebut">
            {{ ucfirst(__('PkgGestionTaches::realisationTache.dateDebut')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="dateDebut"
                type="text"
                class="form-control datetimepicker"
                required
                
                
                id="dateDebut"
                {{ $canEditdateDebut ? '' : 'disabled' }}
                placeholder="{{ __('PkgGestionTaches::realisationTache.dateDebut') }}"
                value="{{ $itemRealisationTache ? $itemRealisationTache->dateDebut : old('dateDebut') }}">

          @error('dateDebut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationTache" field="dateFin" :bulkEdit="$bulkEdit">
      @php $canEditdateFin = !$itemRealisationTache || !$itemRealisationTache->id || Auth::user()->hasAnyRole(explode(',', 'apprenant,formateur,admin')); @endphp

      <div class="form-group col-12 col-md-3">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="dateFin" id="bulk_field_dateFin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dateFin">
            {{ ucfirst(__('PkgGestionTaches::realisationTache.dateFin')) }}
            
          </label>
                      <input
                name="dateFin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="dateFin"
                {{ $canEditdateFin ? '' : 'disabled' }}
                placeholder="{{ __('PkgGestionTaches::realisationTache.dateFin') }}"
                value="{{ $itemRealisationTache ? $itemRealisationTache->dateFin : old('dateFin') }}">

          @error('dateFin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  
    

    
      <h5 class="debut-groupe-title text-info">{{ __('État') }}</h5>
      <hr class="debut-groupe-hr">
    
    <div class="row">
        <x-form-field :entity="$itemRealisationTache" field="etat_realisation_tache_id" :bulkEdit="$bulkEdit">
      @php $canEditetat_realisation_tache_id = !$itemRealisationTache || !$itemRealisationTache->id || Auth::user()->hasAnyRole(explode(',', 'apprenant,formateur,admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="etat_realisation_tache_id" id="bulk_field_etat_realisation_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="etat_realisation_tache_id">
            {{ ucfirst(__('PkgGestionTaches::etatRealisationTache.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="etat_realisation_tache_id" 
            {{ $canEditetat_realisation_tache_id ? '' : 'disabled' }}
            required
            
            
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
  
</x-form-field>

<x-form-field :entity="$itemRealisationTache" field="note" :bulkEdit="$bulkEdit">
      @php $canEditnote = !$itemRealisationTache || !$itemRealisationTache->id || Auth::user()->hasAnyRole(explode(',', 'formateur,evaluateur')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="note" id="bulk_field_note" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note">
            {{ ucfirst(__('PkgGestionTaches::realisationTache.note')) }}
            
          </label>
              <input
        name="note"
        type="number"
        class="form-control"
        
        
        
        id="note"
        {{ $canEditnote ? '' : 'disabled' }}
        step="0.01"
        placeholder="{{ __('PkgGestionTaches::realisationTache.note') }}"
        value="{{ $itemRealisationTache ? number_format($itemRealisationTache->note, 2, '.', '') : old('note') }}">
          @error('note')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  
    

    
      <h5 class="debut-groupe-title text-info">{{ __('Remarques') }}</h5>
      <hr class="debut-groupe-hr">
    
    <div class="row">
        <x-form-field :entity="$itemRealisationTache" field="remarques_formateur" :bulkEdit="$bulkEdit">
      @php $canEditremarques_formateur = !$itemRealisationTache || !$itemRealisationTache->id || Auth::user()->hasAnyRole(explode(',', 'formateur')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="remarques_formateur" id="bulk_field_remarques_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :entity="$itemRealisationTache" field="remarques_apprenant" :bulkEdit="$bulkEdit">
      @php $canEditremarques_apprenant = !$itemRealisationTache || !$itemRealisationTache->id || Auth::user()->hasAnyRole(explode(',', 'apprenant,formateur,admin')); @endphp

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="remarques_apprenant" id="bulk_field_remarques_apprenant" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="remarques_apprenant">
            {{ ucfirst(__('PkgGestionTaches::realisationTache.remarques_apprenant')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="remarques_apprenant"
                class="form-control richText"
                {{ $canEditremarques_apprenant ? '' : 'disabled' }}
                
                
                
                id="remarques_apprenant"
                placeholder="{{ __('PkgGestionTaches::realisationTache.remarques_apprenant') }}">{{ $itemRealisationTache ? $itemRealisationTache->remarques_apprenant : old('remarques_apprenant') }}</textarea>
          @error('remarques_apprenant')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  
    
    

    
    <div class="row">
        <x-form-field :entity="$itemRealisationTache" field="remarque_evaluateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="remarque_evaluateur" id="bulk_field_remarque_evaluateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="remarque_evaluateur">
            {{ ucfirst(__('PkgGestionTaches::realisationTache.remarque_evaluateur')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="remarque_evaluateur"
                class="form-control richText"
                
                
                
                id="remarque_evaluateur"
                placeholder="{{ __('PkgGestionTaches::realisationTache.remarque_evaluateur') }}">{{ $itemRealisationTache ? $itemRealisationTache->remarque_evaluateur : old('remarque_evaluateur') }}</textarea>
          @error('remarque_evaluateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>



@if($itemRealisationTache->id)
@if (empty($bulkEdit))
<div class="col-12 col-md-12">
   <label for="EvaluationRealisationTache">
            {{ ucfirst(__('PkgValidationProjets::evaluationRealisationTache.plural')) }}
            
    </label>

  @include('PkgValidationProjets::evaluationRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . $itemRealisationTache->id])
</div>
@endif
@endif




@if($itemRealisationTache->id)
@if (empty($bulkEdit))
<div class="col-12 col-md-12">
   <label for="HistoriqueRealisationTache">
            {{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.plural')) }}
            
    </label>

  @include('PkgGestionTaches::historiqueRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . $itemRealisationTache->id])
</div>
@endif
@endif



    </div>
  


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
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgGestionTaches::realisationTache.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgGestionTaches::realisationTache.singular") }} : {{$itemRealisationTache}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
