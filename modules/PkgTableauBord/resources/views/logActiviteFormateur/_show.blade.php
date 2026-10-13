{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('logActiviteFormateur-show')
<div id="logActiviteFormateur-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgFormation::formateur.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemLogActiviteFormateur->formateur)
                  {{ $itemLogActiviteFormateur->formateur }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::logActiviteFormateur.type_activite')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemLogActiviteFormateur->type_activite) && $itemLogActiviteFormateur->type_activite !== '')
        {{ $itemLogActiviteFormateur->type_activite }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::logActiviteFormateur.duree_secondes')) }}</small>
                  <span>
                    @if(! is_null($itemLogActiviteFormateur->duree_secondes))
                      {{ $itemLogActiviteFormateur->duree_secondes }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::logActiviteFormateur.date_activite')) }}</small>
                  <span>
                    @if ($itemLogActiviteFormateur->date_activite)
                    {{ \Carbon\Carbon::parse($itemLogActiviteFormateur->date_activite)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgTableauBord::logActiviteFormateur.semaine_annee')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemLogActiviteFormateur->semaine_annee) && $itemLogActiviteFormateur->semaine_annee !== '')
        {{ $itemLogActiviteFormateur->semaine_annee }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('logActiviteFormateurs.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-logActiviteFormateur')
          <x-action-button :entity="$itemLogActiviteFormateur" actionName="edit">
          @can('update', $itemLogActiviteFormateur)
              <a href="{{ route('logActiviteFormateurs.edit', ['logActiviteFormateur' => $itemLogActiviteFormateur->id]) }}" data-id="{{$itemLogActiviteFormateur->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgTableauBord::logActiviteFormateur.singular") }} : {{ $itemLogActiviteFormateur }}';
    window.showUIId = 'logActiviteFormateur-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show