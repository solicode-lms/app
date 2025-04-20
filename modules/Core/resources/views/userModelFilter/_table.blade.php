{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('userModelFilter-table')
<div class="card-body table-responsive p-0 crud-card-body" id="userModelFilters-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-userModelFilter') || Auth::user()->can('destroy-userModelFilter');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="82" field="user_id" modelname="userModelFilter" label="{{ucfirst(__('PkgAutorisation::user.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('userModelFilter-table-tbody')
            @foreach ($userModelFilters_data as $userModelFilter)
                <tr id="userModelFilter-row-{{$userModelFilter->id}}" data-id="{{$userModelFilter->id}}">
                    <x-checkbox-row :item="$userModelFilter" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="text-truncate" data-toggle="tooltip" title="{{ $userModelFilter->user }}" >
                    <x-field :entity="$userModelFilter" field="user">
                       
                         {{  $userModelFilter->user }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-userModelFilter')
                        @can('update', $userModelFilter)
                            <a href="{{ route('userModelFilters.edit', ['userModelFilter' => $userModelFilter->id]) }}" data-id="{{$userModelFilter->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-userModelFilter')
                        @can('view', $userModelFilter)
                            <a href="{{ route('userModelFilters.show', ['userModelFilter' => $userModelFilter->id]) }}" data-id="{{$userModelFilter->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-userModelFilter')
                        @can('delete', $userModelFilter)
                            <form class="context-state" action="{{ route('userModelFilters.destroy',['userModelFilter' => $userModelFilter->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$userModelFilter->id}}">
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
    @section('userModelFilter-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $userModelFilters_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>