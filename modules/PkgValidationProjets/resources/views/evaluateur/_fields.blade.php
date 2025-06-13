{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluateur-form')
<form 
    class="crud-form custom-form context-state container" 
    id="evaluateurForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('evaluateurs.bulkUpdate') : ($itemEvaluateur->id ? route('evaluateurs.update', $itemEvaluateur->id) : route('evaluateurs.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemEvaluateur->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($evaluateur_ids))
        @foreach ($evaluateur_ids as $id)
            <input type="hidden" name="evaluateur_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemEvaluateur" field="nom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgValidationProjets::evaluateur.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgValidationProjets::evaluateur.nom') }}"
                value="{{ $itemEvaluateur ? $itemEvaluateur->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEvaluateur" field="prenom" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="prenom" id="bulk_field_prenom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="prenom">
            {{ ucfirst(__('PkgValidationProjets::evaluateur.prenom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="prenom"
                type="input"
                class="form-control"
                required
                
                
                id="prenom"
                placeholder="{{ __('PkgValidationProjets::evaluateur.prenom') }}"
                value="{{ $itemEvaluateur ? $itemEvaluateur->prenom : old('prenom') }}">
          @error('prenom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEvaluateur" field="email" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="email" id="bulk_field_email" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="email">
            {{ ucfirst(__('PkgValidationProjets::evaluateur.email')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="email"
                type="input"
                class="form-control"
                required
                
                
                id="email"
                placeholder="{{ __('PkgValidationProjets::evaluateur.email') }}"
                value="{{ $itemEvaluateur ? $itemEvaluateur->email : old('email') }}">
          @error('email')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEvaluateur" field="organism" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="organism" id="bulk_field_organism" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="organism">
            {{ ucfirst(__('PkgValidationProjets::evaluateur.organism')) }}
            
          </label>
           <input
                name="organism"
                type="input"
                class="form-control"
                
                
                
                id="organism"
                placeholder="{{ __('PkgValidationProjets::evaluateur.organism') }}"
                value="{{ $itemEvaluateur ? $itemEvaluateur->organism : old('organism') }}">
          @error('organism')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEvaluateur" field="telephone" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="telephone" id="bulk_field_telephone" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="telephone">
            {{ ucfirst(__('PkgValidationProjets::evaluateur.telephone')) }}
            
          </label>
           <input
                name="telephone"
                type="input"
                class="form-control"
                
                
                
                id="telephone"
                placeholder="{{ __('PkgValidationProjets::evaluateur.telephone') }}"
                value="{{ $itemEvaluateur ? $itemEvaluateur->telephone : old('telephone') }}">
          @error('telephone')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEvaluateur" field="user_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="user_id" id="bulk_field_user_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="user_id">
            {{ ucfirst(__('PkgAutorisation::user.singular')) }}
            
          </label>
                      <select 
            id="user_id" 
            
            
            
            name="user_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ (isset($itemEvaluateur) && $itemEvaluateur->user_id == $user->id) || (old('user_id>') == $user->id) ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
          @error('user_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemEvaluateur" field="affectationProjets" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="affectationProjets" id="bulk_field_affectationProjets" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="affectationProjets">
            {{ ucfirst(__('PkgRealisationProjets::affectationProjet.plural')) }}
            
          </label>
                      <select
                id="affectationProjets"
                name="affectationProjets[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($affectationProjets as $affectationProjet)
                    <option value="{{ $affectationProjet->id }}"
                        {{ (isset($itemEvaluateur) && $itemEvaluateur->affectationProjets && $itemEvaluateur->affectationProjets->contains('id', $affectationProjet->id)) || (is_array(old('affectationProjets')) && in_array($affectationProjet->id, old('affectationProjets'))) ? 'selected' : '' }}>
                        {{ $affectationProjet }}
                    </option>
                @endforeach
            </select>
          @error('affectationProjets')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('evaluateurs.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemEvaluateur->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgValidationProjets::evaluateur.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgValidationProjets::evaluateur.singular") }} : {{$itemEvaluateur}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
