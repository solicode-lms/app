{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('propositionReponse-show')
<div id="propositionReponse-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::propositionReponse.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemPropositionReponse->ordre))
                      {{ $itemPropositionReponse->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::propositionReponse.libelle')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemPropositionReponse->libelle) && $itemPropositionReponse->libelle !== '')
                    {!! $itemPropositionReponse->libelle !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::propositionReponse.is_correcte')) }}</small>
                  @if($itemPropositionReponse->is_correcte)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::questionLib.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemPropositionReponse->questionLib)
                  {{ $itemPropositionReponse->questionLib }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::reponseQcm.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemPropositionReponse->reponseQcms->isNotEmpty())
                  <div>
                    @foreach($itemPropositionReponse->reponseQcms as $reponseQcm)
                      <span class="badge badge-info mr-1">
                        {{ $reponseQcm }}
                      </span>
                    @endforeach
                  </div>
                  @else
                  <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('propositionReponses.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-propositionReponse')
          <x-action-button :entity="$itemPropositionReponse" actionName="edit">
          @can('update', $itemPropositionReponse)
              <a href="{{ route('propositionReponses.edit', ['propositionReponse' => $itemPropositionReponse->id]) }}" data-id="{{$itemPropositionReponse->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgQcm::propositionReponse.singular") }} : {{ $itemPropositionReponse }}';
    window.showUIId = 'propositionReponse-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show