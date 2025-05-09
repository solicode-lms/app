{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('formation-show')
<div id="formation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::formation.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormation->nom) && $itemFormation->nom !== '')
          {{ $itemFormation->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::formation.lien')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemFormation->lien) && $itemFormation->lien !== '')
          {{ $itemFormation->lien }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::formation.filiere_id')) }}</small>
                              
      @if($itemFormation->filiere)
        {{ $itemFormation->filiere }}
      @else
        —
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.singular')) }}</small>
                              
      @if($itemFormation->competence)
        {{ $itemFormation->competence }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::technology.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemFormation->technologies->isNotEmpty())
          <div>
            @foreach($itemFormation->technologies as $technology)
              <span class="badge badge-info mr-1">
                {{ $technology }}
              </span>
            @endforeach
          </div>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::formation.is_officiel')) }}</small>
                              
      @if($itemFormation->is_officiel)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>
                              
      @if($itemFormation->formateur)
        {{ $itemFormation->formateur }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::formation.singular')) }}</small>
                              
      @if($itemFormation->formation)
        {{ $itemFormation->formation }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::formation.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemFormation->description) && $itemFormation->description !== '')
    {!! $itemFormation->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('formations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-formation')
          <x-action-button :entity="$itemFormation" actionName="edit">
          @can('update', $itemFormation)
              <a href="{{ route('formations.edit', ['formation' => $itemFormation->id]) }}" data-id="{{$itemFormation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgAutoformation::formation.singular") }} : {{ $itemFormation }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show