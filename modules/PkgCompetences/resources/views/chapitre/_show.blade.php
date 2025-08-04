{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('chapitre-show')
<div id="chapitre-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::chapitre.code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemChapitre->code) && $itemChapitre->code !== '')
        {{ $itemChapitre->code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::chapitre.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemChapitre->nom) && $itemChapitre->nom !== '')
        {{ $itemChapitre->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::uniteApprentissage.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemChapitre->uniteApprentissage)
                  {{ $itemChapitre->uniteApprentissage }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::chapitre.duree_en_heure')) }}</small>
                  <span>
                  @if(! is_null($itemChapitre->duree_en_heure))
                  {{ number_format($itemChapitre->duree_en_heure, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::chapitre.isOfficiel')) }}</small>
                  @if($itemChapitre->isOfficiel)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::chapitre.lien')) }}</small>
    {{-- Lien cliquable --}}
    @if(!is_null($itemChapitre->lien) && $itemChapitre->lien !== '')
        <a href="{{ $itemChapitre->lien }}" target="_blank">
            <i class="fas fa-link mr-1"></i>
            {{ $itemChapitre->lien }}
        </a>
    @else
        <span class="text-muted">—</span>
    @endif

                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::chapitre.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemChapitre->description) && $itemChapitre->description !== '')
                    {!! $itemChapitre->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('chapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-chapitre')
          <x-action-button :entity="$itemChapitre" actionName="edit">
          @can('update', $itemChapitre)
              <a href="{{ route('chapitres.edit', ['chapitre' => $itemChapitre->id]) }}" data-id="{{$itemChapitre->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCompetences::chapitre.singular") }} : {{ $itemChapitre }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show