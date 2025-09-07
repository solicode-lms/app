{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('module-show')
<div id="module-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::module.code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemModule->code) && $itemModule->code !== '')
        {{ $itemModule->code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::module.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemModule->nom) && $itemModule->nom !== '')
        {{ $itemModule->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::module.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemModule->description) && $itemModule->description !== '')
                    {!! $itemModule->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::module.masse_horaire')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemModule->masse_horaire) && $itemModule->masse_horaire !== '')
        {{ $itemModule->masse_horaire }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::filiere.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemModule->filiere)
                  {{ $itemModule->filiere }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            @if(auth()->user()?->can('show-competence') || auth()->user()?->can('create-competence'))
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgCompetences::competence.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgCompetences::competence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'module.show_' . $itemModule->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(auth()->user()?->can('show-realisationModule') || auth()->user()?->can('create-realisationModule'))
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationModule.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'module.show_' . $itemModule->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('modules.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-module')
          <x-action-button :entity="$itemModule" actionName="edit">
          @can('update', $itemModule)
              <a href="{{ route('modules.edit', ['module' => $itemModule->id]) }}" data-id="{{$itemModule->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgFormation::module.singular") }} : {{ $itemModule }}';
    window.showUIId = 'module-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show