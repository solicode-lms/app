{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationChapitre-show')
<div id="realisationChapitre-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::chapitre.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationChapitre->chapitre)
                  {{ $itemRealisationChapitre->chapitre }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationChapitre.etat_realisation_chapitre_id')) }}</small>

                {{-- Affichage sous forme de badge --}}
                @if($itemRealisationChapitre->etatRealisationChapitre)
                  <x-badge 
                    :text="$itemRealisationChapitre->etatRealisationChapitre" 
                    :background="$itemRealisationChapitre->etatRealisationChapitre->sysColor->hex ?? '#6c757d'" 
                  />
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationChapitre.date_debut')) }}</small>
                  <span>
                    @if ($itemRealisationChapitre->date_debut)
                    {{ \Carbon\Carbon::parse($itemRealisationChapitre->date_debut)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationChapitre.date_fin')) }}</small>
                  <span>
                    @if ($itemRealisationChapitre->date_fin)
                    {{ \Carbon\Carbon::parse($itemRealisationChapitre->date_fin)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUa.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationChapitre->realisationUa)
                  {{ $itemRealisationChapitre->realisationUa }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationChapitre->realisationTache)
                  {{ $itemRealisationChapitre->realisationTache }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationChapitre.commentaire_formateur')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemRealisationChapitre->commentaire_formateur) && $itemRealisationChapitre->commentaire_formateur !== '')
                    {!! $itemRealisationChapitre->commentaire_formateur !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationChapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationChapitre')
          <x-action-button :entity="$itemRealisationChapitre" actionName="edit">
          @can('update', $itemRealisationChapitre)
              <a href="{{ route('realisationChapitres.edit', ['realisationChapitre' => $itemRealisationChapitre->id]) }}" data-id="{{$itemRealisationChapitre->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::realisationChapitre.singular") }} : {{ $itemRealisationChapitre }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show