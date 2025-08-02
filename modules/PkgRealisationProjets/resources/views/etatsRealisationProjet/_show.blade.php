{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatsRealisationProjet-show')
<div id="etatsRealisationProjet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemEtatsRealisationProjet->ordre))
          {{ $itemEtatsRealisationProjet->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.titre')) }}</small>
                              @if(! is_null($itemEtatsRealisationProjet->titre) && $itemEtatsRealisationProjet->titre !== '')
        {{ $itemEtatsRealisationProjet->titre }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.code')) }}</small>
                              @if(! is_null($itemEtatsRealisationProjet->code) && $itemEtatsRealisationProjet->code !== '')
        {{ $itemEtatsRealisationProjet->code }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEtatsRealisationProjet->description) && $itemEtatsRealisationProjet->description !== '')
    {!! $itemEtatsRealisationProjet->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemEtatsRealisationProjet->sysColor)
        @php
          $related = $itemEtatsRealisationProjet->sysColor;
        @endphp
        <span 
          class="badge" 
          style="background-color: {{ $related->hex }}; color: #fff;"
        >
          {{ $related }}
        </span>
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.is_editable_by_formateur')) }}</small>
                              
      @if($itemEtatsRealisationProjet->is_editable_by_formateur)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('etatsRealisationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-etatsRealisationProjet')
          <x-action-button :entity="$itemEtatsRealisationProjet" actionName="edit">
          @can('update', $itemEtatsRealisationProjet)
              <a href="{{ route('etatsRealisationProjets.edit', ['etatsRealisationProjet' => $itemEtatsRealisationProjet->id]) }}" data-id="{{$itemEtatsRealisationProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationProjets::etatsRealisationProjet.singular") }} : {{ $itemEtatsRealisationProjet }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show