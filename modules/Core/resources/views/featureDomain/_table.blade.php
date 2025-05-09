{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('featureDomain-table')
<div class="card-body p-0 crud-card-body" id="featureDomains-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-featureDomain') || Auth::user()->can('destroy-featureDomain');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="41"  field="name" modelname="featureDomain" label="{{ucfirst(__('Core::featureDomain.name'))}}" />
                <x-sortable-column :sortable="true" width="41" field="sys_module_id" modelname="featureDomain" label="{{ucfirst(__('Core::sysModule.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('featureDomain-table-tbody')
            @foreach ($featureDomains_data as $featureDomain)
                @php
                    $isEditable = Auth::user()->can('edit-featureDomain') && Auth::user()->can('update', $featureDomain);
                @endphp
                <tr id="featureDomain-row-{{$featureDomain->id}}" data-id="{{$featureDomain->id}}">
                    <x-checkbox-row :item="$featureDomain" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$featureDomain->id}}" data-field="name"  data-toggle="tooltip" title="{{ $featureDomain->name }}" >
                    <x-field :entity="$featureDomain" field="name">
                        {{ $featureDomain->name }}
                    </x-field>
                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$featureDomain->id}}" data-field="sys_module_id"  data-toggle="tooltip" title="{{ $featureDomain->sysModule }}" >
                    <x-field :entity="$featureDomain" field="sysModule">
                       
                         {{  $featureDomain->sysModule }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-featureDomain')
                        <x-action-button :entity="$featureDomain" actionName="edit">
                        @can('update', $featureDomain)
                            <a href="{{ route('featureDomains.edit', ['featureDomain' => $featureDomain->id]) }}" data-id="{{$featureDomain->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-featureDomain')
                        <x-action-button :entity="$featureDomain" actionName="show">
                        @can('view', $featureDomain)
                            <a href="{{ route('featureDomains.show', ['featureDomain' => $featureDomain->id]) }}" data-id="{{$featureDomain->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$featureDomain" actionName="delete">
                        @can('destroy-featureDomain')
                        @can('delete', $featureDomain)
                            <form class="context-state" action="{{ route('featureDomains.destroy',['featureDomain' => $featureDomain->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$featureDomain->id}}">
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
    @section('featureDomain-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $featureDomains_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>