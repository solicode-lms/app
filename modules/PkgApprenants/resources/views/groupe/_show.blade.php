{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('groupe-show')
<div id="groupe-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::groupe.code')) }}</small>
                              @if(! is_null($itemGroupe->code) && $itemGroupe->code !== '')
        {{ $itemGroupe->code }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::groupe.nom')) }}</small>
                              @if(! is_null($itemGroupe->nom) && $itemGroupe->nom !== '')
        {{ $itemGroupe->nom }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::groupe.description')) }}</small>
                          <!-- Valeur avec sauts de ligne -->
  @if(! is_null($itemGroupe->description) && $itemGroupe->description !== '')
    {!! $itemGroupe->description !!}
  @else
    <span class="text-muted">—</span>
  @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::filiere.singular')) }}</small>
                              
      @if($itemGroupe->filiere)
        {{ $itemGroupe->filiere }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::anneeFormation.singular')) }}</small>
                              
      @if($itemGroupe->anneeFormation)
        {{ $itemGroupe->anneeFormation }}
      @else
        —
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::affectationProjet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationProjets::affectationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'groupe.show_' . $itemGroupe->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::apprenant.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemGroupe->apprenants->isNotEmpty())
          <div>
            @foreach($itemGroupe->apprenants as $apprenant)
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
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.plural')) }}</small>
                              <!-- Valeurs many-to-many -->
        @if($itemGroupe->formateurs->isNotEmpty())
          <div>
            @foreach($itemGroupe->formateurs as $formateur)
              <span class="badge badge-info mr-1">
                {{ $formateur }}
              </span>
            @endforeach
          </div>
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgApprenants::sousGroupe.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgApprenants::sousGroupe._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'groupe.show_' . $itemGroupe->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('groupes.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-groupe')
          <x-action-button :entity="$itemGroupe" actionName="edit">
          @can('update', $itemGroupe)
              <a href="{{ route('groupes.edit', ['groupe' => $itemGroupe->id]) }}" data-id="{{$itemGroupe->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprenants::groupe.singular") }} : {{ $itemGroupe }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show