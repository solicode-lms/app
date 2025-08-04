{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eMetadatum-show')
<div id="eMetadatum-crud-show">
        <div class="card-body">
            <div class="row no-gutters mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadatum.value_boolean')) }}</small>
                  @if($itemEMetadatum->value_boolean)
                  <span class="badge badge-success">{{ __('Oui') }}</span>
                  @else
                  <span class="badge badge-secondary">{{ __('Non') }}</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadatum.value_string')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEMetadatum->value_string) && $itemEMetadatum->value_string !== '')
        {{ $itemEMetadatum->value_string }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadatum.value_integer')) }}</small>
                  <span>
                    @if(! is_null($itemEMetadatum->value_integer))
                      {{ $itemEMetadatum->value_integer }}
                    @else
                      —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadatum.value_float')) }}</small>
                  <span>
                  @if(! is_null($itemEMetadatum->value_float))
                  {{ number_format($itemEMetadatum->value_float, 2, '.', '') }}
                  @else
                  —
                  @endif
                  </span>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadatum.value_date')) }}</small>
                  <span>
                    @if ($itemEMetadatum->value_date)
                    {{ \Carbon\Carbon::parse($itemEMetadatum->value_date)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadatum.value_datetime')) }}</small>
                  <span>
                    @if ($itemEMetadatum->value_datetime)
                    {{ \Carbon\Carbon::parse($itemEMetadatum->value_datetime)->isoFormat('LLL') }}
                    @else
                    —
                    @endif
                  </span>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadatum.value_enum')) }}</small>
    {{-- Affichage texte par défaut --}}
    @if(!is_null($itemEMetadatum->value_enum) && $itemEMetadatum->value_enum !== '')
        {{ $itemEMetadatum->value_enum }}
    @else
        <span class="text-muted">—</span>
    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadatum.value_json')) }}</small>
                  @if(! is_null($itemEMetadatum->value_json))
                    <pre class="border rounded p-2 bg-light" style="overflow:auto;">
                  {!! json_encode($itemEMetadatum->value_json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) !!}
                    </pre>
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadatum.value_text')) }}</small>
                  <!-- Valeur avec sauts de ligne -->
                  @if(! is_null($itemEMetadatum->value_text) && $itemEMetadatum->value_text !== '')
                    {!! $itemEMetadatum->value_text !!}
                  @else
                    <span class="text-muted">—</span>
                  @endif                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eModel.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemEMetadatum->eModel)
                  {{ $itemEMetadatum->eModel }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eDataField.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemEMetadatum->eDataField)
                  {{ $itemEMetadatum->eDataField }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-3 px-2">
                <div class="border rounded p-2 h-100">
                  <small class="text-muted d-block">{{ ucfirst(__('PkgGapp::eMetadataDefinition.singular')) }}</small>

                {{-- Affichage texte classique --}}
                @if($itemEMetadatum->eMetadataDefinition)
                  {{ $itemEMetadatum->eMetadataDefinition }}
                @else
                  <span class="text-muted">—</span>
                @endif
                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('eMetadata.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
       
          @can('edit-eMetadatum')
          <x-action-button :entity="$itemEMetadatum" actionName="edit">
          @can('update', $itemEMetadatum)
              <a href="{{ route('eMetadata.edit', ['eMetadatum' => $itemEMetadatum->id]) }}" data-id="{{$itemEMetadatum->id}}" class="btn btn-info ml-2 editEntity">
                  <i class="fas fa-pen-square"></i>
              </a>
          @endcan
          </x-action-button>
          @endcan

        </div>
</div>
<script>
    window.modalTitle   = '{{ __("PkgGapp::eMetadatum.singular") }} : {{ $itemEMetadatum }}';
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState    = @json($viewState);
</script>
@show