{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('apprenantKonosy-show')
<div id="apprenantKonosy-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.MatriculeEtudiant')) }}</small>
                              @if(! is_null($itemApprenantKonosy->MatriculeEtudiant) && $itemApprenantKonosy->MatriculeEtudiant !== '')
        {{ $itemApprenantKonosy->MatriculeEtudiant }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Nom')) }}</small>
                              @if(! is_null($itemApprenantKonosy->Nom) && $itemApprenantKonosy->Nom !== '')
        {{ $itemApprenantKonosy->Nom }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Prenom')) }}</small>
                              @if(! is_null($itemApprenantKonosy->Prenom) && $itemApprenantKonosy->Prenom !== '')
        {{ $itemApprenantKonosy->Prenom }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Sexe')) }}</small>
                              @if(! is_null($itemApprenantKonosy->Sexe) && $itemApprenantKonosy->Sexe !== '')
        {{ $itemApprenantKonosy->Sexe }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.EtudiantActif')) }}</small>
                              @if(! is_null($itemApprenantKonosy->EtudiantActif) && $itemApprenantKonosy->EtudiantActif !== '')
        {{ $itemApprenantKonosy->EtudiantActif }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Diplome')) }}</small>
                              @if(! is_null($itemApprenantKonosy->Diplome) && $itemApprenantKonosy->Diplome !== '')
        {{ $itemApprenantKonosy->Diplome }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Principale')) }}</small>
                              @if(! is_null($itemApprenantKonosy->Principale) && $itemApprenantKonosy->Principale !== '')
        {{ $itemApprenantKonosy->Principale }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.LibelleLong')) }}</small>
                              @if(! is_null($itemApprenantKonosy->LibelleLong) && $itemApprenantKonosy->LibelleLong !== '')
        {{ $itemApprenantKonosy->LibelleLong }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.CodeDiplome')) }}</small>
                              @if(! is_null($itemApprenantKonosy->CodeDiplome) && $itemApprenantKonosy->CodeDiplome !== '')
        {{ $itemApprenantKonosy->CodeDiplome }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.DateNaissance')) }}</small>
                              @if(! is_null($itemApprenantKonosy->DateNaissance) && $itemApprenantKonosy->DateNaissance !== '')
        {{ $itemApprenantKonosy->DateNaissance }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.DateInscription')) }}</small>
                              @if(! is_null($itemApprenantKonosy->DateInscription) && $itemApprenantKonosy->DateInscription !== '')
        {{ $itemApprenantKonosy->DateInscription }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.LieuNaissance')) }}</small>
                              @if(! is_null($itemApprenantKonosy->LieuNaissance) && $itemApprenantKonosy->LieuNaissance !== '')
        {{ $itemApprenantKonosy->LieuNaissance }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.CIN')) }}</small>
                              @if(! is_null($itemApprenantKonosy->CIN) && $itemApprenantKonosy->CIN !== '')
        {{ $itemApprenantKonosy->CIN }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.NTelephone')) }}</small>
                              @if(! is_null($itemApprenantKonosy->NTelephone) && $itemApprenantKonosy->NTelephone !== '')
        {{ $itemApprenantKonosy->NTelephone }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Adresse')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemApprenantKonosy->Adresse) && $itemApprenantKonosy->Adresse !== '')
    {!! $itemApprenantKonosy->Adresse !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Nationalite')) }}</small>
                              @if(! is_null($itemApprenantKonosy->Nationalite) && $itemApprenantKonosy->Nationalite !== '')
        {{ $itemApprenantKonosy->Nationalite }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Nom_Arabe')) }}</small>
                              @if(! is_null($itemApprenantKonosy->Nom_Arabe) && $itemApprenantKonosy->Nom_Arabe !== '')
        {{ $itemApprenantKonosy->Nom_Arabe }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.Prenom_Arabe')) }}</small>
                              @if(! is_null($itemApprenantKonosy->Prenom_Arabe) && $itemApprenantKonosy->Prenom_Arabe !== '')
        {{ $itemApprenantKonosy->Prenom_Arabe }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenantKonosy.NiveauScolaire')) }}</small>
                              @if(! is_null($itemApprenantKonosy->NiveauScolaire) && $itemApprenantKonosy->NiveauScolaire !== '')
        {{ $itemApprenantKonosy->NiveauScolaire }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('apprenantKonosies.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-apprenantKonosy')
          <x-action-button :entity="$itemApprenantKonosy" actionName="edit">
          @can('update', $itemApprenantKonosy)
              <a href="{{ route('apprenantKonosies.edit', ['apprenantKonosy' => $itemApprenantKonosy->id]) }}" data-id="{{$itemApprenantKonosy->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprenants::apprenantKonosy.singular") }} : {{ $itemApprenantKonosy }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show