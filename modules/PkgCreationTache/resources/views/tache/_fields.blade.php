{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('tache-form')
<form 
    class="crud-form custom-form context-state container" 
    id="tacheForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('taches.bulkUpdate') : ($itemTache->id ? route('taches.update', $itemTache->id) : route('taches.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemTache->id)
        <input type="hidden" name="id" value="{{ $itemTache->id }}">
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($tache_ids))
        @foreach ($tache_ids as $id)
            <input type="hidden" name="tache_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemTache" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-2">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgCreationTache::tache.ordre')) }}
            
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                
                
                
                id="ordre"
                placeholder="{{ __('PkgCreationTache::tache.ordre') }}"
                value="{{ $itemTache ? $itemTache->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTache" field="priorite" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-2">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="priorite" id="bulk_field_priorite" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="priorite">
            {{ ucfirst(__('PkgCreationTache::tache.priorite')) }}
            
          </label>
                      <input
                name="priorite"
                type="number"
                class="form-control"
                
                
                
                id="priorite"
                placeholder="{{ __('PkgCreationTache::tache.priorite') }}"
                value="{{ $itemTache ? $itemTache->priorite : old('priorite') }}">
          @error('priorite')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTache" field="titre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="titre" id="bulk_field_titre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgCreationTache::tache.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgCreationTache::tache.titre') }}"
                value="{{ $itemTache ? $itemTache->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTache" field="projet_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="projet_id" id="bulk_field_projet_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
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
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTache" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgCreationTache::tache.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgCreationTache::tache.description') }}">{{ $itemTache ? $itemTache->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTache" field="dateDebut" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="dateDebut" id="bulk_field_dateDebut" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dateDebut">
            {{ ucfirst(__('PkgCreationTache::tache.dateDebut')) }}
            
          </label>
                      <input
                name="dateDebut"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="dateDebut"
                placeholder="{{ __('PkgCreationTache::tache.dateDebut') }}"
                value="{{ $itemTache ? $itemTache->dateDebut : old('dateDebut') }}">

          @error('dateDebut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTache" field="dateFin" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="dateFin" id="bulk_field_dateFin" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="dateFin">
            {{ ucfirst(__('PkgCreationTache::tache.dateFin')) }}
            
          </label>
                      <input
                name="dateFin"
                type="text"
                class="form-control datetimepicker"
                
                
                
                id="dateFin"
                placeholder="{{ __('PkgCreationTache::tache.dateFin') }}"
                value="{{ $itemTache ? $itemTache->dateFin : old('dateFin') }}">

          @error('dateFin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTache" field="note" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="note" id="bulk_field_note" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="note">
            {{ ucfirst(__('PkgCreationTache::tache.note')) }}
            
          </label>
              <input
        name="note"
        type="number"
        class="form-control"
        
        
        
        id="note"
        step="0.01"
        placeholder="{{ __('PkgCreationTache::tache.note') }}"
        value="{{ $itemTache ? number_format($itemTache->note, 2, '.', '') : old('note') }}">
          @error('note')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTache" field="phase_evaluation_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="phase_evaluation_id" id="bulk_field_phase_evaluation_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="phase_evaluation_id">
            {{ ucfirst(__('PkgCompetences::phaseEvaluation.singular')) }}
            
          </label>
                      <select 
            id="phase_evaluation_id" 
            
            
            
            name="phase_evaluation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($phaseEvaluations as $phaseEvaluation)
                    <option value="{{ $phaseEvaluation->id }}"
                        {{ (isset($itemTache) && $itemTache->phase_evaluation_id == $phaseEvaluation->id) || (old('phase_evaluation_id>') == $phaseEvaluation->id) ? 'selected' : '' }}>
                        {{ $phaseEvaluation }}
                    </option>
                @endforeach
            </select>
          @error('phase_evaluation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTache" field="chapitre_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="chapitre_id" id="bulk_field_chapitre_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="chapitre_id">
            {{ ucfirst(__('PkgCompetences::chapitre.singular')) }}
            
          </label>
                      <select 
            id="chapitre_id" 
            
            
            
            name="chapitre_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($chapitres as $chapitre)
                    <option value="{{ $chapitre->id }}"
                        {{ (isset($itemTache) && $itemTache->chapitre_id == $chapitre->id) || (old('chapitre_id>') == $chapitre->id) ? 'selected' : '' }}>
                        {{ $chapitre }}
                    </option>
                @endforeach
            </select>
          @error('chapitre_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemTache" field="livrables" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input {{ $canEdittache_id ? '' : 'disabled' }} type="checkbox" class="check-input" name="fields_modifiables[]" value="livrables" id="bulk_field_livrables" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="livrables">
            {{ ucfirst(__('PkgCreationProjet::livrable.plural')) }}
            
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
  
</x-form-field>


    </div>
  


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
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgCreationTache::tache.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgCreationTache::tache.singular") }} : {{$itemTache}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
