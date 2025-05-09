{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('specialite-show')
<div id="specialite-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::specialite.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemSpecialite->nom) && $itemSpecialite->nom !== '')
          {{ $itemSpecialite->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::specialite.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSpecialite->description) && $itemSpecialite->description !== '')
    {!! $itemSpecialite->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemSpecialite->formateurs->isNotEmpty())
          <div>
            @foreach($itemSpecialite->formateurs as $formateur)
              <span class="badge badge-info mr-1">
                {{ $formateur }}
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
          <a href="{{ route('specialites.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-specialite')
          <x-action-button :entity="$itemSpecialite" actionName="edit">
          @can('update', $itemSpecialite)
              <a href="{{ route('specialites.edit', ['specialite' => $itemSpecialite->id]) }}" data-id="{{$itemSpecialite->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgFormation::specialite.singular") }} : {{ $itemSpecialite }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show