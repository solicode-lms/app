{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('transfertCompetence-show')
<div id="transfertCompetence-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.singular')) }}</small>
                              
      @if($itemTransfertCompetence->competence)
        {{ $itemTransfertCompetence->competence }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::niveauDifficulte.singular')) }}</small>
                              
      @if($itemTransfertCompetence->niveauDifficulte)
        {{ $itemTransfertCompetence->niveauDifficulte }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::technology.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemTransfertCompetence->technologies->isNotEmpty())
          <div>
            @foreach($itemTransfertCompetence->technologies as $technology)
              <span class="badge badge-info mr-1">
                {{ $technology }}
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
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::transfertCompetence.note')) }}</small>
                              
      <span>
        @if(! is_null($itemTransfertCompetence->note))
          {{ number_format($itemTransfertCompetence->note, 2, '.', '') }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::validation.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationProjets::validation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'transfertCompetence.show_' . $itemTransfertCompetence->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</small>
                              
      @if($itemTransfertCompetence->projet)
        {{ $itemTransfertCompetence->projet }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::transfertCompetence.question')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemTransfertCompetence->question) && $itemTransfertCompetence->question !== '')
    {!! $itemTransfertCompetence->question !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('transfertCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-transfertCompetence')
          <x-action-button :entity="$itemTransfertCompetence" actionName="edit">
          @can('update', $itemTransfertCompetence)
              <a href="{{ route('transfertCompetences.edit', ['transfertCompetence' => $itemTransfertCompetence->id]) }}" data-id="{{$itemTransfertCompetence->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCreationProjet::transfertCompetence.singular") }} : {{ $itemTransfertCompetence }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show