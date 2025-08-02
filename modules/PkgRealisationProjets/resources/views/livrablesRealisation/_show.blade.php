{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrablesRealisation-show')
<div id="livrablesRealisation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::livrable.singular')) }}</small>
                              
      @if($itemLivrablesRealisation->livrable)
        {{ $itemLivrablesRealisation->livrable }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.lien')) }}</small>
                              @if(! is_null($itemLivrablesRealisation->lien) && $itemLivrablesRealisation->lien !== '')
        <a href="{{ $itemLivrablesRealisation->lien }}" target="_blank">
          <i class="fas fa-link mr-1"></i>
          {{ $itemLivrablesRealisation->lien }}
        </a>
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.titre')) }}</small>
                              @if(! is_null($itemLivrablesRealisation->titre) && $itemLivrablesRealisation->titre !== '')
        {{ $itemLivrablesRealisation->titre }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemLivrablesRealisation->description) && $itemLivrablesRealisation->description !== '')
    {!! $itemLivrablesRealisation->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}</small>
                              
      @if($itemLivrablesRealisation->realisationProjet)
        {{ $itemLivrablesRealisation->realisationProjet }}
      @else
        —
      @endif

          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('livrablesRealisations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-livrablesRealisation')
          <x-action-button :entity="$itemLivrablesRealisation" actionName="edit">
          @can('update', $itemLivrablesRealisation)
              <a href="{{ route('livrablesRealisations.edit', ['livrablesRealisation' => $itemLivrablesRealisation->id]) }}" data-id="{{$itemLivrablesRealisation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationProjets::livrablesRealisation.singular") }} : {{ $itemLivrablesRealisation }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show