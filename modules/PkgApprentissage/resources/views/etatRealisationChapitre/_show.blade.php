{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationChapitre-show')
<div id="etatRealisationChapitre-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationChapitre.nom')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEtatRealisationChapitre->nom) && $itemEtatRealisationChapitre->nom !== '')
          {{ $itemEtatRealisationChapitre->nom }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationChapitre.code')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemEtatRealisationChapitre->code) && $itemEtatRealisationChapitre->code !== '')
          {{ $itemEtatRealisationChapitre->code }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationChapitre.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemEtatRealisationChapitre->description) && $itemEtatRealisationChapitre->description !== '')
    {!! $itemEtatRealisationChapitre->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationChapitre.is_editable_only_by_formateur')) }}</small>
                              
      @if($itemEtatRealisationChapitre->is_editable_only_by_formateur)
        <span class="badge badge-success">{{ __('Oui') }}</span>
      @else
        <span class="badge badge-secondary">{{ __('Non') }}</span>
      @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                              
      @if($itemEtatRealisationChapitre->sysColor)
        @php
          $related = $itemEtatRealisationChapitre->sysColor;
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
            <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationChapitre.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgApprentissage::realisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationChapitre.show_' . $itemEtatRealisationChapitre->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('etatRealisationChapitres.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-etatRealisationChapitre')
          <x-action-button :entity="$itemEtatRealisationChapitre" actionName="edit">
          @can('update', $itemEtatRealisationChapitre)
              <a href="{{ route('etatRealisationChapitres.edit', ['etatRealisationChapitre' => $itemEtatRealisationChapitre->id]) }}" data-id="{{$itemEtatRealisationChapitre->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::etatRealisationChapitre.singular") }} : {{ $itemEtatRealisationChapitre }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show