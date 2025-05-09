{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('categoryTechnology-show')
<div id="categoryTechnology-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::categoryTechnology.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemCategoryTechnology->nom) && $itemCategoryTechnology->nom !== '')
          {{ $itemCategoryTechnology->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::categoryTechnology.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemCategoryTechnology->description) && $itemCategoryTechnology->description !== '')
    {!! $itemCategoryTechnology->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('categoryTechnologies.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-categoryTechnology')
          <x-action-button :entity="$itemCategoryTechnology" actionName="edit">
          @can('update', $itemCategoryTechnology)
              <a href="{{ route('categoryTechnologies.edit', ['categoryTechnology' => $itemCategoryTechnology->id]) }}" data-id="{{$itemCategoryTechnology->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCompetences::categoryTechnology.singular") }} : {{ $itemCategoryTechnology }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show