{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('chapitre-show')
<div id="chapitre-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::chapitre.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemChapitre->nom) && $itemChapitre->nom !== '')
          {{ $itemChapitre->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::chapitre.lien')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemChapitre->lien) && $itemChapitre->lien !== '')
          {{ $itemChapitre->lien }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::chapitre.coefficient')) }}</small>
                              
      <span>
        @if(! is_null($itemChapitre->coefficient))
          {{ $itemChapitre->coefficient }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::chapitre.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemChapitre->description) && $itemChapitre->description !== '')
    {!! $itemChapitre->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::chapitre.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemChapitre->ordre))
          {{ $itemChapitre->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::chapitre.is_officiel')) }}</small>
                              
      @if($itemChapitre->is_officiel)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::formation.singular')) }}</small>
                              
      @if($itemChapitre->formation)
        {{ $itemChapitre->formation }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::niveauCompetence.singular')) }}</small>
                              
      @if($itemChapitre->niveauCompetence)
        {{ $itemChapitre->niveauCompetence }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>
                              
      @if($itemChapitre->formateur)
        {{ $itemChapitre->formateur }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::chapitre.singular')) }}</small>
                              
      @if($itemChapitre->chapitre)
        {{ $itemChapitre->chapitre }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgAutoformation::chapitre.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgAutoformation::chapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'chapitre.show_' . $itemChapitre->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgAutoformation::realisationChapitre.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgAutoformation::realisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'chapitre.show_' . $itemChapitre->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('chapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-chapitre')
          <x-action-button :entity="$itemChapitre" actionName="edit">
          @can('update', $itemChapitre)
              <a href="{{ route('chapitres.edit', ['chapitre' => $itemChapitre->id]) }}" data-id="{{$itemChapitre->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgAutoformation::chapitre.singular") }} : {{ $itemChapitre }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show