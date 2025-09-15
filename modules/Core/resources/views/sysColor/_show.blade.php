{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysColor-show')
<div id="sysColor-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.name')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemSysColor->name) && $itemSysColor->name !== '')
        {{ $itemSysColor->name }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.hex')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemSysColor->hex) && $itemSysColor->hex !== '')
        {{ $itemSysColor->hex }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-etatRealisationTache') && $itemSysColor->etatRealisationTaches->isNotEmpty())  
                  || auth()->user()?->can('create-etatRealisationTache')
                  || (auth()->user()?->can('edit-etatRealisationTache')  && $itemSysColor->etatRealisationTaches->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationTache::etatRealisationTache.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgRealisationTache::etatRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-sysModel') && $itemSysColor->sysModels->isNotEmpty())  
                  || auth()->user()?->can('create-sysModel')
                  || (auth()->user()?->can('edit-sysModel')  && $itemSysColor->sysModels->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('Core::sysModel.plural')) }}</small>
                  <div class="pt-2">
                        @include('Core::sysModel._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-etatEvaluationProjet') && $itemSysColor->etatEvaluationProjets->isNotEmpty())  
                  || auth()->user()?->can('create-etatEvaluationProjet')
                  || (auth()->user()?->can('edit-etatEvaluationProjet')  && $itemSysColor->etatEvaluationProjets->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgEvaluateurs::etatEvaluationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-etatRealisationChapitre') && $itemSysColor->etatRealisationChapitres->isNotEmpty())  
                  || auth()->user()?->can('create-etatRealisationChapitre')
                  || (auth()->user()?->can('edit-etatRealisationChapitre')  && $itemSysColor->etatRealisationChapitres->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::etatRealisationChapitre.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::etatRealisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-sysModule') && $itemSysColor->sysModules->isNotEmpty())  
                  || auth()->user()?->can('create-sysModule')
                  || (auth()->user()?->can('edit-sysModule')  && $itemSysColor->sysModules->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('Core::sysModule.plural')) }}</small>
                  <div class="pt-2">
                        @include('Core::sysModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-etatRealisationCompetence') && $itemSysColor->etatRealisationCompetences->isNotEmpty())  
                  || auth()->user()?->can('create-etatRealisationCompetence')
                  || (auth()->user()?->can('edit-etatRealisationCompetence')  && $itemSysColor->etatRealisationCompetences->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::etatRealisationCompetence.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::etatRealisationCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-etatRealisationMicroCompetence') && $itemSysColor->etatRealisationMicroCompetences->isNotEmpty())  
                  || auth()->user()?->can('create-etatRealisationMicroCompetence')
                  || (auth()->user()?->can('edit-etatRealisationMicroCompetence')  && $itemSysColor->etatRealisationMicroCompetences->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::etatRealisationMicroCompetence.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::etatRealisationMicroCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-etatsRealisationProjet') && $itemSysColor->etatsRealisationProjets->isNotEmpty())  
                  || auth()->user()?->can('create-etatsRealisationProjet')
                  || (auth()->user()?->can('edit-etatsRealisationProjet')  && $itemSysColor->etatsRealisationProjets->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgRealisationProjets::etatsRealisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-etatRealisationModule') && $itemSysColor->etatRealisationModules->isNotEmpty())  
                  || auth()->user()?->can('create-etatRealisationModule')
                  || (auth()->user()?->can('edit-etatRealisationModule')  && $itemSysColor->etatRealisationModules->isNotEmpty() )
                  )
            <div class="col-12 col-md-12 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::etatRealisationModule.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::etatRealisationModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-etatRealisationUa') && $itemSysColor->etatRealisationUas->isNotEmpty())  
                  || auth()->user()?->can('create-etatRealisationUa')
                  || (auth()->user()?->can('edit-etatRealisationUa')  && $itemSysColor->etatRealisationUas->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprentissage::etatRealisationUa.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprentissage::etatRealisationUa._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-sectionWidget') && $itemSysColor->sectionWidgets->isNotEmpty())  
                  || auth()->user()?->can('create-sectionWidget')
                  || (auth()->user()?->can('edit-sectionWidget')  && $itemSysColor->sectionWidgets->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgWidgets::sectionWidget.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgWidgets::sectionWidget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-widget') && $itemSysColor->widgets->isNotEmpty())  
                  || auth()->user()?->can('create-widget')
                  || (auth()->user()?->can('edit-widget')  && $itemSysColor->widgets->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgWidgets::widget.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            @if(
                  (auth()->user()?->can('show-workflowTache') && $itemSysColor->workflowTaches->isNotEmpty())  
                  || auth()->user()?->can('create-workflowTache')
                  || (auth()->user()?->can('edit-workflowTache')  && $itemSysColor->workflowTaches->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationTache::workflowTache.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgRealisationTache::workflowTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('sysColors.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-sysColor')
          <x-action-button :entity="$itemSysColor" actionName="edit">
          @can('update', $itemSysColor)
              <a href="{{ route('sysColors.edit', ['sysColor' => $itemSysColor->id]) }}" data-id="{{$itemSysColor->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("Core::sysColor.singular") }} : {{ $itemSysColor }}';
    window.showUIId = 'sysColor-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show