{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetType-table')
<div class="card-body p-0 crud-card-body" id="widgetTypes-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-widgetType') || Auth::user()->can('destroy-widgetType');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="41"  field="type" modelname="widgetType" label="{{ucfirst(__('PkgWidgets::widgetType.type'))}}" />
                <x-sortable-column :sortable="true" width="41"  field="description" modelname="widgetType" label="{{ucfirst(__('PkgWidgets::widgetType.description'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widgetType-table-tbody')
            @foreach ($widgetTypes_data as $widgetType)
                @php
                    $isEditable = Auth::user()->can('edit-widgetType') && Auth::user()->can('update', $widgetType);
                @endphp
                <tr id="widgetType-row-{{$widgetType->id}}" data-id="{{$widgetType->id}}">
                    <x-checkbox-row :item="$widgetType" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widgetType->id}}" data-field="type"  data-toggle="tooltip" title="{{ $widgetType->type }}" >
                    <x-field :entity="$widgetType" field="type">
                        {{ $widgetType->type }}
                    </x-field>
                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widgetType->id}}" data-field="description"  data-toggle="tooltip" title="{{ $widgetType->description }}" >
                    <x-field :entity="$widgetType" field="description">
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($widgetType->description, 30) !!}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-widgetType')
                        <x-action-button :entity="$widgetType" actionName="edit">
                        @can('update', $widgetType)
                            <a href="{{ route('widgetTypes.edit', ['widgetType' => $widgetType->id]) }}" data-id="{{$widgetType->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-widgetType')
                        <x-action-button :entity="$widgetType" actionName="show">
                        @can('view', $widgetType)
                            <a href="{{ route('widgetTypes.show', ['widgetType' => $widgetType->id]) }}" data-id="{{$widgetType->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$widgetType" actionName="delete">
                        @can('destroy-widgetType')
                        @can('delete', $widgetType)
                            <form class="context-state" action="{{ route('widgetTypes.destroy',['widgetType' => $widgetType->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$widgetType->id}}">
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
    @section('widgetType-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgetTypes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>