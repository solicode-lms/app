{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysColor-table')
<div class="card-body p-0 crud-card-body" id="sysColors-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-sysColor') || Auth::user()->can('destroy-sysColor');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="82"  field="name" modelname="sysColor" label="{{ucfirst(__('Core::sysColor.name'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sysColor-table-tbody')
            @foreach ($sysColors_data as $sysColor)
                @php
                    $isEditable = Auth::user()->can('edit-sysColor') && Auth::user()->can('update', $sysColor);
                @endphp
                <tr id="sysColor-row-{{$sysColor->id}}" data-id="{{$sysColor->id}}">
                    <x-checkbox-row :item="$sysColor" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysColor->id}}" data-field="name"  data-toggle="tooltip" title="{{ $sysColor->name }}" >
                    <x-field :entity="$sysColor" field="name">
                        {{ $sysColor->name }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-sysColor')
                        <x-action-button :entity="$sysColor" actionName="edit">
                        @can('update', $sysColor)
                            <a href="{{ route('sysColors.edit', ['sysColor' => $sysColor->id]) }}" data-id="{{$sysColor->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-sysColor')
                        <x-action-button :entity="$sysColor" actionName="show">
                        @can('view', $sysColor)
                            <a href="{{ route('sysColors.show', ['sysColor' => $sysColor->id]) }}" data-id="{{$sysColor->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$sysColor" actionName="delete">
                        @can('destroy-sysColor')
                        @can('delete', $sysColor)
                            <form class="context-state" action="{{ route('sysColors.destroy',['sysColor' => $sysColor->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$sysColor->id}}">
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
    @section('sysColor-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysColors_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>