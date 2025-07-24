{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('microCompetence-show')
<div id="microCompetence-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::microCompetence.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemMicroCompetence->ordre))
          {{ $itemMicroCompetence->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::microCompetence.code')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemMicroCompetence->code) && $itemMicroCompetence->code !== '')
          {{ $itemMicroCompetence->code }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::microCompetence.titre')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemMicroCompetence->titre) && $itemMicroCompetence->titre !== '')
          {{ $itemMicroCompetence->titre }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::microCompetence.sous_titre')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemMicroCompetence->sous_titre) && $itemMicroCompetence->sous_titre !== '')
          {{ $itemMicroCompetence->sous_titre }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::competence.singular')) }}</small>
                              
      @if($itemMicroCompetence->competence)
        {{ $itemMicroCompetence->competence }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgCompetences::uniteApprentissage.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgCompetences::uniteApprentissage._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'microCompetence.show_' . $itemMicroCompetence->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::microCompetence.lien')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemMicroCompetence->lien) && $itemMicroCompetence->lien !== '')
          {{ $itemMicroCompetence->lien }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::microCompetence.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemMicroCompetence->description) && $itemMicroCompetence->description !== '')
    {!! $itemMicroCompetence->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgApprentissage::realisationMicroCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'microCompetence.show_' . $itemMicroCompetence->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('microCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-microCompetence')
          <x-action-button :entity="$itemMicroCompetence" actionName="edit">
          @can('update', $itemMicroCompetence)
              <a href="{{ route('microCompetences.edit', ['microCompetence' => $itemMicroCompetence->id]) }}" data-id="{{$itemMicroCompetence->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCompetences::microCompetence.singular") }} : {{ $itemMicroCompetence }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show