{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('featureDomain-show')
<div id="featureDomain-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::featureDomain.name')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemFeatureDomain->name) && $itemFeatureDomain->name !== '')
        {{ $itemFeatureDomain->name }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::featureDomain.slug')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemFeatureDomain->slug) && $itemFeatureDomain->slug !== '')
        {{ $itemFeatureDomain->slug }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="show_group col-12 col-md-12 col-lg-12 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::featureDomain.description')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemFeatureDomain->description) && $itemFeatureDomain->description !== '')
                    {!! $itemFeatureDomain->description !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="show_group col-12 col-md-6 col-lg-6 mb-3 px-2 ">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('Core::sysModule.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemFeatureDomain->sysModule)
                  {{ $itemFeatureDomain->sysModule }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            @if(auth()->user()?->can('show-feature') || auth()->user()?->can('create-feature'))
            <div class="col-12 col-md-6 mb-3 px-2 show-has-many">
                  <div class="border rounded p-2 h-100 " >
                  <small class="text-muted d-block">  {{ ucfirst(__('Core::feature.plural')) }}</small>
                  <div class="pt-2">
                        @include('Core::feature._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'featureDomain.show_' . $itemFeatureDomain->id])
                  </div>
                  </div>
            </div>
            @endif

            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('featureDomains.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-featureDomain')
          <x-action-button :entity="$itemFeatureDomain" actionName="edit">
          @can('update', $itemFeatureDomain)
              <a href="{{ route('featureDomains.edit', ['featureDomain' => $itemFeatureDomain->id]) }}" data-id="{{$itemFeatureDomain->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("Core::featureDomain.singular") }} : {{ $itemFeatureDomain }}';
    window.showUIId = 'featureDomain-crud-show';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show