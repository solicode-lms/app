{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('reponseQcm-show')
<div id="reponseQcm-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::realisationQcm.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemReponseQcm->realisationQcm)
                  {{ $itemReponseQcm->realisationQcm }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::question.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemReponseQcm->question)
                  {{ $itemReponseQcm->question }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::reponseQcm.date_reponse')) }}</small>
                  <span>
                    @if ($itemReponseQcm->date_reponse)
                    {{ \Carbon\Carbon::parse($itemReponseQcm->date_reponse)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::propositionReponse.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemReponseQcm->propositionReponses->isNotEmpty())
                  <div>
                    @foreach($itemReponseQcm->propositionReponses as $propositionReponse)
                      <span class="badge badge-info mr-1">
                        {{ $propositionReponse }}
                      </span>
                    @endforeach
                  </div>
                  @else
                  <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaPrototype.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemReponseQcm->realisationUaPrototypes->isNotEmpty())
                  <div>
                    @foreach($itemReponseQcm->realisationUaPrototypes as $realisationUaPrototype)
                      <span class="badge badge-info mr-1">
                        {{ $realisationUaPrototype }}
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
          <a href="{{ route('reponseQcms.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-reponseQcm')
          <x-action-button :entity="$itemReponseQcm" actionName="edit">
          @can('update', $itemReponseQcm)
              <a href="{{ route('reponseQcms.edit', ['reponseQcm' => $itemReponseQcm->id]) }}" data-id="{{$itemReponseQcm->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgQcm::reponseQcm.singular") }} : {{ $itemReponseQcm }}';
    window.showUIId = 'reponseQcm-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show