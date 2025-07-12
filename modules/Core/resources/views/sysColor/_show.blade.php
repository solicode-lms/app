{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysColor-show')
<div id="sysColor-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
                      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.name')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemSysColor->name) && $itemSysColor->name !== '')
          {{ $itemSysColor->name }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
          <div class="border rounded p-2 h-100">
                        <small class="text-muted d-block">{{ ucfirst(__('Core::sysColor.hex')) }}</small>
                                <!-- Valeur texte -->
        @if(! is_null($itemSysColor->hex) && $itemSysColor->hex !== '')
          {{ $itemSysColor->hex }}
        @else
          <span class="text-muted">—</span>
        @endif
          </div>
      </div>
  

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationTache::etatRealisationTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationTache::etatRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('Core::sysModel.plural')) }}</small>
            <div class="pt-2">
                  @include('Core::sysModel._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgEvaluateurs::etatEvaluationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationTache::labelRealisationTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationTache::labelRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('Core::sysModule.plural')) }}</small>
            <div class="pt-2">
                  @include('Core::sysModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationProjets::etatsRealisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgWidgets::sectionWidget.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgWidgets::sectionWidget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgWidgets::widget.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
            </div>
          </div>
      </div>

      <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
          <div class="border rounded p-2 h-100 " >
            <small class="text-muted d-block">  {{ ucfirst(__('PkgRealisationTache::workflowTache.plural')) }}</small>
            <div class="pt-2">
                  @include('PkgRealisationTache::workflowTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.show_' . $itemSysColor->id])
            </div>
          </div>
      </div>


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
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show