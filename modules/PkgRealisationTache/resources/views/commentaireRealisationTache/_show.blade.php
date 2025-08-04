{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('commentaireRealisationTache-show')
<div id="commentaireRealisationTache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::commentaireRealisationTache.commentaire')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemCommentaireRealisationTache->commentaire) && $itemCommentaireRealisationTache->commentaire !== '')
                    {!! $itemCommentaireRealisationTache->commentaire !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::commentaireRealisationTache.dateCommentaire')) }}</small>
                  <span>
                    @if ($itemCommentaireRealisationTache->dateCommentaire)
                    {{ \Carbon\Carbon::parse($itemCommentaireRealisationTache->dateCommentaire)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemCommentaireRealisationTache->realisationTache)
                  {{ $itemCommentaireRealisationTache->realisationTache }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemCommentaireRealisationTache->formateur)
                  {{ $itemCommentaireRealisationTache->formateur }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemCommentaireRealisationTache->apprenant)
                  {{ $itemCommentaireRealisationTache->apprenant }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('commentaireRealisationTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-commentaireRealisationTache')
          <x-action-button :entity="$itemCommentaireRealisationTache" actionName="edit">
          @can('update', $itemCommentaireRealisationTache)
              <a href="{{ route('commentaireRealisationTaches.edit', ['commentaireRealisationTache' => $itemCommentaireRealisationTache->id]) }}" data-id="{{$itemCommentaireRealisationTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationTache::commentaireRealisationTache.singular") }} : {{ $itemCommentaireRealisationTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show