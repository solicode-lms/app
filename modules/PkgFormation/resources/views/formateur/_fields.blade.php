{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('formateur-form')
<form 
    class="crud-form custom-form context-state container" 
    id="formateurForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('formateurs.bulkUpdate') : ($itemFormateur->id ? route('formateurs.update', $itemFormateur->id) : route('formateurs.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemFormateur->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($formateur_ids))
        @foreach ($formateur_ids as $id)
            <input type="hidden" name="formateur_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :entity="$itemFormateur" field="matricule">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="matricule" id="bulk_field_matricule" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="matricule">
            {{ ucfirst(__('PkgFormation::formateur.matricule')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="matricule"
                type="input"
                class="form-control"
                required
                
                
                id="matricule"
                placeholder="{{ __('PkgFormation::formateur.matricule') }}"
                value="{{ $itemFormateur ? $itemFormateur->matricule : old('matricule') }}">
          @error('matricule')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemFormateur" field="nom">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nom" id="bulk_field_nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nom">
            {{ ucfirst(__('PkgFormation::formateur.nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="nom"
                type="input"
                class="form-control"
                required
                
                
                id="nom"
                placeholder="{{ __('PkgFormation::formateur.nom') }}"
                value="{{ $itemFormateur ? $itemFormateur->nom : old('nom') }}">
          @error('nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemFormateur" field="prenom">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="prenom" id="bulk_field_prenom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="prenom">
            {{ ucfirst(__('PkgFormation::formateur.prenom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="prenom"
                type="input"
                class="form-control"
                required
                
                
                id="prenom"
                placeholder="{{ __('PkgFormation::formateur.prenom') }}"
                value="{{ $itemFormateur ? $itemFormateur->prenom : old('prenom') }}">
          @error('prenom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemFormateur" field="specialites">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="specialites" id="bulk_field_specialites" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="specialites">
            {{ ucfirst(__('PkgFormation::specialite.plural')) }}
            
          </label>
                      <select
                id="specialites"
                name="specialites[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($specialites as $specialite)
                    <option value="{{ $specialite->id }}"
                        {{ (isset($itemFormateur) && $itemFormateur->specialites && $itemFormateur->specialites->contains('id', $specialite->id)) || (is_array(old('specialites')) && in_array($specialite->id, old('specialites'))) ? 'selected' : '' }}>
                        {{ $specialite }}
                    </option>
                @endforeach
            </select>
          @error('specialites')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemFormateur" field="groupes">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="groupes" id="bulk_field_groupes" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="groupes">
            {{ ucfirst(__('PkgApprenants::groupe.plural')) }}
            
          </label>
                      <select
                id="groupes"
                name="groupes[]"
                class="form-control select2"
                
                
                multiple="multiple">
               
                @foreach ($groupes as $groupe)
                    <option value="{{ $groupe->id }}"
                        {{ (isset($itemFormateur) && $itemFormateur->groupes && $itemFormateur->groupes->contains('id', $groupe->id)) || (is_array(old('groupes')) && in_array($groupe->id, old('groupes'))) ? 'selected' : '' }}>
                        {{ $groupe }}
                    </option>
                @endforeach
            </select>
          @error('groupes')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemFormateur" field="email">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="email" id="bulk_field_email" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="email">
            {{ ucfirst(__('PkgFormation::formateur.email')) }}
            
          </label>
           <input
                name="email"
                type="input"
                class="form-control"
                
                
                
                id="email"
                placeholder="{{ __('PkgFormation::formateur.email') }}"
                value="{{ $itemFormateur ? $itemFormateur->email : old('email') }}">
          @error('email')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemFormateur" field="tele_num">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="tele_num" id="bulk_field_tele_num" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="tele_num">
            {{ ucfirst(__('PkgFormation::formateur.tele_num')) }}
            
          </label>
           <input
                name="tele_num"
                type="input"
                class="form-control"
                
                
                
                id="tele_num"
                placeholder="{{ __('PkgFormation::formateur.tele_num') }}"
                value="{{ $itemFormateur ? $itemFormateur->tele_num : old('tele_num') }}">
          @error('tele_num')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :entity="$itemFormateur" field="user_id">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
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
                        {{ (isset($itemFormateur) && $itemFormateur->user_id == $user->id) || (old('user_id>') == $user->id) ? 'selected' : '' }}>
                        {{ $user }}
                    </option>
                @endforeach
            </select>
          @error('user_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('formateurs.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemFormateur->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgFormation::formateur.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgFormation::formateur.singular") }} : {{$itemFormateur}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
