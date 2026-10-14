{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('revisionTache-show')
<div id="revisionTache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRevisionTache->realisationTache)
                  {{ $itemRevisionTache->realisationTache }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::revisionTache.tentative')) }}</small>
                  <span>
                    @if(! is_null($itemRevisionTache->tentative))
                      {{ $itemRevisionTache->tentative }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::revisionTache.date_demande')) }}</small>
                  <span>
                    @if ($itemRevisionTache->date_demande)
                    {{ \Carbon\Carbon::parse($itemRevisionTache->date_demande)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::revisionTache.message_formateur')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemRevisionTache->message_formateur) && $itemRevisionTache->message_formateur !== '')
                    {!! $itemRevisionTache->message_formateur !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::revisionTache.date_soumission')) }}</small>
                  <span>
                    @if ($itemRevisionTache->date_soumission)
                    {{ \Carbon\Carbon::parse($itemRevisionTache->date_soumission)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::revisionTache.date_validation')) }}</small>
                  <span>
                    @if ($itemRevisionTache->date_validation)
                    {{ \Carbon\Carbon::parse($itemRevisionTache->date_validation)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::revisionTache.est_valide')) }}</small>
                  @if($itemRevisionTache->est_valide)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('revisionTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-revisionTache')
          <x-action-button :entity="$itemRevisionTache" actionName="edit">
          @can('update', $itemRevisionTache)
              <a href="{{ route('revisionTaches.edit', ['revisionTache' => $itemRevisionTache->id]) }}" data-id="{{$itemRevisionTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationTache::revisionTache.singular") }} : {{ $itemRevisionTache }}';
    window.showUIId = 'revisionTache-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show