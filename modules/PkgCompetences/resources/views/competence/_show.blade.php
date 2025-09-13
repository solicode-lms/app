{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('competence-show')
<div id="competence-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-2 col-lg-2 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemCompetence->code) && $itemCompetence->code !== '')
        {{ $itemCompetence->code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-4 col-lg-4 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.mini_code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemCompetence->mini_code) && $itemCompetence->mini_code !== '')
        {{ $itemCompetence->mini_code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemCompetence->nom) && $itemCompetence->nom !== '')
        {{ $itemCompetence->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::module.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemCompetence->module)
                  {{ $itemCompetence->module }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-microCompetence') && $itemCompetence->microCompetences->isNotEmpty())  
                  || auth()->user()?->can('create-microCompetence')
                  || (auth()->user()?->can('edit-microCompetence')  && $itemCompetence->microCompetences->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgCompetences::microCompetence.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgCompetences::microCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'competence.show_' . $itemCompetence->id])
                  </div>
                  </div>
            </div>
            @endif

            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemCompetence->description) && $itemCompetence->description !== '')
                    {!! $itemCompetence->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-realisationCompetence') && $itemCompetence->realisationCompetences->isNotEmpty())  
                  || auth()->user()?->can('create-realisationCompetence')
                  || (auth()->user()?->can('edit-realisationCompetence')  && $itemCompetence->realisationCompetences->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationCompetence.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'competence.show_' . $itemCompetence->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('competences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-competence')
          <x-action-button :entity="$itemCompetence" actionName="edit">
          @can('update', $itemCompetence)
              <a href="{{ route('competences.edit', ['competence' => $itemCompetence->id]) }}" data-id="{{$itemCompetence->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCompetences::competence.singular") }} : {{ $itemCompetence }}';
    window.showUIId = 'competence-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show