{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('tache-show')
<div id="tache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-8 col-lg-8 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::tache.titre')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemTache->titre) && $itemTache->titre !== '')
          {{ $itemTache->titre }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-4 col-lg-4 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</small>
                              
      @if($itemTache->projet)
        {{ $itemTache->projet }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::tache.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemTache->description) && $itemTache->description !== '')
    {!! $itemTache->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::tache.dateDebut')) }}</small>
                            
    <span>
      @if ($itemTache->dateDebut)
        {{ \Carbon\Carbon::parse($itemTache->dateDebut)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::tache.dateFin')) }}</small>
                            
    <span>
      @if ($itemTache->dateFin)
        {{ \Carbon\Carbon::parse($itemTache->dateFin)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::tache.note')) }}</small>
                              
      <span>
        @if(! is_null($itemTache->note))
          {{ number_format($itemTache->note, 2, '.', '') }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-2 col-lg-2 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::tache.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemTache->ordre))
          {{ $itemTache->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-3 col-lg-3 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgGestionTaches::tache.priorite_tache_id')) }}</small>
                              
      @if($itemTache->prioriteTache)
        {{ $itemTache->prioriteTache }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
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
        @endif
          </div>
      </div>
  


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
    window.modalTitle   = '{{ __("PkgGestionTaches::tache.singular") }} : {{ $itemTache }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show