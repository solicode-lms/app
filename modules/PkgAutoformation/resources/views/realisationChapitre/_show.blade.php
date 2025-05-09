{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationChapitre-show')
<div id="realisationChapitre-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::realisationChapitre.date_debut')) }}</small>
                            
    <span>
      @if ($itemRealisationChapitre->date_debut)
        {{ \Carbon\Carbon::parse($itemRealisationChapitre->date_debut)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::realisationChapitre.date_fin')) }}</small>
                            
    <span>
      @if ($itemRealisationChapitre->date_fin)
        {{ \Carbon\Carbon::parse($itemRealisationChapitre->date_fin)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::chapitre.singular')) }}</small>
                              
      @if($itemRealisationChapitre->chapitre)
        {{ $itemRealisationChapitre->chapitre }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::realisationFormation.singular')) }}</small>
                              
      @if($itemRealisationChapitre->realisationFormation)
        {{ $itemRealisationChapitre->realisationFormation }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::etatChapitre.singular')) }}</small>
                              
      @if($itemRealisationChapitre->etatChapitre)
        {{ $itemRealisationChapitre->etatChapitre }}
      @else
        —
      @endif

          </div>
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
    window.modalTitle   = '{{ __("PkgAutoformation::realisationChapitre.singular") }} : {{ $itemRealisationChapitre }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show