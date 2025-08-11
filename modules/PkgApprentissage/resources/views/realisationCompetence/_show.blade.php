{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationCompetence-show')
<div id="realisationCompetence-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.date_debut')) }}</small>
                  <span>
                    @if ($itemRealisationCompetence->date_debut)
                    {{ \Carbon\Carbon::parse($itemRealisationCompetence->date_debut)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.date_fin')) }}</small>
                  <span>
                    @if ($itemRealisationCompetence->date_fin)
                    {{ \Carbon\Carbon::parse($itemRealisationCompetence->date_fin)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.progression_cache')) }}</small>
                  <span>
                  @if(! is_null($itemRealisationCompetence->progression_cache))
                  {{ number_format($itemRealisationCompetence->progression_cache, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.note_cache')) }}</small>
                  <span>
                  @if(! is_null($itemRealisationCompetence->note_cache))
                  {{ number_format($itemRealisationCompetence->note_cache, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.bareme_cache')) }}</small>
                  <span>
                  @if(! is_null($itemRealisationCompetence->bareme_cache))
                  {{ number_format($itemRealisationCompetence->bareme_cache, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.commentaire_formateur')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemRealisationCompetence->commentaire_formateur) && $itemRealisationCompetence->commentaire_formateur !== '')
                    {!! $itemRealisationCompetence->commentaire_formateur !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.dernier_update')) }}</small>
                  <span>
                    @if ($itemRealisationCompetence->dernier_update)
                    {{ \Carbon\Carbon::parse($itemRealisationCompetence->dernier_update)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationCompetence->apprenant)
                  {{ $itemRealisationCompetence->apprenant }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationCompetence->competence)
                  {{ $itemRealisationCompetence->competence }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationCompetence.singular')) }}</small>

                {{-- Affichage sous forme de badge --}}
                @if($itemRealisationCompetence->etatRealisationCompetence)
                  <x-badge 
                    :text="$itemRealisationCompetence->etatRealisationCompetence" 
                    :background="$itemRealisationCompetence->etatRealisationCompetence->sysColor->hex ?? '#6c757d'" 
                  />
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationCompetence')
          <x-action-button :entity="$itemRealisationCompetence" actionName="edit">
          @can('update', $itemRealisationCompetence)
              <a href="{{ route('realisationCompetences.edit', ['realisationCompetence' => $itemRealisationCompetence->id]) }}" data-id="{{$itemRealisationCompetence->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::realisationCompetence.singular") }} : {{ $itemRealisationCompetence }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show