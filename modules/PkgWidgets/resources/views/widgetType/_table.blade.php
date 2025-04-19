{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetType-table')
<div class="card-body table-responsive p-0 crud-card-body" id="widgetTypes-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-widgetType') || Auth::user()->can('destroy-widgetType');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="41"  field="type" modelname="widgetType" label="{{ ucfirst(__('PkgWidgets::widgetType.type')) }}" />
                <x-sortable-column :sortable="true" width="41"  field="description" modelname="widgetType" label="{{ ucfirst(__('PkgWidgets::widgetType.description')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widgetType-table-tbody')
            @foreach ($widgetTypes_data as $widgetType)
                <tr id="widgetType-row-{{$widgetType->id}}" data-id="{{$widgetType->id}}">
                    <x-checkbox-row :item="$widgetType" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="text-truncate" data-toggle="tooltip" title="{{ $widgetType->type }}" >
                    <x-field :entity="$widgetType" field="type">
                        {{ $widgetType->type }}
                    </x-field>
                    </td>
                    <td style="max-width: 41%;" class="text-truncate" data-toggle="tooltip" title="{{ $widgetType->description }}" >
                    <x-field :entity="$widgetType" field="description">
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($widgetType->description, 30) !!}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-widgetType')
                        @can('update', $widgetType)
                            <a href="{{ route('widgetTypes.edit', ['widgetType' => $widgetType->id]) }}" data-id="{{$widgetType->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-widgetType')
                        @can('view', $widgetType)
                            <a href="{{ route('widgetTypes.show', ['widgetType' => $widgetType->id]) }}" data-id="{{$widgetType->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-widgetType')
                        @can('delete', $widgetType)
                            <form class="context-state" action="{{ route('widgetTypes.destroy',['widgetType' => $widgetType->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$widgetType->id}}">
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
    @section('widgetType-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgetTypes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>