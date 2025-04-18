{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetOperation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="widgetOperations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-widgetOperation') || Auth::user()->can('destroy-widgetOperation');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column width="41"  field="operation" modelname="widgetOperation" label="{{ ucfirst(__('PkgWidgets::widgetOperation.operation')) }}" />
                <x-sortable-column width="41"  field="description" modelname="widgetOperation" label="{{ ucfirst(__('PkgWidgets::widgetOperation.description')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widgetOperation-table-tbody')
            @foreach ($widgetOperations_data as $widgetOperation)
                <tr id="widgetOperation-row-{{$widgetOperation->id}}">
                    <x-checkbox-row :item="$widgetOperation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="text-truncate" data-toggle="tooltip" title="{{ $widgetOperation->operation }}" >
                    <x-field :entity="$widgetOperation" field="operation">
                        {{ $widgetOperation->operation }}
                    </x-field>
                    </td>
                    <td style="max-width: 41%;" class="text-truncate" data-toggle="tooltip" title="{{ $widgetOperation->description }}" >
                    <x-field :entity="$widgetOperation" field="description">
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($widgetOperation->description, 30) !!}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-widgetOperation')
                        @can('update', $widgetOperation)
                            <a href="{{ route('widgetOperations.edit', ['widgetOperation' => $widgetOperation->id]) }}" data-id="{{$widgetOperation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-widgetOperation')
                        @can('view', $widgetOperation)
                            <a href="{{ route('widgetOperations.show', ['widgetOperation' => $widgetOperation->id]) }}" data-id="{{$widgetOperation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-widgetOperation')
                        @can('delete', $widgetOperation)
                            <form class="context-state" action="{{ route('widgetOperations.destroy',['widgetOperation' => $widgetOperation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$widgetOperation->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                    </td>
                </tr>
            @endforeach
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('widgetOperation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgetOperations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>