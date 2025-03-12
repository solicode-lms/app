{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eRelationship-table')
<div class="card-body table-responsive p-0 crud-card-body" id="eRelationships-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" modelname="eRelationship" label="{{ ucfirst(__('PkgGapp::eRelationship.name')) }}" />
                <x-sortable-column field="type" modelname="eRelationship" label="{{ ucfirst(__('PkgGapp::eRelationship.type')) }}" />
                <x-sortable-column field="source_e_model_id" modelname="eRelationship" label="{{ ucfirst(__('PkgGapp::eModel.singular')) }}" />
                <x-sortable-column field="target_e_model_id" modelname="eRelationship" label="{{ ucfirst(__('PkgGapp::eModel.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('eRelationship-table-tbody')
            @foreach ($eRelationships_data as $eRelationship)
                <tr id="eRelationship-row-{{$eRelationship->id}}">
                    <td>
                     <span @if(strlen($eRelationship->name) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $eRelationship->name }}" 
                        @endif>
                        {{ Str::limit($eRelationship->name, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($eRelationship->type) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $eRelationship->type }}" 
                        @endif>
                        {{ Str::limit($eRelationship->type, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($eRelationship->sourceEModel) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $eRelationship->sourceEModel }}" 
                        @endif>
                        {{ Str::limit($eRelationship->sourceEModel, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($eRelationship->targetEModel) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $eRelationship->targetEModel }}" 
                        @endif>
                        {{ Str::limit($eRelationship->targetEModel, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-eRelationship')
                        @can('view', $eRelationship)
                            <a href="{{ route('eRelationships.show', ['eRelationship' => $eRelationship->id]) }}" data-id="{{$eRelationship->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-eRelationship')
                        @can('update', $eRelationship)
                            <a href="{{ route('eRelationships.edit', ['eRelationship' => $eRelationship->id]) }}" data-id="{{$eRelationship->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-eRelationship')
                        @can('delete', $eRelationship)
                            <form class="context-state" action="{{ route('eRelationships.destroy',['eRelationship' => $eRelationship->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$eRelationship->id}}">
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
    @section('eRelationship-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eRelationships_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>