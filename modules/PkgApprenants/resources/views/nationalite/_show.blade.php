{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('nationalite-show')
<div id="nationalite-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::nationalite.code')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemNationalite->code) && $itemNationalite->code !== '')
        {{ $itemNationalite->code }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::nationalite.nom')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemNationalite->nom) && $itemNationalite->nom !== '')
        {{ $itemNationalite->nom }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgApprenants::nationalite.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemNationalite->description) && $itemNationalite->description !== '')
                    {!! $itemNationalite->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            @if(
                  (auth()->user()?->can('show-apprenant') && $itemNationalite->apprenants->isNotEmpty())  
                  || auth()->user()?->can('create-apprenant')
                  || (auth()->user()?->can('edit-apprenant')  && $itemNationalite->apprenants->isNotEmpty() )
                  )
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('PkgApprenants::apprenant.plural')) }}</small>
                  <div class="pt-2">
                        @include('PkgApprenants::apprenant._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'nationalite.show_' . $itemNationalite->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('nationalites.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-nationalite')
          <x-action-button :entity="$itemNationalite" actionName="edit">
          @can('update', $itemNationalite)
              <a href="{{ route('nationalites.edit', ['nationalite' => $itemNationalite->id]) }}" data-id="{{$itemNationalite->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgApprenants::nationalite.singular") }} : {{ $itemNationalite }}';
    window.showUIId = 'nationalite-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show