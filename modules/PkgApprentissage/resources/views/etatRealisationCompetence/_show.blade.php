{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationCompetence-show')
<div id="etatRealisationCompetence-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationCompetence.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemEtatRealisationCompetence->ordre))
                      {{ $itemEtatRealisationCompetence->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationCompetence.code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEtatRealisationCompetence->code) && $itemEtatRealisationCompetence->code !== '')
        {{ $itemEtatRealisationCompetence->code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationCompetence.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEtatRealisationCompetence->nom) && $itemEtatRealisationCompetence->nom !== '')
        {{ $itemEtatRealisationCompetence->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationCompetence.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemEtatRealisationCompetence->description) && $itemEtatRealisationCompetence->description !== '')
                    {!! $itemEtatRealisationCompetence->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                  @if($itemEtatRealisationCompetence->sysColor)
                  @php
                    $related = $itemEtatRealisationCompetence->sysColor;
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
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::realisationCompetence.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::realisationCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationCompetence.show_' . $itemEtatRealisationCompetence->id])
                  </div>
                  </div>
            </div>

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('etatRealisationCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-etatRealisationCompetence')
          <x-action-button :entity="$itemEtatRealisationCompetence" actionName="edit">
          @can('update', $itemEtatRealisationCompetence)
              <a href="{{ route('etatRealisationCompetences.edit', ['etatRealisationCompetence' => $itemEtatRealisationCompetence->id]) }}" data-id="{{$itemEtatRealisationCompetence->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::etatRealisationCompetence.singular") }} : {{ $itemEtatRealisationCompetence }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show