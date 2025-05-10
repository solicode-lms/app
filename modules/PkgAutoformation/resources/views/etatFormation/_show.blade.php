{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatFormation-show')
<div id="etatFormation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::etatFormation.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEtatFormation->nom) && $itemEtatFormation->nom !== '')
          {{ $itemEtatFormation->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::workflowFormation.singular')) }}</small>
                              
      @if($itemEtatFormation->workflowFormation)
        {{ $itemEtatFormation->workflowFormation }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemEtatFormation->sysColor)
        @php
          $related = $itemEtatFormation->sysColor;
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
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::etatFormation.is_editable_only_by_formateur')) }}</small>
                              
      @if($itemEtatFormation->is_editable_only_by_formateur)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>
                              
      @if($itemEtatFormation->formateur)
        {{ $itemEtatFormation->formateur }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgAutoformation::realisationFormation.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgAutoformation::realisationFormation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatFormation.show_' . $itemEtatFormation->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::etatFormation.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEtatFormation->description) && $itemEtatFormation->description !== '')
    {!! $itemEtatFormation->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('etatFormations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-etatFormation')
          <x-action-button :entity="$itemEtatFormation" actionName="edit">
          @can('update', $itemEtatFormation)
              <a href="{{ route('etatFormations.edit', ['etatFormation' => $itemEtatFormation->id]) }}" data-id="{{$itemEtatFormation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgAutoformation::etatFormation.singular") }} : {{ $itemEtatFormation }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show