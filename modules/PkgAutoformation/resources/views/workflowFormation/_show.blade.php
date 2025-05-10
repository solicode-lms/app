{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowFormation-show')
<div id="workflowFormation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::workflowFormation.code')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemWorkflowFormation->code) && $itemWorkflowFormation->code !== '')
          {{ $itemWorkflowFormation->code }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::workflowFormation.titre')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemWorkflowFormation->titre) && $itemWorkflowFormation->titre !== '')
          {{ $itemWorkflowFormation->titre }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemWorkflowFormation->sysColor)
        @php
          $related = $itemWorkflowFormation->sysColor;
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
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::workflowFormation.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemWorkflowFormation->description) && $itemWorkflowFormation->description !== '')
    {!! $itemWorkflowFormation->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgAutoformation::etatFormation.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgAutoformation::etatFormation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'workflowFormation.show_' . $itemWorkflowFormation->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('workflowFormations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-workflowFormation')
          <x-action-button :entity="$itemWorkflowFormation" actionName="edit">
          @can('update', $itemWorkflowFormation)
              <a href="{{ route('workflowFormations.edit', ['workflowFormation' => $itemWorkflowFormation->id]) }}" data-id="{{$itemWorkflowFormation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgAutoformation::workflowFormation.singular") }} : {{ $itemWorkflowFormation }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show