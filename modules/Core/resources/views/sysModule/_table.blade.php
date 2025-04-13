{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysModule-table')
<div class="card-body table-responsive p-0 crud-card-body" id="sysModules-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="28.333333333333332"  field="name" modelname="sysModule" label="{{ ucfirst(__('Core::sysModule.name')) }}" />
                <x-sortable-column width="28.333333333333332"  field="is_active" modelname="sysModule" label="{{ ucfirst(__('Core::sysModule.is_active')) }}" />
                <x-sortable-column width="28.333333333333332" field="sys_color_id" modelname="sysModule" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sysModule-table-tbody')
            @foreach ($sysModules_data as $sysModule)
                <tr id="sysModule-row-{{$sysModule->id}}">
                    <td style="max-width: 28.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $sysModule->name }}" >
                    <x-field :entity="$sysModule" field="name">
                        {{ $sysModule->name }}
                    </x-field>
                    </td>
                    <td style="max-width: 28.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $sysModule->is_active }}" >
                    <x-field :entity="$sysModule" field="is_active">
                        {{ $sysModule->is_active }}
                    </x-field>
                    </td>
                    <td style="max-width: 28.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $sysModule->sysColor }}" >
                    <x-field :entity="$sysModule" field="sysColor">
                       
                         {{  $sysModule->sysColor }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-sysModule')
                        @can('update', $sysModule)
                            <a href="{{ route('sysModules.edit', ['sysModule' => $sysModule->id]) }}" data-id="{{$sysModule->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-sysModule')
                        @can('view', $sysModule)
                            <a href="{{ route('sysModules.show', ['sysModule' => $sysModule->id]) }}" data-id="{{$sysModule->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-sysModule')
                        @can('delete', $sysModule)
                            <form class="context-state" action="{{ route('sysModules.destroy',['sysModule' => $sysModule->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sysModule->id}}">
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
    @section('sysModule-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysModules_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>