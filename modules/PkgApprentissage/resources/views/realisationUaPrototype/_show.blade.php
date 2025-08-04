{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationUaPrototype-show')
<div id="realisationUaPrototype-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaPrototype.realisation_tache_id')) }}</small>
@include('PkgApprentissage::realisationUaPrototype.custom.fields.realisationTache',['entity' => $itemRealisationUaPrototype])
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaPrototype.note')) }}</small>
@include('PkgApprentissage::realisationUaPrototype.custom.fields.note',['entity' => $itemRealisationUaPrototype])
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaPrototype.remarque_formateur')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemRealisationUaPrototype->remarque_formateur) && $itemRealisationUaPrototype->remarque_formateur !== '')
                    {!! $itemRealisationUaPrototype->remarque_formateur !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaPrototype.date_debut')) }}</small>
                  <span>
                    @if ($itemRealisationUaPrototype->date_debut)
                    {{ \Carbon\Carbon::parse($itemRealisationUaPrototype->date_debut)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUaPrototype.date_fin')) }}</small>
                  <span>
                    @if ($itemRealisationUaPrototype->date_fin)
                    {{ \Carbon\Carbon::parse($itemRealisationUaPrototype->date_fin)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationUaPrototypes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationUaPrototype')
          <x-action-button :entity="$itemRealisationUaPrototype" actionName="edit">
          @can('update', $itemRealisationUaPrototype)
              <a href="{{ route('realisationUaPrototypes.edit', ['realisationUaPrototype' => $itemRealisationUaPrototype->id]) }}" data-id="{{$itemRealisationUaPrototype->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::realisationUaPrototype.singular") }} : {{ $itemRealisationUaPrototype }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show