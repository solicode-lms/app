{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationFormation-show')
<div id="realisationFormation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::realisationFormation.date_debut')) }}</small>
                            
    <span>
      @if ($itemRealisationFormation->date_debut)
        {{ \Carbon\Carbon::parse($itemRealisationFormation->date_debut)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::realisationFormation.date_fin')) }}</small>
                            
    <span>
      @if ($itemRealisationFormation->date_fin)
        {{ \Carbon\Carbon::parse($itemRealisationFormation->date_fin)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::formation.singular')) }}</small>
                              
      @if($itemRealisationFormation->formation)
        {{ $itemRealisationFormation->formation }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.singular')) }}</small>
                              
      @if($itemRealisationFormation->apprenant)
        {{ $itemRealisationFormation->apprenant }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::etatFormation.singular')) }}</small>
                              
      @if($itemRealisationFormation->etatFormation)
        {{ $itemRealisationFormation->etatFormation }}
      @else
        —
      @endif

          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationFormations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationFormation')
          <x-action-button :entity="$itemRealisationFormation" actionName="edit">
          @can('update', $itemRealisationFormation)
              <a href="{{ route('realisationFormations.edit', ['realisationFormation' => $itemRealisationFormation->id]) }}" data-id="{{$itemRealisationFormation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgAutoformation::realisationFormation.singular") }} : {{ $itemRealisationFormation }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show