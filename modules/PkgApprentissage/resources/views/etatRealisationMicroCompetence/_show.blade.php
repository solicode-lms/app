{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationMicroCompetence-show')
<div id="etatRealisationMicroCompetence-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationMicroCompetence.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemEtatRealisationMicroCompetence->ordre))
                      {{ $itemEtatRealisationMicroCompetence->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationMicroCompetence.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEtatRealisationMicroCompetence->nom) && $itemEtatRealisationMicroCompetence->nom !== '')
        {{ $itemEtatRealisationMicroCompetence->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationMicroCompetence.code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEtatRealisationMicroCompetence->code) && $itemEtatRealisationMicroCompetence->code !== '')
        {{ $itemEtatRealisationMicroCompetence->code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                  @if($itemEtatRealisationMicroCompetence->sysColor)
                  @php
                    $related = $itemEtatRealisationMicroCompetence->sysColor;
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
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationMicroCompetence.is_editable_only_by_formateur')) }}</small>
                  @if($itemEtatRealisationMicroCompetence->is_editable_only_by_formateur)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprentissage::etatRealisationMicroCompetence.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemEtatRealisationMicroCompetence->description) && $itemEtatRealisationMicroCompetence->description !== '')
                    {!! $itemEtatRealisationMicroCompetence->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('etatRealisationMicroCompetences.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-etatRealisationMicroCompetence')
          <x-action-button :entity="$itemEtatRealisationMicroCompetence" actionName="edit">
          @can('update', $itemEtatRealisationMicroCompetence)
              <a href="{{ route('etatRealisationMicroCompetences.edit', ['etatRealisationMicroCompetence' => $itemEtatRealisationMicroCompetence->id]) }}" data-id="{{$itemEtatRealisationMicroCompetence->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprentissage::etatRealisationMicroCompetence.singular") }} : {{ $itemEtatRealisationMicroCompetence }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show