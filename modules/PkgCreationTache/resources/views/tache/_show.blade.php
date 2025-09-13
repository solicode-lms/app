{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('tache-show')
<div id="tache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-2 col-lg-2 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::tache.priorite')) }}</small>
                  <span>
                    @if(! is_null($itemTache->priorite))
                      {{ $itemTache->priorite }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::tache.titre')) }}</small>
@include('PkgCreationTache::tache.custom.fields.titre',['entity' => $itemTache])
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemTache->projet)
                  {{ $itemTache->projet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::tache.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemTache->description) && $itemTache->description !== '')
                    {!! $itemTache->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::tache.dateDebut')) }}</small>
                  <span>
                    @if ($itemTache->dateDebut)
                    {{ \Carbon\Carbon::parse($itemTache->dateDebut)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::tache.dateFin')) }}</small>
                  <span>
                    @if ($itemTache->dateFin)
                    {{ \Carbon\Carbon::parse($itemTache->dateFin)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::tache.note')) }}</small>
@include('PkgCreationTache::tache.custom.fields.note',['entity' => $itemTache])
                </div>
            </div>
            <div class="show_group col-12 col-md-2 col-lg-2 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::tache.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemTache->ordre))
                      {{ $itemTache->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::phaseEvaluation.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemTache->phaseEvaluation)
                  {{ $itemTache->phaseEvaluation }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::chapitre.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemTache->chapitre)
                  {{ $itemTache->chapitre }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::livrable.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemTache->livrables->isNotEmpty())
                  <div>
                    @foreach($itemTache->livrables as $livrable)
                      <span class="badge badge-info mr-1">
                        {{ $livrable }}
                      </span>
                    @endforeach
                  </div>
                  @else
                  <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-tacheAffectation') && $itemTache->tacheAffectations->isNotEmpty())  
                  || auth()->user()?->can('create-tacheAffectation')
                  || (auth()->user()?->can('edit-tacheAffectation')  && $itemTache->tacheAffectations->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationTache::tacheAffectation.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgRealisationTache::tacheAffectation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'tache.show_' . $itemTache->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('taches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-tache')
          <x-action-button :entity="$itemTache" actionName="edit">
          @can('update', $itemTache)
              <a href="{{ route('taches.edit', ['tache' => $itemTache->id]) }}" data-id="{{$itemTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCreationTache::tache.singular") }} : {{ $itemTache }}';
    window.showUIId = 'tache-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show