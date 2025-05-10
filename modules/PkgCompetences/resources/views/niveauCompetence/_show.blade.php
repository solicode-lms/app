{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauCompetence-show')
<div id="niveauCompetence-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::niveauCompetence.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemNiveauCompetence->nom) && $itemNiveauCompetence->nom !== '')
          {{ $itemNiveauCompetence->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::niveauCompetence.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemNiveauCompetence->description) && $itemNiveauCompetence->description !== '')
    {!! $itemNiveauCompetence->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.singular')) }}</small>
                              
      @if($itemNiveauCompetence->competence)
        {{ $itemNiveauCompetence->competence }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgAutoformation::chapitre.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgAutoformation::chapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'niveauCompetence.show_' . $itemNiveauCompetence->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('niveauCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-niveauCompetence')
          <x-action-button :entity="$itemNiveauCompetence" actionName="edit">
          @can('update', $itemNiveauCompetence)
              <a href="{{ route('niveauCompetences.edit', ['niveauCompetence' => $itemNiveauCompetence->id]) }}" data-id="{{$itemNiveauCompetence->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCompetences::niveauCompetence.singular") }} : {{ $itemNiveauCompetence }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show