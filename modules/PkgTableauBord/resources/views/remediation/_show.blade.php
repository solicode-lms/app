{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('remediation-show')
<div id="remediation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::realisationTache.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRemediation->realisationTache)
                  {{ $itemRemediation->realisationTache }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRemediation->formateur)
                  {{ $itemRemediation->formateur }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::remediation.type_remediation')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemRemediation->type_remediation) && $itemRemediation->type_remediation !== '')
        {{ $itemRemediation->type_remediation }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::remediation.diagnostic')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemRemediation->diagnostic) && $itemRemediation->diagnostic !== '')
                    {!! $itemRemediation->diagnostic !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::remediation.est_resolu')) }}</small>
                  @if($itemRemediation->est_resolu)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('remediations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-remediation')
          <x-action-button :entity="$itemRemediation" actionName="edit">
          @can('update', $itemRemediation)
              <a href="{{ route('remediations.edit', ['remediation' => $itemRemediation->id]) }}" data-id="{{$itemRemediation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgTableauBord::remediation.singular") }} : {{ $itemRemediation }}';
    window.showUIId = 'remediation-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show