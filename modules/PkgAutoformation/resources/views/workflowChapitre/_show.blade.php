{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowChapitre-show')
<div id="workflowChapitre-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::workflowChapitre.code')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemWorkflowChapitre->code) && $itemWorkflowChapitre->code !== '')
          {{ $itemWorkflowChapitre->code }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::workflowChapitre.titre')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemWorkflowChapitre->titre) && $itemWorkflowChapitre->titre !== '')
          {{ $itemWorkflowChapitre->titre }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemWorkflowChapitre->sysColor)
        @php
          $related = $itemWorkflowChapitre->sysColor;
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
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::workflowChapitre.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemWorkflowChapitre->description) && $itemWorkflowChapitre->description !== '')
    {!! $itemWorkflowChapitre->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('workflowChapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-workflowChapitre')
          <x-action-button :entity="$itemWorkflowChapitre" actionName="edit">
          @can('update', $itemWorkflowChapitre)
              <a href="{{ route('workflowChapitres.edit', ['workflowChapitre' => $itemWorkflowChapitre->id]) }}" data-id="{{$itemWorkflowChapitre->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgAutoformation::workflowChapitre.singular") }} : {{ $itemWorkflowChapitre }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show