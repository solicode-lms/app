{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluateur-show')
<div id="evaluateur-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgValidationProjets::evaluateur.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEvaluateur->nom) && $itemEvaluateur->nom !== '')
          {{ $itemEvaluateur->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgValidationProjets::evaluateur.prenom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEvaluateur->prenom) && $itemEvaluateur->prenom !== '')
          {{ $itemEvaluateur->prenom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgValidationProjets::evaluateur.email')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEvaluateur->email) && $itemEvaluateur->email !== '')
          {{ $itemEvaluateur->email }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgValidationProjets::evaluateur.telephone')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEvaluateur->telephone) && $itemEvaluateur->telephone !== '')
          {{ $itemEvaluateur->telephone }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgValidationProjets::evaluateur.organism')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEvaluateur->organism) && $itemEvaluateur->organism !== '')
          {{ $itemEvaluateur->organism }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>
                              
      @if($itemEvaluateur->formateur)
        {{ $itemEvaluateur->formateur }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemEvaluateur->affectationProjets->isNotEmpty())
          <div>
            @foreach($itemEvaluateur->affectationProjets as $affectationProjet)
              <span class="badge badge-info mr-1">
                {{ $affectationProjet }}
              </span>
            @endforeach
          </div>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgValidationProjets::evaluationRealisationTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgValidationProjets::evaluationRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'evaluateur.show_' . $itemEvaluateur->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('evaluateurs.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-evaluateur')
          <x-action-button :entity="$itemEvaluateur" actionName="edit">
          @can('update', $itemEvaluateur)
              <a href="{{ route('evaluateurs.edit', ['evaluateur' => $itemEvaluateur->id]) }}" data-id="{{$itemEvaluateur->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgValidationProjets::evaluateur.singular") }} : {{ $itemEvaluateur }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show