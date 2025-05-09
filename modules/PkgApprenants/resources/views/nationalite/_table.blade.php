{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('nationalite-table')
<div class="card-body p-0 crud-card-body" id="nationalites-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-nationalite') || Auth::user()->can('destroy-nationalite');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="82"  field="code" modelname="nationalite" label="{{ucfirst(__('PkgApprenants::nationalite.code'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('nationalite-table-tbody')
            @foreach ($nationalites_data as $nationalite)
                @php
                    $isEditable = Auth::user()->can('edit-nationalite') && Auth::user()->can('update', $nationalite);
                @endphp
                <tr id="nationalite-row-{{$nationalite->id}}" data-id="{{$nationalite->id}}">
                    <x-checkbox-row :item="$nationalite" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$nationalite->id}}" data-field="code"  data-toggle="tooltip" title="{{ $nationalite->code }}" >
                    <x-field :entity="$nationalite" field="code">
                        {{ $nationalite->code }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-nationalite')
                        <x-action-button :entity="$nationalite" actionName="edit">
                        @can('update', $nationalite)
                            <a href="{{ route('nationalites.edit', ['nationalite' => $nationalite->id]) }}" data-id="{{$nationalite->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-nationalite')
                        <x-action-button :entity="$nationalite" actionName="show">
                        @can('view', $nationalite)
                            <a href="{{ route('nationalites.show', ['nationalite' => $nationalite->id]) }}" data-id="{{$nationalite->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$nationalite" actionName="delete">
                        @can('destroy-nationalite')
                        @can('delete', $nationalite)
                            <form class="context-state" action="{{ route('nationalites.destroy',['nationalite' => $nationalite->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$nationalite->id}}">
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
    @section('nationalite-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $nationalites_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>