{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('formateur-show')
<div id="formateur-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.matricule')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormateur->matricule) && $itemFormateur->matricule !== '')
          {{ $itemFormateur->matricule }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormateur->nom) && $itemFormateur->nom !== '')
          {{ $itemFormateur->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.prenom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormateur->prenom) && $itemFormateur->prenom !== '')
          {{ $itemFormateur->prenom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.prenom_arab')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormateur->prenom_arab) && $itemFormateur->prenom_arab !== '')
          {{ $itemFormateur->prenom_arab }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::specialite.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemFormateur->specialites->isNotEmpty())
          <div>
            @foreach($itemFormateur->specialites as $specialite)
              <span class="badge badge-info mr-1">
                {{ $specialite }}
              </span>
            @endforeach
          </div>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.nom_arab')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormateur->nom_arab) && $itemFormateur->nom_arab !== '')
          {{ $itemFormateur->nom_arab }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::groupe.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemFormateur->groupes->isNotEmpty())
          <div>
            @foreach($itemFormateur->groupes as $groupe)
              <span class="badge badge-info mr-1">
                {{ $groupe }}
              </span>
            @endforeach
          </div>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.email')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormateur->email) && $itemFormateur->email !== '')
          {{ $itemFormateur->email }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.tele_num')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormateur->tele_num) && $itemFormateur->tele_num !== '')
          {{ $itemFormateur->tele_num }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.adresse')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormateur->adresse) && $itemFormateur->adresse !== '')
          {{ $itemFormateur->adresse }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.diplome')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormateur->diplome) && $itemFormateur->diplome !== '')
          {{ $itemFormateur->diplome }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.echelle')) }}</small>
                              
      <span>
        @if(! is_null($itemFormateur->echelle))
          {{ $itemFormateur->echelle }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.echelon')) }}</small>
                              
      <span>
        @if(! is_null($itemFormateur->echelon))
          {{ $itemFormateur->echelon }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.profile_image')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormateur->profile_image) && $itemFormateur->profile_image !== '')
          {{ $itemFormateur->profile_image }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.singular')) }}</small>
                              
      @if($itemFormateur->user)
        {{ $itemFormateur->user }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationProjets::etatsRealisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.show_' . $itemFormateur->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgGestionTaches::commentaireRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.show_' . $itemFormateur->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgGestionTaches::etatRealisationTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgGestionTaches::etatRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.show_' . $itemFormateur->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgCreationProjet::projet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgCreationProjet::projet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.show_' . $itemFormateur->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgGestionTaches::labelRealisationTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgGestionTaches::labelRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.show_' . $itemFormateur->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgGestionTaches::prioriteTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgGestionTaches::prioriteTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.show_' . $itemFormateur->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('formateurs.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-formateur')
          <x-action-button :entity="$itemFormateur" actionName="edit">
          @can('update', $itemFormateur)
              <a href="{{ route('formateurs.edit', ['formateur' => $itemFormateur->id]) }}" data-id="{{$itemFormateur->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgFormation::formateur.singular") }} : {{ $itemFormateur }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show