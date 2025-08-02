{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluateur-show')
<div id="evaluateur-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluateur.nom')) }}</small>
                              @if(! is_null($itemEvaluateur->nom) && $itemEvaluateur->nom !== '')
        {{ $itemEvaluateur->nom }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluateur.prenom')) }}</small>
                              @if(! is_null($itemEvaluateur->prenom) && $itemEvaluateur->prenom !== '')
        {{ $itemEvaluateur->prenom }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluateur.email')) }}</small>
                              @if(! is_null($itemEvaluateur->email) && $itemEvaluateur->email !== '')
        {{ $itemEvaluateur->email }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluateur.organism')) }}</small>
                              @if(! is_null($itemEvaluateur->organism) && $itemEvaluateur->organism !== '')
        {{ $itemEvaluateur->organism }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluateur.telephone')) }}</small>
                              @if(! is_null($itemEvaluateur->telephone) && $itemEvaluateur->telephone !== '')
        {{ $itemEvaluateur->telephone }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutorisation::user.singular')) }}</small>
                              
      @if($itemEvaluateur->user)
        {{ $itemEvaluateur->user }}
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
            <small class="text-muted d-block">  {{ ucfirst(__('PkgEvaluateurs::evaluationRealisationProjet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgEvaluateurs::evaluationRealisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'evaluateur.show_' . $itemEvaluateur->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgEvaluateurs::evaluationRealisationTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgEvaluateurs::evaluationRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'evaluateur.show_' . $itemEvaluateur->id])
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
    window.modalTitle   = '{{ __("PkgEvaluateurs::evaluateur.singular") }} : {{ $itemEvaluateur }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show