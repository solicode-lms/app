{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowProjet-show')
<div id="workflowProjet-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::workflowProjet.ordre')) }}</small>
                              
      <span>
        @if(! is_null($itemWorkflowProjet->ordre))
          {{ $itemWorkflowProjet->ordre }}
        @else
          —
        @endif
      </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::workflowProjet.code')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemWorkflowProjet->code) && $itemWorkflowProjet->code !== '')
          {{ $itemWorkflowProjet->code }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::workflowProjet.titre')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemWorkflowProjet->titre) && $itemWorkflowProjet->titre !== '')
          {{ $itemWorkflowProjet->titre }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemWorkflowProjet->sysColor)
        @php
          $related = $itemWorkflowProjet->sysColor;
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
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationProjets::workflowProjet.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemWorkflowProjet->description) && $itemWorkflowProjet->description !== '')
    {!! $itemWorkflowProjet->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationProjets::etatsRealisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'workflowProjet.show_' . $itemWorkflowProjet->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('workflowProjets.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-workflowProjet')
          <x-action-button :entity="$itemWorkflowProjet" actionName="edit">
          @can('update', $itemWorkflowProjet)
              <a href="{{ route('workflowProjets.edit', ['workflowProjet' => $itemWorkflowProjet->id]) }}" data-id="{{$itemWorkflowProjet->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationProjets::workflowProjet.singular") }} : {{ $itemWorkflowProjet }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show