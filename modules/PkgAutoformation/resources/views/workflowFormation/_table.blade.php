{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowFormation-table')
<div class="card-body p-0 crud-card-body" id="workflowFormations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-workflowFormation') || Auth::user()->can('destroy-workflowFormation');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="code" modelname="workflowFormation" label="{{ucfirst(__('PkgAutoformation::workflowFormation.code'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="titre" modelname="workflowFormation" label="{{ucfirst(__('PkgAutoformation::workflowFormation.titre'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="sys_color_id" modelname="workflowFormation" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('workflowFormation-table-tbody')
            @foreach ($workflowFormations_data as $workflowFormation)
                @php
                    $isEditable = Auth::user()->can('edit-workflowFormation') && Auth::user()->can('update', $workflowFormation);
                @endphp
                <tr id="workflowFormation-row-{{$workflowFormation->id}}" data-id="{{$workflowFormation->id}}">
                    <x-checkbox-row :item="$workflowFormation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowFormation->id}}" data-field="code"  data-toggle="tooltip" title="{{ $workflowFormation->code }}" >
                    <x-field :entity="$workflowFormation" field="code">
                        {{ $workflowFormation->code }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowFormation->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $workflowFormation->titre }}" >
                    <x-field :entity="$workflowFormation" field="titre">
                        {{ $workflowFormation->titre }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowFormation->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $workflowFormation->sysColor }}" >
                    <x-field :entity="$workflowFormation" field="sysColor">
                        <x-badge 
                        :text="$workflowFormation->sysColor->name ?? ''" 
                        :background="$workflowFormation->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-workflowFormation')
                        <x-action-button :entity="$workflowFormation" actionName="edit">
                        @can('update', $workflowFormation)
                            <a href="{{ route('workflowFormations.edit', ['workflowFormation' => $workflowFormation->id]) }}" data-id="{{$workflowFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-workflowFormation')
                        <x-action-button :entity="$workflowFormation" actionName="show">
                        @can('view', $workflowFormation)
                            <a href="{{ route('workflowFormations.show', ['workflowFormation' => $workflowFormation->id]) }}" data-id="{{$workflowFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$workflowFormation" actionName="delete">
                        @can('destroy-workflowFormation')
                        @can('delete', $workflowFormation)
                            <form class="context-state" action="{{ route('workflowFormations.destroy',['workflowFormation' => $workflowFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$workflowFormation->id}}">
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
    @section('workflowFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $workflowFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>