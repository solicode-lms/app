{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatChapitre-show')
<div id="etatChapitre-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::etatChapitre.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEtatChapitre->nom) && $itemEtatChapitre->nom !== '')
          {{ $itemEtatChapitre->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::workflowChapitre.singular')) }}</small>
                              
      @if($itemEtatChapitre->workflowChapitre)
        {{ $itemEtatChapitre->workflowChapitre }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemEtatChapitre->sysColor)
        @php
          $related = $itemEtatChapitre->sysColor;
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
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::etatChapitre.is_editable_only_by_formateur')) }}</small>
                              
      @if($itemEtatChapitre->is_editable_only_by_formateur)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgAutoformation::etatChapitre.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEtatChapitre->description) && $itemEtatChapitre->description !== '')
    {!! $itemEtatChapitre->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>
                              
      @if($itemEtatChapitre->formateur)
        {{ $itemEtatChapitre->formateur }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgAutoformation::realisationChapitre.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgAutoformation::realisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatChapitre.show_' . $itemEtatChapitre->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('etatChapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-etatChapitre')
          <x-action-button :entity="$itemEtatChapitre" actionName="edit">
          @can('update', $itemEtatChapitre)
              <a href="{{ route('etatChapitres.edit', ['etatChapitre' => $itemEtatChapitre->id]) }}" data-id="{{$itemEtatChapitre->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgAutoformation::etatChapitre.singular") }} : {{ $itemEtatChapitre }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show