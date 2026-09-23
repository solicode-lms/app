{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationQcm-show')
<div id="etatRealisationQcm-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::etatRealisationQcm.titre')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEtatRealisationQcm->titre) && $itemEtatRealisationQcm->titre !== '')
        {{ $itemEtatRealisationQcm->titre }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::etatRealisationQcm.description')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEtatRealisationQcm->description) && $itemEtatRealisationQcm->description !== '')
        {{ $itemEtatRealisationQcm->description }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgQcm::etatRealisationQcm.is_editable_by_formateur')) }}</small>
                  @if($itemEtatRealisationQcm->is_editable_by_formateur)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                  @if($itemEtatRealisationQcm->sysColor)
                  @php
                    $related = $itemEtatRealisationQcm->sysColor;
                  @endphp
                  <span 
                    class="badge" 
                    style="background-color: {{ $related->hex }}; color: #fff;"
                  >
                    {{ $related }}
                  </span>
                  @else
                  <span class="text-muted">—</span>
                  @endif
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-realisationQcm') && $itemEtatRealisationQcm->realisationQcms->isNotEmpty())  
                  || auth()->user()?->can('create-realisationQcm')
                  || (auth()->user()?->can('edit-realisationQcm')  && $itemEtatRealisationQcm->realisationQcms->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgQcm::realisationQcm.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgQcm::realisationQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationQcm.show_' . $itemEtatRealisationQcm->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('etatRealisationQcms.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-etatRealisationQcm')
          <x-action-button :entity="$itemEtatRealisationQcm" actionName="edit">
          @can('update', $itemEtatRealisationQcm)
              <a href="{{ route('etatRealisationQcms.edit', ['etatRealisationQcm' => $itemEtatRealisationQcm->id]) }}" data-id="{{$itemEtatRealisationQcm->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgQcm::etatRealisationQcm.singular") }} : {{ $itemEtatRealisationQcm }}';
    window.showUIId = 'etatRealisationQcm-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show