{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('labelRealisationTache-table')
<div class="card-body p-0 crud-card-body" id="labelRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-labelRealisationTache') || Auth::user()->can('destroy-labelRealisationTache');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="nom" modelname="labelRealisationTache" label="{{ucfirst(__('PkgGestionTaches::labelRealisationTache.nom'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="formateur_id" modelname="labelRealisationTache" label="{{ucfirst(__('PkgFormation::formateur.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="sys_color_id" modelname="labelRealisationTache" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('labelRealisationTache-table-tbody')
            @foreach ($labelRealisationTaches_data as $labelRealisationTache)
                @php
                    $isEditable = Auth::user()->can('edit-labelRealisationTache') && Auth::user()->can('update', $labelRealisationTache);
                @endphp
                <tr id="labelRealisationTache-row-{{$labelRealisationTache->id}}" data-id="{{$labelRealisationTache->id}}">
                    <x-checkbox-row :item="$labelRealisationTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$labelRealisationTache->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $labelRealisationTache->nom }}" >
                    <x-field :entity="$labelRealisationTache" field="nom">
                        {{ $labelRealisationTache->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$labelRealisationTache->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $labelRealisationTache->formateur }}" >
                    <x-field :entity="$labelRealisationTache" field="formateur">
                       
                         {{  $labelRealisationTache->formateur }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$labelRealisationTache->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $labelRealisationTache->sysColor }}" >
                    <x-field :entity="$labelRealisationTache" field="sysColor">
                        <x-badge 
                        :text="$labelRealisationTache->sysColor->name ?? ''" 
                        :background="$labelRealisationTache->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-labelRealisationTache')
                        <x-action-button :entity="$labelRealisationTache" actionName="edit">
                        @can('update', $labelRealisationTache)
                            <a href="{{ route('labelRealisationTaches.edit', ['labelRealisationTache' => $labelRealisationTache->id]) }}" data-id="{{$labelRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-labelRealisationTache')
                        <x-action-button :entity="$labelRealisationTache" actionName="show">
                        @can('view', $labelRealisationTache)
                            <a href="{{ route('labelRealisationTaches.show', ['labelRealisationTache' => $labelRealisationTache->id]) }}" data-id="{{$labelRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$labelRealisationTache" actionName="delete">
                        @can('destroy-labelRealisationTache')
                        @can('delete', $labelRealisationTache)
                            <form class="context-state" action="{{ route('labelRealisationTaches.destroy',['labelRealisationTache' => $labelRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$labelRealisationTache->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                        </x-action-button>
                    </td>
                </tr>
            @endforeach
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('labelRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $labelRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>