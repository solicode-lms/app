{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationChapitre-form')
<form 
    class="crud-form custom-form context-state container" 
    id="realisationChapitreForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('realisationChapitres.bulkUpdate') : ($itemRealisationChapitre->id ? route('realisationChapitres.update', $itemRealisationChapitre->id) : route('realisationChapitres.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemRealisationChapitre->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($realisationChapitre_ids))
        @foreach ($realisationChapitre_ids as $id)
            <input type="hidden" name="realisationChapitre_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemRealisationChapitre" field="date_debut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_debut" id="bulk_field_date_debut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_debut">
            {{ ucfirst(__('PkgApprentissage::realisationChapitre.date_debut')) }}
            
          </label>
                      <input
                name="date_debut"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_debut"
                placeholder="{{ __('PkgApprentissage::realisationChapitre.date_debut') }}"
                value="{{ $itemRealisationChapitre ? $itemRealisationChapitre->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationChapitre" field="date_fin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="date_fin" id="bulk_field_date_fin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="date_fin">
            {{ ucfirst(__('PkgApprentissage::realisationChapitre.date_fin')) }}
            
          </label>
                      <input
                name="date_fin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="date_fin"
                placeholder="{{ __('PkgApprentissage::realisationChapitre.date_fin') }}"
                value="{{ $itemRealisationChapitre ? $itemRealisationChapitre->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationChapitre" field="commentaire_formateur" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="commentaire_formateur" id="bulk_field_commentaire_formateur" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="commentaire_formateur">
            {{ ucfirst(__('PkgApprentissage::realisationChapitre.commentaire_formateur')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="commentaire_formateur"
                class="form-control richText"
                
                
                
                id="commentaire_formateur"
                placeholder="{{ __('PkgApprentissage::realisationChapitre.commentaire_formateur') }}">{{ $itemRealisationChapitre ? $itemRealisationChapitre->commentaire_formateur : old('commentaire_formateur') }}</textarea>
          @error('commentaire_formateur')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationChapitre" field="realisation_ua_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_ua_id" id="bulk_field_realisation_ua_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_ua_id">
            {{ ucfirst(__('PkgApprentissage::realisationUa.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="realisation_ua_id" 
            required
            
            
            name="realisation_ua_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationUas as $realisationUa)
                    <option value="{{ $realisationUa->id }}"
                        {{ (isset($itemRealisationChapitre) && $itemRealisationChapitre->realisation_ua_id == $realisationUa->id) || (old('realisation_ua_id>') == $realisationUa->id) ? 'selected' : '' }}>
                        {{ $realisationUa }}
                    </option>
                @endforeach
            </select>
          @error('realisation_ua_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationChapitre" field="realisation_tache_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="realisation_tache_id" id="bulk_field_realisation_tache_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="realisation_tache_id">
            {{ ucfirst(__('PkgRealisationTache::realisationTache.singular')) }}
            
          </label>
                      <select 
            id="realisation_tache_id" 
            
            
            
            name="realisation_tache_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($realisationTaches as $realisationTache)
                    <option value="{{ $realisationTache->id }}"
                        {{ (isset($itemRealisationChapitre) && $itemRealisationChapitre->realisation_tache_id == $realisationTache->id) || (old('realisation_tache_id>') == $realisationTache->id) ? 'selected' : '' }}>
                        {{ $realisationTache }}
                    </option>
                @endforeach
            </select>
          @error('realisation_tache_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationChapitre" field="chapitre_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="chapitre_id" id="bulk_field_chapitre_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="chapitre_id">
            {{ ucfirst(__('PkgCompetences::chapitre.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="chapitre_id" 
            required
            
            
            name="chapitre_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($chapitres as $chapitre)
                    <option value="{{ $chapitre->id }}"
                        {{ (isset($itemRealisationChapitre) && $itemRealisationChapitre->chapitre_id == $chapitre->id) || (old('chapitre_id>') == $chapitre->id) ? 'selected' : '' }}>
                        {{ $chapitre }}
                    </option>
                @endforeach
            </select>
          @error('chapitre_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemRealisationChapitre" field="etat_realisation_chapitre_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="etat_realisation_chapitre_id" id="bulk_field_etat_realisation_chapitre_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="etat_realisation_chapitre_id">
            {{ ucfirst(__('PkgApprentissage::etatRealisationChapitre.singular')) }}
            
          </label>
                      <select 
            id="etat_realisation_chapitre_id" 
            
            
            
            name="etat_realisation_chapitre_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($etatRealisationChapitres as $etatRealisationChapitre)
                    <option value="{{ $etatRealisationChapitre->id }}"
                        {{ (isset($itemRealisationChapitre) && $itemRealisationChapitre->etat_realisation_chapitre_id == $etatRealisationChapitre->id) || (old('etat_realisation_chapitre_id>') == $etatRealisationChapitre->id) ? 'selected' : '' }}>
                        {{ $etatRealisationChapitre }}
                    </option>
                @endforeach
            </select>
          @error('etat_realisation_chapitre_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('realisationChapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRealisationChapitre->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgApprentissage::realisationChapitre.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprentissage::realisationChapitre.singular") }} : {{$itemRealisationChapitre}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
