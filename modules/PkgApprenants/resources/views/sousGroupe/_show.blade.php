{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sousGroupe-show')
<div id="sousGroupe-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::sousGroupe.nom')) }}</small>
                              @if(! is_null($itemSousGroupe->nom) && $itemSousGroupe->nom !== '')
        {{ $itemSousGroupe->nom }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::sousGroupe.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemSousGroupe->description) && $itemSousGroupe->description !== '')
    {!! $itemSousGroupe->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::groupe.singular')) }}</small>
                              
      @if($itemSousGroupe->groupe)
        {{ $itemSousGroupe->groupe }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::affectationProjet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationProjets::affectationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sousGroupe.show_' . $itemSousGroupe->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemSousGroupe->apprenants->isNotEmpty())
          <div>
            @foreach($itemSousGroupe->apprenants as $apprenant)
              <span class="badge badge-info mr-1">
                {{ $apprenant }}
              </span>
            @endforeach
          </div>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('sousGroupes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-sousGroupe')
          <x-action-button :entity="$itemSousGroupe" actionName="edit">
          @can('update', $itemSousGroupe)
              <a href="{{ route('sousGroupes.edit', ['sousGroupe' => $itemSousGroupe->id]) }}" data-id="{{$itemSousGroupe->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprenants::sousGroupe.singular") }} : {{ $itemSousGroupe }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show