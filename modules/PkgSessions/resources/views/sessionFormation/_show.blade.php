{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sessionFormation-show')
<div id="sessionFormation-crud-show">
        <div class="card-body">
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('Informations générales') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-10 col-lg-10 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.titre')) }}</small>
@include('PkgSessions::sessionFormation.custom.fields.titre',['entity' => $itemSessionFormation])
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.remarques')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemSessionFormation->remarques) && $itemSessionFormation->remarques !== '')
                    {!! $itemSessionFormation->remarques !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('Prototype') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.titre_prototype')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemSessionFormation->titre_prototype) && $itemSessionFormation->titre_prototype !== '')
        {{ $itemSessionFormation->titre_prototype }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.description_prototype')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemSessionFormation->description_prototype) && $itemSessionFormation->description_prototype !== '')
                    {!! $itemSessionFormation->description_prototype !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.contraintes_prototype')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemSessionFormation->contraintes_prototype) && $itemSessionFormation->contraintes_prototype !== '')
                    {!! $itemSessionFormation->contraintes_prototype !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('Projet') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.titre_projet')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemSessionFormation->titre_projet) && $itemSessionFormation->titre_projet !== '')
        {{ $itemSessionFormation->titre_projet }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.description_projet')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemSessionFormation->description_projet) && $itemSessionFormation->description_projet !== '')
                    {!! $itemSessionFormation->description_projet !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.contraintes_projet')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemSessionFormation->contraintes_projet) && $itemSessionFormation->contraintes_projet !== '')
                    {!! $itemSessionFormation->contraintes_projet !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
            <h6 class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>{{ __('Objectifs pédagogiques') }}
            </h6>
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.objectifs_pedagogique')) }}</small>
@include('PkgSessions::sessionFormation.custom.fields.objectifs_pedagogique',['entity' => $itemSessionFormation])
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-alignementUa') && $itemSessionFormation->alignementUas->isNotEmpty())  
                  || auth()->user()?->can('create-alignementUa')
                  || (auth()->user()?->can('edit-alignementUa')  && $itemSessionFormation->alignementUas->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgSessions::alignementUa.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgSessions::alignementUa._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sessionFormation.show_' . $itemSessionFormation->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
            <div class="row no-gutters mb-4">
            @if(
                  (auth()->user()?->can('show-livrableSession') && $itemSessionFormation->livrableSessions->isNotEmpty())  
                  || auth()->user()?->can('create-livrableSession')
                  || (auth()->user()?->can('edit-livrableSession')  && $itemSessionFormation->livrableSessions->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgSessions::livrableSession.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgSessions::livrableSession._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sessionFormation.show_' . $itemSessionFormation->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-projet') && $itemSessionFormation->projets->isNotEmpty())  
                  || auth()->user()?->can('create-projet')
                  || (auth()->user()?->can('edit-projet')  && $itemSessionFormation->projets->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgCreationProjet::projet.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgCreationProjet::projet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sessionFormation.show_' . $itemSessionFormation->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('sessionFormations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-sessionFormation')
          <x-action-button :entity="$itemSessionFormation" actionName="edit">
          @can('update', $itemSessionFormation)
              <a href="{{ route('sessionFormations.edit', ['sessionFormation' => $itemSessionFormation->id]) }}" data-id="{{$itemSessionFormation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgSessions::sessionFormation.singular") }} : {{ $itemSessionFormation }}';
    window.showUIId = 'sessionFormation-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show