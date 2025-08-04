{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationMicroCompetence-show')
<div id="realisationMicroCompetence-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::microCompetence.singular')) }}</small>
                  @if($itemRealisationMicroCompetence->microCompetence)
                    {{ $itemRealisationMicroCompetence->microCompetence }}
                  @else
                    —
                  @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.singular')) }}</small>
                  @if($itemRealisationMicroCompetence->apprenant)
                    {{ $itemRealisationMicroCompetence->apprenant }}
                  @else
                    —
                  @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.etat_realisation_micro_competence_id')) }}</small>
                  @if($itemRealisationMicroCompetence->etatRealisationMicroCompetence)
                    {{ $itemRealisationMicroCompetence->etatRealisationMicroCompetence }}
                  @else
                    —
                  @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.progression_cache')) }}</small>
                  <div class="progress progress-sm">
                      <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{$itemRealisationMicroCompetence->progression_cache }}" aria-valuemin="0" aria-valuemax="100" style="width: {{$itemRealisationMicroCompetence->progression_cache }}%">
                      </div>
                  </div>
                  <small>
                      {{$itemRealisationMicroCompetence->progression_cache }}% Terminé
                  </small>
                </div>
            </div>
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationUa.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationUa._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationMicroCompetence.show_' . $itemRealisationMicroCompetence->id])
                  </div>
                  </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.note_cache')) }}</small>
@include('PkgApprentissage::realisationMicroCompetence.custom.fields.note_cache' , ['entity' => $itemRealisationMicroCompetence])
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.bareme_cache')) }}</small>
                  <span>
                  @if(! is_null($itemRealisationMicroCompetence->bareme_cache))
                  {{ number_format($itemRealisationMicroCompetence->bareme_cache, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.date_debut')) }}</small>
                  <span>
                    @if ($itemRealisationMicroCompetence->date_debut)
                    {{ \Carbon\Carbon::parse($itemRealisationMicroCompetence->date_debut)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.date_fin')) }}</small>
                  <span>
                    @if ($itemRealisationMicroCompetence->date_fin)
                    {{ \Carbon\Carbon::parse($itemRealisationMicroCompetence->date_fin)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.commentaire_formateur')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemRealisationMicroCompetence->commentaire_formateur) && $itemRealisationMicroCompetence->commentaire_formateur !== '')
                    {!! $itemRealisationMicroCompetence->commentaire_formateur !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.dernier_update')) }}</small>
                  <span>
                    @if ($itemRealisationMicroCompetence->dernier_update)
                    {{ \Carbon\Carbon::parse($itemRealisationMicroCompetence->dernier_update)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationMicroCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationMicroCompetence')
          <x-action-button :entity="$itemRealisationMicroCompetence" actionName="edit">
          @can('update', $itemRealisationMicroCompetence)
              <a href="{{ route('realisationMicroCompetences.edit', ['realisationMicroCompetence' => $itemRealisationMicroCompetence->id]) }}" data-id="{{$itemRealisationMicroCompetence->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::realisationMicroCompetence.singular") }} : {{ $itemRealisationMicroCompetence }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show