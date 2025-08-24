{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('uniteApprentissage-show')
<div id="uniteApprentissage-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::uniteApprentissage.code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemUniteApprentissage->code) && $itemUniteApprentissage->code !== '')
        {{ $itemUniteApprentissage->code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::uniteApprentissage.nom')) }}</small>
@include('PkgCompetences::uniteApprentissage.custom.fields.nom',['entity' => $itemUniteApprentissage])
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::microCompetence.singular')) }}</small>
@include('PkgCompetences::uniteApprentissage.custom.fields.microCompetence',['entity' => $itemUniteApprentissage])
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::uniteApprentissage.lien')) }}</small>
    {{-- Lien cliquable --}}
    @if(!is_null($itemUniteApprentissage->lien) && $itemUniteApprentissage->lien !== '')
        <a href="{{ $itemUniteApprentissage->lien }}" target="_blank">
            <i class="fas fa-link mr-1"></i>
            {{ $itemUniteApprentissage->lien }}
        </a>
    @else
        <span class="text-muted">—</span>
    @endif

                </div>
            </div>
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgCompetences::chapitre.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgCompetences::chapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'uniteApprentissage.show_' . $itemUniteApprentissage->id])
                  </div>
                  </div>
            </div>

            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgCompetences::critereEvaluation.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgCompetences::critereEvaluation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'uniteApprentissage.show_' . $itemUniteApprentissage->id])
                  </div>
                  </div>
            </div>

            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgCompetences::uniteApprentissage.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemUniteApprentissage->description) && $itemUniteApprentissage->description !== '')
                    {!! $itemUniteApprentissage->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('uniteApprentissages.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-uniteApprentissage')
          <x-action-button :entity="$itemUniteApprentissage" actionName="edit">
          @can('update', $itemUniteApprentissage)
              <a href="{{ route('uniteApprentissages.edit', ['uniteApprentissage' => $itemUniteApprentissage->id]) }}" data-id="{{$itemUniteApprentissage->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgCompetences::uniteApprentissage.singular") }} : {{ $itemUniteApprentissage }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show