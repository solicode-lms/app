{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowTache-show')
<div id="workflowTache-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::workflowTache.ordre')) }}</small>
                  <span>
                    @if(! is_null($itemWorkflowTache->ordre))
                      {{ $itemWorkflowTache->ordre }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::workflowTache.code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemWorkflowTache->code) && $itemWorkflowTache->code !== '')
        {{ $itemWorkflowTache->code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::workflowTache.titre')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemWorkflowTache->titre) && $itemWorkflowTache->titre !== '')
        {{ $itemWorkflowTache->titre }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::workflowTache.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemWorkflowTache->description) && $itemWorkflowTache->description !== '')
                    {!! $itemWorkflowTache->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgRealisationTache::workflowTache.is_editable_only_by_formateur')) }}</small>
                  @if($itemWorkflowTache->is_editable_only_by_formateur)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.singular')) }}</small>
                  @if($itemWorkflowTache->sysColor)
                  @php
                    $related = $itemWorkflowTache->sysColor;
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
            @if(auth()->user()?->can('show-etatRealisationTache') || auth()->user()?->can('create-etatRealisationTache'))
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationTache::etatRealisationTache.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgRealisationTache::etatRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'workflowTache.show_' . $itemWorkflowTache->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('workflowTaches.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-workflowTache')
          <x-action-button :entity="$itemWorkflowTache" actionName="edit">
          @can('update', $itemWorkflowTache)
              <a href="{{ route('workflowTaches.edit', ['workflowTache' => $itemWorkflowTache->id]) }}" data-id="{{$itemWorkflowTache->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgRealisationTache::workflowTache.singular") }} : {{ $itemWorkflowTache }}';
    window.showUIId = 'workflowTache-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show