{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('anneeFormation-show')
<div id="anneeFormation-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::anneeFormation.titre')) }}</small>
                              @if(! is_null($itemAnneeFormation->titre) && $itemAnneeFormation->titre !== '')
        {{ $itemAnneeFormation->titre }}
      @else
        <span class="text-muted">—</span>
      @endif

          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::anneeFormation.date_debut')) }}</small>
                            
    <span>
      @if ($itemAnneeFormation->date_debut)
        {{ \Carbon\Carbon::parse($itemAnneeFormation->date_debut)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::anneeFormation.date_fin')) }}</small>
                            
    <span>
      @if ($itemAnneeFormation->date_fin)
        {{ \Carbon\Carbon::parse($itemAnneeFormation->date_fin)->isoFormat('LLL') }}
      @else
        —
      @endif
    </span>
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::affectationProjet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationProjets::affectationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'anneeFormation.show_' . $itemAnneeFormation->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgApprenants::groupe.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgApprenants::groupe._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'anneeFormation.show_' . $itemAnneeFormation->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgSessions::sessionFormation.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgSessions::sessionFormation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'anneeFormation.show_' . $itemAnneeFormation->id])
            </div>
          </div>
      </div>


            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('anneeFormations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-anneeFormation')
          <x-action-button :entity="$itemAnneeFormation" actionName="edit">
          @can('update', $itemAnneeFormation)
              <a href="{{ route('anneeFormations.edit', ['anneeFormation' => $itemAnneeFormation->id]) }}" data-id="{{$itemAnneeFormation->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgFormation::anneeFormation.singular") }} : {{ $itemAnneeFormation }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show