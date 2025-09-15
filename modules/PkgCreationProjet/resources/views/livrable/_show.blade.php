{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrable-show')
<div id="livrable-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::natureLivrable.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemLivrable->natureLivrable)
                  {{ $itemLivrable->natureLivrable }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::livrable.titre')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemLivrable->titre) && $itemLivrable->titre !== '')
        {{ $itemLivrable->titre }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemLivrable->projet)
                  {{ $itemLivrable->projet }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::livrable.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemLivrable->description) && $itemLivrable->description !== '')
                    {!! $itemLivrable->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::livrable.is_affichable_seulement_par_formateur')) }}</small>
                  @if($itemLivrable->is_affichable_seulement_par_formateur)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCreationTache::tache.plural')) }}</small>
                  <!-- Valeurs many-to-many -->
                  @if($itemLivrable->taches->isNotEmpty())
                  <div>
                    @foreach($itemLivrable->taches as $tache)
                      <span class="badge badge-info mr-1">
                        {{ $tache }}
                      </span>
                    @endforeach
                  </div>
                  @else
                  <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-livrablesRealisation') && $itemLivrable->livrablesRealisations->isNotEmpty())  
                  || auth()->user()?->can('create-livrablesRealisation')
                  || (auth()->user()?->can('edit-livrablesRealisation')  && $itemLivrable->livrablesRealisations->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgRealisationProjets::livrablesRealisation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'livrable.show_' . $itemLivrable->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('livrables.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-livrable')
          <x-action-button :entity="$itemLivrable" actionName="edit">
          @can('update', $itemLivrable)
              <a href="{{ route('livrables.edit', ['livrable' => $itemLivrable->id]) }}" data-id="{{$itemLivrable->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCreationProjet::livrable.singular") }} : {{ $itemLivrable }}';
    window.showUIId = 'livrable-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show