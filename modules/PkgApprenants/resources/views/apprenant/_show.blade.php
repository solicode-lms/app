{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('apprenant-show')
<div id="apprenant-crud-show">
        <div class="card-body">
            <h6 class="text-muted mb-2">
                        <i class="fas fa-user mr-1"></i>{{ __('État Civil') }}
            </h6>
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemApprenant->nom) && $itemApprenant->nom !== '')
          {{ $itemApprenant->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.nom_arab')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemApprenant->nom_arab) && $itemApprenant->nom_arab !== '')
          {{ $itemApprenant->nom_arab }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.prenom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemApprenant->prenom) && $itemApprenant->prenom !== '')
          {{ $itemApprenant->prenom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.prenom_arab')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemApprenant->prenom_arab) && $itemApprenant->prenom_arab !== '')
          {{ $itemApprenant->prenom_arab }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.cin')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemApprenant->cin) && $itemApprenant->cin !== '')
          {{ $itemApprenant->cin }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.date_naissance')) }}</small>
                            
    <span>
      @if ($itemApprenant->date_naissance)
        {{ \Carbon\Carbon::parse($itemApprenant->date_naissance)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.sexe')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemApprenant->sexe) && $itemApprenant->sexe !== '')
          {{ $itemApprenant->sexe }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::nationalite.singular')) }}</small>
                              
      @if($itemApprenant->nationalite)
        {{ $itemApprenant->nationalite }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.lieu_naissance')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemApprenant->lieu_naissance) && $itemApprenant->lieu_naissance !== '')
          {{ $itemApprenant->lieu_naissance }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-9 col-lg-9 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::niveauxScolaire.singular')) }}</small>
                              
      @if($itemApprenant->niveauxScolaire)
        {{ $itemApprenant->niveauxScolaire }}
      @else
        —
      @endif

          </div>
      </div>
  


            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-address-book mr-1"></i>{{ __('Informations de Contact') }}
            </h6>
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.tele_num')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemApprenant->tele_num) && $itemApprenant->tele_num !== '')
          {{ $itemApprenant->tele_num }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.singular')) }}</small>
                              
      @if($itemApprenant->user)
        {{ $itemApprenant->user }}
      @else
        —
      @endif

          </div>
      </div>
  


            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-graduation-cap mr-1"></i>{{ __('Informations Académiques') }}
            </h6>
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.matricule')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemApprenant->matricule) && $itemApprenant->matricule !== '')
          {{ $itemApprenant->matricule }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::groupe.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemApprenant->groupes->isNotEmpty())
          <div>
            @foreach($itemApprenant->groupes as $groupe)
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
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.date_inscription')) }}</small>
                            
    <span>
      @if ($itemApprenant->date_inscription)
        {{ \Carbon\Carbon::parse($itemApprenant->date_inscription)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.actif')) }}</small>
                              
      @if($itemApprenant->actif)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('apprenants.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-apprenant')
          <x-action-button :entity="$itemApprenant" actionName="edit">
          @can('update', $itemApprenant)
              <a href="{{ route('apprenants.edit', ['apprenant' => $itemApprenant->id]) }}" data-id="{{$itemApprenant->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprenants::apprenant.singular") }} : {{ $itemApprenant }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show