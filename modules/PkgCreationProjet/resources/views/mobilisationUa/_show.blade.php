{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('mobilisationUa-show')
<div id="mobilisationUa-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::uniteApprentissage.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemMobilisationUa->uniteApprentissage)
                  {{ $itemMobilisationUa->uniteApprentissage }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::mobilisationUa.bareme_evaluation_projet')) }}</small>
                  <span>
                  @if(! is_null($itemMobilisationUa->bareme_evaluation_projet))
                  {{ number_format($itemMobilisationUa->bareme_evaluation_projet, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::mobilisationUa.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemMobilisationUa->description) && $itemMobilisationUa->description !== '')
                    {!! $itemMobilisationUa->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemMobilisationUa->projet)
                  {{ $itemMobilisationUa->projet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('mobilisationUas.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-mobilisationUa')
          <x-action-button :entity="$itemMobilisationUa" actionName="edit">
          @can('update', $itemMobilisationUa)
              <a href="{{ route('mobilisationUas.edit', ['mobilisationUa' => $itemMobilisationUa->id]) }}" data-id="{{$itemMobilisationUa->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCreationProjet::mobilisationUa.singular") }} : {{ $itemMobilisationUa }}';
    window.showUIId = 'mobilisationUa-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show