{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationUa-show')
<div id="realisationUa-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::uniteApprentissage.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationUa->uniteApprentissage)
                  {{ $itemRealisationUa->uniteApprentissage }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemRealisationUa->realisationMicroCompetence)
                  {{ $itemRealisationUa->realisationMicroCompetence }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationUa.singular')) }}</small>

                {{-- Affichage sous forme de badge --}}
                @if($itemRealisationUa->etatRealisationUa)
                  <x-badge 
                    :text="$itemRealisationUa->etatRealisationUa" 
                    :background="$itemRealisationUa->etatRealisationUa->sysColor->hex ?? '#6c757d'" 
                  />
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUa.progression_cache')) }}</small>
                  <span>
                  @if(! is_null($itemRealisationUa->progression_cache))
                  {{ number_format($itemRealisationUa->progression_cache, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUa.note_cache')) }}</small>
                  <span>
                  @if(! is_null($itemRealisationUa->note_cache))
                  {{ number_format($itemRealisationUa->note_cache, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUa.bareme_cache')) }}</small>
                  <span>
                  @if(! is_null($itemRealisationUa->bareme_cache))
                  {{ number_format($itemRealisationUa->bareme_cache, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationChapitre.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationUa.show_' . $itemRealisationUa->id])
                  </div>
                  </div>
            </div>

            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationUaPrototype.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationUaPrototype._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationUa.show_' . $itemRealisationUa->id])
                  </div>
                  </div>
            </div>

            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationUaProjet.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationUaProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationUa.show_' . $itemRealisationUa->id])
                  </div>
                  </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUa.date_debut')) }}</small>
                  <span>
                    @if ($itemRealisationUa->date_debut)
                    {{ \Carbon\Carbon::parse($itemRealisationUa->date_debut)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUa.date_fin')) }}</small>
                  <span>
                    @if ($itemRealisationUa->date_fin)
                    {{ \Carbon\Carbon::parse($itemRealisationUa->date_fin)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::realisationUa.commentaire_formateur')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemRealisationUa->commentaire_formateur) && $itemRealisationUa->commentaire_formateur !== '')
                    {!! $itemRealisationUa->commentaire_formateur !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('realisationUas.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-realisationUa')
          <x-action-button :entity="$itemRealisationUa" actionName="edit">
          @can('update', $itemRealisationUa)
              <a href="{{ route('realisationUas.edit', ['realisationUa' => $itemRealisationUa->id]) }}" data-id="{{$itemRealisationUa->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::realisationUa.singular") }} : {{ $itemRealisationUa }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show