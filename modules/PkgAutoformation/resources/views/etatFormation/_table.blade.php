{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatFormation-table')
<div class="card-body p-0 crud-card-body" id="etatFormations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $etatFormations_permissions['edit-etatFormation'] || $etatFormations_permissions['destroy-etatFormation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="nom" modelname="etatFormation" label="{{ucfirst(__('PkgAutoformation::etatFormation.nom'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="sys_color_id" modelname="etatFormation" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="formateur_id" modelname="etatFormation" label="{{ucfirst(__('PkgFormation::formateur.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatFormation-table-tbody')
            @foreach ($etatFormations_data as $etatFormation)
                @php
                    $isEditable = $etatFormations_permissions['edit-etatFormation'] && $etatFormations_permissionsByItem['update'][$etatFormation->id];
                @endphp
                <tr id="etatFormation-row-{{$etatFormation->id}}" data-id="{{$etatFormation->id}}">
                    <x-checkbox-row :item="$etatFormation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatFormation->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $etatFormation->nom }}" >
                        {{ $etatFormation->nom }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatFormation->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $etatFormation->sysColor }}" >
                        <x-badge 
                        :text="$etatFormation->sysColor->name ?? ''" 
                        :background="$etatFormation->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatFormation->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $etatFormation->formateur }}" >
                        {{  $etatFormation->formateur }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($etatFormations_permissions['edit-etatFormation'])
                        <x-action-button :entity="$etatFormation" actionName="edit">
                        @if($etatFormations_permissionsByItem['update'][$etatFormation->id])
                            <a href="{{ route('etatFormations.edit', ['etatFormation' => $etatFormation->id]) }}" data-id="{{$etatFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($etatFormations_permissions['show-etatFormation'])
                        <x-action-button :entity="$etatFormation" actionName="show">
                        @if($etatFormations_permissionsByItem['view'][$etatFormation->id])
                            <a href="{{ route('etatFormations.show', ['etatFormation' => $etatFormation->id]) }}" data-id="{{$etatFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$etatFormation" actionName="delete">
                        @if($etatFormations_permissions['destroy-etatFormation'])
                        @if($etatFormations_permissionsByItem['delete'][$etatFormation->id])
                            <form class="context-state" action="{{ route('etatFormations.destroy',['etatFormation' => $etatFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$etatFormation->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                        @endif
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
    @section('etatFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>