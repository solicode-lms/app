{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationCompetence-show')
<div id="realisationCompetence-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.singular')) }}</small>
@include('PkgApprentissage::realisationCompetence.custom.fields.competence',['entity' => $itemRealisationCompetence])
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.progression_cache')) }}</small>
@include('PkgApprentissage::realisationCompetence.custom.fields.progression_cache',['entity' => $itemRealisationCompetence])
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.note_cache')) }}</small>
@include('PkgApprentissage::realisationCompetence.custom.fields.note_cache',['entity' => $itemRealisationCompetence])
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.dernier_update')) }}</small>
                  <span>
                    @if ($itemRealisationCompetence->dernier_update)
                    {{ \Carbon\Carbon::parse($itemRealisationCompetence->dernier_update)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.date_debut')) }}</small>
                  <span>
                    @if ($itemRealisationCompetence->date_debut)
                    {{ \Carbon\Carbon::parse($itemRealisationCompetence->date_debut)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-realisationMicroCompetence') && $itemRealisationCompetence->realisationMicroCompetences->isNotEmpty())  
                  || auth()->user()?->can('create-realisationMicroCompetence')
                  || (auth()->user()?->can('edit-realisationMicroCompetence')  && $itemRealisationCompetence->realisationMicroCompetences->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationMicroCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationCompetence.show_' . $itemRealisationCompetence->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationCompetence')
          <x-action-button :entity="$itemRealisationCompetence" actionName="edit">
          @can('update', $itemRealisationCompetence)
              <a href="{{ route('realisationCompetences.edit', ['realisationCompetence' => $itemRealisationCompetence->id]) }}" data-id="{{$itemRealisationCompetence->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::realisationCompetence.singular") }} : {{ $itemRealisationCompetence }}';
    window.showUIId = 'realisationCompetence-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show