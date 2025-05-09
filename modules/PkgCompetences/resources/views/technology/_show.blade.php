{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('technology-show')
<div id="technology-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::technology.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemTechnology->nom) && $itemTechnology->nom !== '')
          {{ $itemTechnology->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::categoryTechnology.singular')) }}</small>
                              
      @if($itemTechnology->categoryTechnology)
        {{ $itemTechnology->categoryTechnology }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemTechnology->competences->isNotEmpty())
          <div>
            @foreach($itemTechnology->competences as $competence)
              <span class="badge badge-info mr-1">
                {{ $competence }}
              </span>
            @endforeach
          </div>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::technology.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemTechnology->description) && $itemTechnology->description !== '')
    {!! $itemTechnology->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('technologies.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-technology')
          <x-action-button :entity="$itemTechnology" actionName="edit">
          @can('update', $itemTechnology)
              <a href="{{ route('technologys.edit', ['technology' => $itemTechnology->id]) }}" data-id="{{$itemTechnology->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCompetences::technology.singular") }} : {{ $itemTechnology }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show