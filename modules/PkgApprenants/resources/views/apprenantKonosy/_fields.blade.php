{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('apprenantKonosy-form')
<form 
    class="crud-form custom-form context-state container" 
    id="apprenantKonosyForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('apprenantKonosies.bulkUpdate') : ($itemApprenantKonosy->id ? route('apprenantKonosys.update', $itemApprenantKonosy->id) : route('apprenantKonosys.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemApprenantKonosy->id)
        @method('PUT')
    @endif
    @if (!empty($bulkEdit) && !empty($apprenantKonosy_ids))
        @foreach ($apprenantKonosy_ids as $id)
            <input type="hidden" name="apprenantKonosy_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="MatriculeEtudiant" id="bulk_field_MatriculeEtudiant" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="MatriculeEtudiant">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.MatriculeEtudiant')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="MatriculeEtudiant"
                type="input"
                class="form-control"
                required
                
                
                id="MatriculeEtudiant"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.MatriculeEtudiant') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->MatriculeEtudiant : old('MatriculeEtudiant') }}">
          @error('MatriculeEtudiant')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="Nom" id="bulk_field_Nom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="Nom">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.Nom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="Nom"
                type="input"
                class="form-control"
                required
                
                
                id="Nom"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.Nom') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Nom : old('Nom') }}">
          @error('Nom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="Prenom" id="bulk_field_Prenom" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="Prenom">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.Prenom')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="Prenom"
                type="input"
                class="form-control"
                required
                
                
                id="Prenom"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.Prenom') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Prenom : old('Prenom') }}">
          @error('Prenom')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="Sexe" id="bulk_field_Sexe" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="Sexe">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.Sexe')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="Sexe"
                type="input"
                class="form-control"
                required
                
                
                id="Sexe"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.Sexe') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Sexe : old('Sexe') }}">
          @error('Sexe')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="EtudiantActif" id="bulk_field_EtudiantActif" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="EtudiantActif">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.EtudiantActif')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="EtudiantActif"
                type="input"
                class="form-control"
                required
                
                
                id="EtudiantActif"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.EtudiantActif') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->EtudiantActif : old('EtudiantActif') }}">
          @error('EtudiantActif')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="Diplome" id="bulk_field_Diplome" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="Diplome">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.Diplome')) }}
            
          </label>
           <input
                name="Diplome"
                type="input"
                class="form-control"
                
                
                
                id="Diplome"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.Diplome') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Diplome : old('Diplome') }}">
          @error('Diplome')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="Principale" id="bulk_field_Principale" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="Principale">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.Principale')) }}
            
          </label>
           <input
                name="Principale"
                type="input"
                class="form-control"
                
                
                
                id="Principale"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.Principale') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Principale : old('Principale') }}">
          @error('Principale')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="LibelleLong" id="bulk_field_LibelleLong" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="LibelleLong">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.LibelleLong')) }}
            
          </label>
           <input
                name="LibelleLong"
                type="input"
                class="form-control"
                
                
                
                id="LibelleLong"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.LibelleLong') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->LibelleLong : old('LibelleLong') }}">
          @error('LibelleLong')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="CodeDiplome" id="bulk_field_CodeDiplome" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="CodeDiplome">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.CodeDiplome')) }}
            
          </label>
           <input
                name="CodeDiplome"
                type="input"
                class="form-control"
                
                
                
                id="CodeDiplome"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.CodeDiplome') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->CodeDiplome : old('CodeDiplome') }}">
          @error('CodeDiplome')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="DateNaissance" id="bulk_field_DateNaissance" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="DateNaissance">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.DateNaissance')) }}
            
          </label>
           <input
                name="DateNaissance"
                type="input"
                class="form-control"
                
                
                
                id="DateNaissance"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.DateNaissance') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->DateNaissance : old('DateNaissance') }}">
          @error('DateNaissance')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="DateInscription" id="bulk_field_DateInscription" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="DateInscription">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.DateInscription')) }}
            
          </label>
           <input
                name="DateInscription"
                type="input"
                class="form-control"
                
                
                
                id="DateInscription"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.DateInscription') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->DateInscription : old('DateInscription') }}">
          @error('DateInscription')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="LieuNaissance" id="bulk_field_LieuNaissance" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="LieuNaissance">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.LieuNaissance')) }}
            
          </label>
           <input
                name="LieuNaissance"
                type="input"
                class="form-control"
                
                
                
                id="LieuNaissance"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.LieuNaissance') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->LieuNaissance : old('LieuNaissance') }}">
          @error('LieuNaissance')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="CIN" id="bulk_field_CIN" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="CIN">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.CIN')) }}
            
          </label>
           <input
                name="CIN"
                type="input"
                class="form-control"
                
                
                
                id="CIN"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.CIN') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->CIN : old('CIN') }}">
          @error('CIN')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="NTelephone" id="bulk_field_NTelephone" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="NTelephone">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.NTelephone')) }}
            
          </label>
           <input
                name="NTelephone"
                type="input"
                class="form-control"
                
                
                
                id="NTelephone"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.NTelephone') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->NTelephone : old('NTelephone') }}">
          @error('NTelephone')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-12">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="Adresse" id="bulk_field_Adresse" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="Adresse">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.Adresse')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="Adresse"
                class="form-control richText"
                
                
                
                id="Adresse"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.Adresse') }}">{{ $itemApprenantKonosy ? $itemApprenantKonosy->Adresse : old('Adresse') }}</textarea>
          @error('Adresse')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="Nationalite" id="bulk_field_Nationalite" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="Nationalite">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.Nationalite')) }}
            
          </label>
           <input
                name="Nationalite"
                type="input"
                class="form-control"
                
                
                
                id="Nationalite"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.Nationalite') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Nationalite : old('Nationalite') }}">
          @error('Nationalite')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="Nom_Arabe" id="bulk_field_Nom_Arabe" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="Nom_Arabe">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.Nom_Arabe')) }}
            
          </label>
           <input
                name="Nom_Arabe"
                type="input"
                class="form-control"
                
                
                
                id="Nom_Arabe"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.Nom_Arabe') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Nom_Arabe : old('Nom_Arabe') }}">
          @error('Nom_Arabe')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="Prenom_Arabe" id="bulk_field_Prenom_Arabe" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="Prenom_Arabe">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.Prenom_Arabe')) }}
            
          </label>
           <input
                name="Prenom_Arabe"
                type="input"
                class="form-control"
                
                
                
                id="Prenom_Arabe"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.Prenom_Arabe') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->Prenom_Arabe : old('Prenom_Arabe') }}">
          @error('Prenom_Arabe')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          @if (!empty($bulkEdit))
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="NiveauScolaire" id="bulk_field_NiveauScolaire" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="NiveauScolaire">
            {{ ucfirst(__('PkgApprenants::apprenantKonosy.NiveauScolaire')) }}
            
          </label>
           <input
                name="NiveauScolaire"
                type="input"
                class="form-control"
                
                
                
                id="NiveauScolaire"
                placeholder="{{ __('PkgApprenants::apprenantKonosy.NiveauScolaire') }}"
                value="{{ $itemApprenantKonosy ? $itemApprenantKonosy->NiveauScolaire : old('NiveauScolaire') }}">
          @error('NiveauScolaire')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  

    </div>

    <div class="card-footer">
        <a href="{{ route('apprenantKonosies.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemApprenantKonosy->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if (!empty($bulkEdit))
        window.modalTitle = '{{__("PkgApprenants::apprenantKonosy.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgApprenants::apprenantKonosy.singular") }} : {{$itemApprenantKonosy}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
