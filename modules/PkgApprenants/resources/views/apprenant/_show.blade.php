{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('apprenant-show')
<div id="apprenant-crud-show">
        <div class="card-body">
            <h6 class="text-muted mb-2">
                        <i class="fas fa-user mr-1"></i>{{ __('État Civil') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-3 col-lg-3 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemApprenant->nom) && $itemApprenant->nom !== '')
        {{ $itemApprenant->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-3 col-lg-3 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.nom_arab')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemApprenant->nom_arab) && $itemApprenant->nom_arab !== '')
        {{ $itemApprenant->nom_arab }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-3 col-lg-3 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.prenom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemApprenant->prenom) && $itemApprenant->prenom !== '')
        {{ $itemApprenant->prenom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-3 col-lg-3 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.prenom_arab')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemApprenant->prenom_arab) && $itemApprenant->prenom_arab !== '')
        {{ $itemApprenant->prenom_arab }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-3 col-lg-3 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.cin')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemApprenant->cin) && $itemApprenant->cin !== '')
        {{ $itemApprenant->cin }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-3 col-lg-3 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.date_naissance')) }}</small>
                  <span>
                    @if ($itemApprenant->date_naissance)
                    {{ \Carbon\Carbon::parse($itemApprenant->date_naissance)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-3 col-lg-3 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.sexe')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemApprenant->sexe) && $itemApprenant->sexe !== '')
        {{ $itemApprenant->sexe }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-3 col-lg-3 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::nationalite.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemApprenant->nationalite)
                  {{ $itemApprenant->nationalite }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-3 col-lg-3 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.lieu_naissance')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemApprenant->lieu_naissance) && $itemApprenant->lieu_naissance !== '')
        {{ $itemApprenant->lieu_naissance }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-9 col-lg-9 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::niveauxScolaire.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemApprenant->niveauxScolaire)
                  {{ $itemApprenant->niveauxScolaire }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-address-book mr-1"></i>{{ __('Informations de Contact') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.tele_num')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemApprenant->tele_num) && $itemApprenant->tele_num !== '')
        {{ $itemApprenant->tele_num }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemApprenant->user)
                  {{ $itemApprenant->user }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-graduation-cap mr-1"></i>{{ __('Informations Académiques') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.matricule')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemApprenant->matricule) && $itemApprenant->matricule !== '')
        {{ $itemApprenant->matricule }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
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
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.date_inscription')) }}</small>
                  <span>
                    @if ($itemApprenant->date_inscription)
                    {{ \Carbon\Carbon::parse($itemApprenant->date_inscription)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.actif')) }}</small>
                  @if($itemApprenant->actif)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            </div>
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.diplome')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemApprenant->diplome) && $itemApprenant->diplome !== '')
        {{ $itemApprenant->diplome }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.adresse')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemApprenant->adresse) && $itemApprenant->adresse !== '')
                    {!! $itemApprenant->adresse !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::sousGroupe.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemApprenant->sousGroupes->isNotEmpty())
                  <div>
                    @foreach($itemApprenant->sousGroupes as $sousGroupe)
                      <span class="badge badge-info mr-1">
                        {{ $sousGroupe }}
                      </span>
                    @endforeach
                  </div>
                  @else
                  <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            @if(auth()->user()?->can('show-realisationProjet') || auth()->user()?->can('create-realisationProjet'))
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::realisationProjet.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgRealisationProjets::realisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'apprenant.show_' . $itemApprenant->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(auth()->user()?->can('show-realisationCompetence') || auth()->user()?->can('create-realisationCompetence'))
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationCompetence.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'apprenant.show_' . $itemApprenant->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(auth()->user()?->can('show-realisationMicroCompetence') || auth()->user()?->can('create-realisationMicroCompetence'))
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationMicroCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'apprenant.show_' . $itemApprenant->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(auth()->user()?->can('show-realisationModule') || auth()->user()?->can('create-realisationModule'))
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationModule.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'apprenant.show_' . $itemApprenant->id])
                  </div>
                  </div>
            </div>
            @endif

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
    window.showUIId = 'apprenant-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show