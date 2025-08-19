{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationMicroCompetence-show')
<div id="realisationMicroCompetence-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::microCompetence.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationMicroCompetence->microCompetence)
                  {{ $itemRealisationMicroCompetence->microCompetence }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationMicroCompetence->apprenant)
                  {{ $itemRealisationMicroCompetence->apprenant }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.note_cache')) }}</small>
@include('PkgApprentissage::realisationMicroCompetence.custom.fields.note_cache',['entity' => $itemRealisationMicroCompetence])
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.progression_cache')) }}</small>
@include('PkgApprentissage::realisationMicroCompetence.custom.fields.progression_cache',['entity' => $itemRealisationMicroCompetence])
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.etat_realisation_micro_competence_id')) }}</small>

                {{-- Affichage sous forme de badge --}}
                @if($itemRealisationMicroCompetence->etatRealisationMicroCompetence)
                  <x-badge 
                    :text="$itemRealisationMicroCompetence->etatRealisationMicroCompetence" 
                    :background="$itemRealisationMicroCompetence->etatRealisationMicroCompetence->sysColor->hex ?? '#6c757d'" 
                  />
                @else
                  <span class="text-muted">—</span>
                @endif
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
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationCompetence.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationMicroCompetence->realisationCompetence)
                  {{ $itemRealisationMicroCompetence->realisationCompetence }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.lien_livrable')) }}</small>
    {{-- Lien cliquable --}}
    @if(!is_null($itemRealisationMicroCompetence->lien_livrable) && $itemRealisationMicroCompetence->lien_livrable !== '')
        <a href="{{ $itemRealisationMicroCompetence->lien_livrable }}" target="_blank">
            <i class="fas fa-link mr-1"></i>
            {{ $itemRealisationMicroCompetence->lien_livrable }}
        </a>
    @else
        <span class="text-muted">—</span>
    @endif

                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.progression_ideal_cache')) }}</small>
                  <span>
                  @if(! is_null($itemRealisationMicroCompetence->progression_ideal_cache))
                  {{ number_format($itemRealisationMicroCompetence->progression_ideal_cache, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.taux_rythme_cache')) }}</small>
                  <span>
                  @if(! is_null($itemRealisationMicroCompetence->taux_rythme_cache))
                  {{ number_format($itemRealisationMicroCompetence->taux_rythme_cache, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
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