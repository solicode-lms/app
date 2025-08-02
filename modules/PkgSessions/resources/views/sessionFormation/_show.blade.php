{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sessionFormation-show')
<div id="sessionFormation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-2 col-lg-2 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemSessionFormation->ordre))
          {{ $itemSessionFormation->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-10 col-lg-10 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.titre')) }}</small>
                              @if(! is_null($itemSessionFormation->titre) && $itemSessionFormation->titre !== '')
        {{ $itemSessionFormation->titre }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.thematique')) }}</small>
                              @if(! is_null($itemSessionFormation->thematique) && $itemSessionFormation->thematique !== '')
        {{ $itemSessionFormation->thematique }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::filiere.singular')) }}</small>
                              
      @if($itemSessionFormation->filiere)
        {{ $itemSessionFormation->filiere }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.objectifs_pedagogique')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSessionFormation->objectifs_pedagogique) && $itemSessionFormation->objectifs_pedagogique !== '')
    {!! $itemSessionFormation->objectifs_pedagogique !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.titre_prototype')) }}</small>
                              @if(! is_null($itemSessionFormation->titre_prototype) && $itemSessionFormation->titre_prototype !== '')
        {{ $itemSessionFormation->titre_prototype }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.description_prototype')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSessionFormation->description_prototype) && $itemSessionFormation->description_prototype !== '')
    {!! $itemSessionFormation->description_prototype !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.contraintes_prototype')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSessionFormation->contraintes_prototype) && $itemSessionFormation->contraintes_prototype !== '')
    {!! $itemSessionFormation->contraintes_prototype !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.titre_projet')) }}</small>
                              @if(! is_null($itemSessionFormation->titre_projet) && $itemSessionFormation->titre_projet !== '')
        {{ $itemSessionFormation->titre_projet }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.description_projet')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSessionFormation->description_projet) && $itemSessionFormation->description_projet !== '')
    {!! $itemSessionFormation->description_projet !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.contraintes_projet')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSessionFormation->contraintes_projet) && $itemSessionFormation->contraintes_projet !== '')
    {!! $itemSessionFormation->contraintes_projet !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.remarques')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSessionFormation->remarques) && $itemSessionFormation->remarques !== '')
    {!! $itemSessionFormation->remarques !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.date_debut')) }}</small>
                            
    <span>
      @if ($itemSessionFormation->date_debut)
        {{ \Carbon\Carbon::parse($itemSessionFormation->date_debut)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.date_fin')) }}</small>
                            
    <span>
      @if ($itemSessionFormation->date_fin)
        {{ \Carbon\Carbon::parse($itemSessionFormation->date_fin)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgSessions::sessionFormation.jour_feries_vacances')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSessionFormation->jour_feries_vacances) && $itemSessionFormation->jour_feries_vacances !== '')
    {!! $itemSessionFormation->jour_feries_vacances !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::anneeFormation.singular')) }}</small>
                              
      @if($itemSessionFormation->anneeFormation)
        {{ $itemSessionFormation->anneeFormation }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgSessions::alignementUa.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgSessions::alignementUa._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sessionFormation.show_' . $itemSessionFormation->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgSessions::livrableSession.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgSessions::livrableSession._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sessionFormation.show_' . $itemSessionFormation->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgCreationProjet::projet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgCreationProjet::projet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sessionFormation.show_' . $itemSessionFormation->id])
            </div>
          </div>
      </div>


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
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show