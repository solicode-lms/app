{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationModule-show')
<div id="realisationModule-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::module.singular')) }}</small>
@include('PkgApprentissage::realisationModule.custom.fields.module',['entity' => $itemRealisationModule])
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationModule.progression_cache')) }}</small>
@include('PkgApprentissage::realisationModule.custom.fields.progression_cache',['entity' => $itemRealisationModule])
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationModule.note_cache')) }}</small>
@include('PkgApprentissage::realisationModule.custom.fields.note_cache',['entity' => $itemRealisationModule])
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationModule.dernier_update')) }}</small>
                  <span>
                    @if ($itemRealisationModule->dernier_update)
                    {{ \Carbon\Carbon::parse($itemRealisationModule->dernier_update)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationModule.date_debut')) }}</small>
                  <span>
                    @if ($itemRealisationModule->date_debut)
                    {{ \Carbon\Carbon::parse($itemRealisationModule->date_debut)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-realisationCompetence') && $itemRealisationModule->realisationCompetences->isNotEmpty())  
                  || auth()->user()?->can('create-realisationCompetence')
                  || (auth()->user()?->can('edit-realisationCompetence')  && $itemRealisationModule->realisationCompetences->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationCompetence.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationModule.show_' . $itemRealisationModule->id])
                  </div>
                  </div>
            </div>
            @endif

            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationModule.progression_ideal_cache')) }}</small>
                  <span>
                  @if(! is_null($itemRealisationModule->progression_ideal_cache))
                  {{ number_format($itemRealisationModule->progression_ideal_cache, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationModule.taux_rythme_cache')) }}</small>
                  <div class="progress progress-sm">
                      <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{$itemRealisationModule->taux_rythme_cache }}" aria-valuemin="0" aria-valuemax="100" style="width: {{$itemRealisationModule->taux_rythme_cache }}%">
                      </div>
                  </div>
                  <small>
                      {{$itemRealisationModule->taux_rythme_cache }}% Terminé
                  </small>
                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationModules.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationModule')
          <x-action-button :entity="$itemRealisationModule" actionName="edit">
          @can('update', $itemRealisationModule)
              <a href="{{ route('realisationModules.edit', ['realisationModule' => $itemRealisationModule->id]) }}" data-id="{{$itemRealisationModule->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::realisationModule.singular") }} : {{ $itemRealisationModule }}';
    window.showUIId = 'realisationModule-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show