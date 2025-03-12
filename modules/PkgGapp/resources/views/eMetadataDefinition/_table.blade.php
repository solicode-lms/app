{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eMetadataDefinition-table')
<div class="card-body table-responsive p-0 crud-card-body" id="eMetadataDefinitions-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" modelname="eMetadataDefinition" label="{{ ucfirst(__('PkgGapp::eMetadataDefinition.name')) }}" />
                <x-sortable-column field="groupe" modelname="eMetadataDefinition" label="{{ ucfirst(__('PkgGapp::eMetadataDefinition.groupe')) }}" />
                <x-sortable-column field="description" modelname="eMetadataDefinition" label="{{ ucfirst(__('PkgGapp::eMetadataDefinition.description')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('eMetadataDefinition-table-tbody')
            @foreach ($eMetadataDefinitions_data as $eMetadataDefinition)
                <tr id="eMetadataDefinition-row-{{$eMetadataDefinition->id}}">
                    <td>
                     <span @if(strlen($eMetadataDefinition->name) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $eMetadataDefinition->name }}" 
                        @endif>
                        {{ Str::limit($eMetadataDefinition->name, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($eMetadataDefinition->groupe) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $eMetadataDefinition->groupe }}" 
                        @endif>
                        {{ Str::limit($eMetadataDefinition->groupe, 40) }}
                    </span>
                    </td>
                    <td>{!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($eMetadataDefinition->description, 50) !!}</td>
                    <td class="text-right">

                        @can('show-eMetadataDefinition')
                        @can('view', $eMetadataDefinition)
                            <a href="{{ route('eMetadataDefinitions.show', ['eMetadataDefinition' => $eMetadataDefinition->id]) }}" data-id="{{$eMetadataDefinition->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-eMetadataDefinition')
                        @can('update', $eMetadataDefinition)
                            <a href="{{ route('eMetadataDefinitions.edit', ['eMetadataDefinition' => $eMetadataDefinition->id]) }}" data-id="{{$eMetadataDefinition->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-eMetadataDefinition')
                        @can('delete', $eMetadataDefinition)
                            <form class="context-state" action="{{ route('eMetadataDefinitions.destroy',['eMetadataDefinition' => $eMetadataDefinition->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$eMetadataDefinition->id}}">
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
    @section('eMetadataDefinition-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eMetadataDefinitions_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>