{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('affectationProjet-show')
<div id="affectationProjet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</small>
                              
      @if($itemAffectationProjet->projet)
        {{ $itemAffectationProjet->projet }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::groupe.singular')) }}</small>
                              
      @if($itemAffectationProjet->groupe)
        {{ $itemAffectationProjet->groupe }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::sousGroupe.singular')) }}</small>
                              
      @if($itemAffectationProjet->sousGroupe)
        {{ $itemAffectationProjet->sousGroupe }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::anneeFormation.singular')) }}</small>
                              
      @if($itemAffectationProjet->anneeFormation)
        {{ $itemAffectationProjet->anneeFormation }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_debut')) }}</small>
                            
    <span>
      @if ($itemAffectationProjet->date_debut)
        {{ \Carbon\Carbon::parse($itemAffectationProjet->date_debut)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_fin')) }}</small>
                            
    <span>
      @if ($itemAffectationProjet->date_fin)
        {{ \Carbon\Carbon::parse($itemAffectationProjet->date_fin)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.echelle_note_cible')) }}</small>
                              
      <span>
        @if(! is_null($itemAffectationProjet->echelle_note_cible))
          {{ $itemAffectationProjet->echelle_note_cible }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::evaluateur.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemAffectationProjet->evaluateurs->isNotEmpty())
          <div>
            @foreach($itemAffectationProjet->evaluateurs as $evaluateur)
              <span class="badge badge-info mr-1">
                {{ $evaluateur }}
              </span>
            @endforeach
          </div>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::realisationProjet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationProjets::realisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'affectationProjet.show_' . $itemAffectationProjet->id])
            </div>
          </div>
      </div>
   

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::affectationProjet.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemAffectationProjet->description) && $itemAffectationProjet->description !== '')
    {!! $itemAffectationProjet->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('affectationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-affectationProjet')
          <x-action-button :entity="$itemAffectationProjet" actionName="edit">
          @can('update', $itemAffectationProjet)
              <a href="{{ route('affectationProjets.edit', ['affectationProjet' => $itemAffectationProjet->id]) }}" data-id="{{$itemAffectationProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationProjets::affectationProjet.singular") }} : {{ $itemAffectationProjet }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show