{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowChapitre-table')
<div class="card-body p-0 crud-card-body" id="workflowChapitres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-workflowChapitre') || Auth::user()->can('destroy-workflowChapitre');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="code" modelname="workflowChapitre" label="{{ucfirst(__('PkgAutoformation::workflowChapitre.code'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="titre" modelname="workflowChapitre" label="{{ucfirst(__('PkgAutoformation::workflowChapitre.titre'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="sys_color_id" modelname="workflowChapitre" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('workflowChapitre-table-tbody')
            @foreach ($workflowChapitres_data as $workflowChapitre)
                @php
                    $isEditable = Auth::user()->can('edit-workflowChapitre') && Auth::user()->can('update', $workflowChapitre);
                @endphp
                <tr id="workflowChapitre-row-{{$workflowChapitre->id}}" data-id="{{$workflowChapitre->id}}">
                    <x-checkbox-row :item="$workflowChapitre" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowChapitre->id}}" data-field="code"  data-toggle="tooltip" title="{{ $workflowChapitre->code }}" >
                    <x-field :entity="$workflowChapitre" field="code">
                        {{ $workflowChapitre->code }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowChapitre->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $workflowChapitre->titre }}" >
                    <x-field :entity="$workflowChapitre" field="titre">
                        {{ $workflowChapitre->titre }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$workflowChapitre->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $workflowChapitre->sysColor }}" >
                    <x-field :entity="$workflowChapitre" field="sysColor">
                        <x-badge 
                        :text="$workflowChapitre->sysColor->name ?? ''" 
                        :background="$workflowChapitre->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-workflowChapitre')
                        <x-action-button :entity="$workflowChapitre" actionName="edit">
                        @can('update', $workflowChapitre)
                            <a href="{{ route('workflowChapitres.edit', ['workflowChapitre' => $workflowChapitre->id]) }}" data-id="{{$workflowChapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-workflowChapitre')
                        <x-action-button :entity="$workflowChapitre" actionName="show">
                        @can('view', $workflowChapitre)
                            <a href="{{ route('workflowChapitres.show', ['workflowChapitre' => $workflowChapitre->id]) }}" data-id="{{$workflowChapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$workflowChapitre" actionName="delete">
                        @can('destroy-workflowChapitre')
                        @can('delete', $workflowChapitre)
                            <form class="context-state" action="{{ route('workflowChapitres.destroy',['workflowChapitre' => $workflowChapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$workflowChapitre->id}}">
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
    @section('workflowChapitre-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $workflowChapitres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>