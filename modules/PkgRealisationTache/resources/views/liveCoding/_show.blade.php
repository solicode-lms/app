{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('liveCoding-show')
<div id="liveCoding-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemLiveCoding->realisationTache)
                  {{ $itemLiveCoding->realisationTache }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::liveCoding.tentative')) }}</small>
                  <span>
                    @if(! is_null($itemLiveCoding->tentative))
                      {{ $itemLiveCoding->tentative }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::liveCoding.est_valide')) }}</small>
                  @if($itemLiveCoding->est_valide)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::liveCoding.date_passage')) }}</small>
                  <span>
                    @if ($itemLiveCoding->date_passage)
                    {{ \Carbon\Carbon::parse($itemLiveCoding->date_passage)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::liveCoding.commentaire')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemLiveCoding->commentaire) && $itemLiveCoding->commentaire !== '')
                    {!! $itemLiveCoding->commentaire !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('liveCodings.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-liveCoding')
          <x-action-button :entity="$itemLiveCoding" actionName="edit">
          @can('update', $itemLiveCoding)
              <a href="{{ route('liveCodings.edit', ['liveCoding' => $itemLiveCoding->id]) }}" data-id="{{$itemLiveCoding->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationTache::liveCoding.singular") }} : {{ $itemLiveCoding }}';
    window.showUIId = 'liveCoding-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show