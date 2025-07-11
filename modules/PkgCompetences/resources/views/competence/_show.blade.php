{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('competence-show')
<div id="competence-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.code')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemCompetence->code) && $itemCompetence->code !== '')
          {{ $itemCompetence->code }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.mini_code')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemCompetence->mini_code) && $itemCompetence->mini_code !== '')
          {{ $itemCompetence->mini_code }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemCompetence->nom) && $itemCompetence->nom !== '')
          {{ $itemCompetence->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::module.singular')) }}</small>
                              
      @if($itemCompetence->module)
        {{ $itemCompetence->module }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemCompetence->description) && $itemCompetence->description !== '')
    {!! $itemCompetence->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('competences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-competence')
          <x-action-button :entity="$itemCompetence" actionName="edit">
          @can('update', $itemCompetence)
              <a href="{{ route('competences.edit', ['competence' => $itemCompetence->id]) }}" data-id="{{$itemCompetence->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCompetences::competence.singular") }} : {{ $itemCompetence }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show