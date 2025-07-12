{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatEvaluationProjet-show')
<div id="etatEvaluationProjet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemEtatEvaluationProjet->ordre))
          {{ $itemEtatEvaluationProjet->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.code')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEtatEvaluationProjet->code) && $itemEtatEvaluationProjet->code !== '')
          {{ $itemEtatEvaluationProjet->code }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.titre')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEtatEvaluationProjet->titre) && $itemEtatEvaluationProjet->titre !== '')
          {{ $itemEtatEvaluationProjet->titre }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEtatEvaluationProjet->description) && $itemEtatEvaluationProjet->description !== '')
    {!! $itemEtatEvaluationProjet->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemEtatEvaluationProjet->sysColor)
        @php
          $related = $itemEtatEvaluationProjet->sysColor;
        @endphp
        <span 
          class="badge" 
          style="background-color: {{ $related->hex }}; color: #fff;"
        >
          {{ $related->sysColor }}
        </span>
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgEvaluateurs::evaluationRealisationProjet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgEvaluateurs::evaluationRealisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatEvaluationProjet.show_' . $itemEtatEvaluationProjet->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('etatEvaluationProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-etatEvaluationProjet')
          <x-action-button :entity="$itemEtatEvaluationProjet" actionName="edit">
          @can('update', $itemEtatEvaluationProjet)
              <a href="{{ route('etatEvaluationProjets.edit', ['etatEvaluationProjet' => $itemEtatEvaluationProjet->id]) }}" data-id="{{$itemEtatEvaluationProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgEvaluateurs::etatEvaluationProjet.singular") }} : {{ $itemEtatEvaluationProjet }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show