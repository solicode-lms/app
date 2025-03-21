{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysColor-table')
<div class="card-body table-responsive p-0 crud-card-body" id="sysColors-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" modelname="sysColor" label="{{ ucfirst(__('Core::sysColor.name')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sysColor-table-tbody')
            @foreach ($sysColors_data as $sysColor)
                <tr id="sysColor-row-{{$sysColor->id}}">
                    <td>
                     <span @if(strlen($sysColor->name) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $sysColor->name }}" 
                        @endif>
                        {{ Str::limit($sysColor->name, 40) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-sysColor')
                        @can('view', $sysColor)
                            <a href="{{ route('sysColors.show', ['sysColor' => $sysColor->id]) }}" data-id="{{$sysColor->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-sysColor')
                        @can('update', $sysColor)
                            <a href="{{ route('sysColors.edit', ['sysColor' => $sysColor->id]) }}" data-id="{{$sysColor->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-sysColor')
                        @can('delete', $sysColor)
                            <form class="context-state" action="{{ route('sysColors.destroy',['sysColor' => $sysColor->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sysColor->id}}">
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
    @section('sysColor-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysColors_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>