{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ePackage-table')
<div class="card-body p-0 crud-card-body" id="ePackages-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-ePackage') || Auth::user()->can('destroy-ePackage');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="82"  field="name" modelname="ePackage" label="{{ucfirst(__('PkgGapp::ePackage.name'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('ePackage-table-tbody')
            @foreach ($ePackages_data as $ePackage)
                @php
                    $isEditable = Auth::user()->can('edit-ePackage') && Auth::user()->can('update', $ePackage);
                @endphp
                <tr id="ePackage-row-{{$ePackage->id}}" data-id="{{$ePackage->id}}">
                    <x-checkbox-row :item="$ePackage" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$ePackage->id}}" data-field="name"  data-toggle="tooltip" title="{{ $ePackage->name }}" >
                    <x-field :entity="$ePackage" field="name">
                        {{ $ePackage->name }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-ePackage')
                        <x-action-button :entity="$ePackage" actionName="edit">
                        @can('update', $ePackage)
                            <a href="{{ route('ePackages.edit', ['ePackage' => $ePackage->id]) }}" data-id="{{$ePackage->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-ePackage')
                        <x-action-button :entity="$ePackage" actionName="show">
                        @can('view', $ePackage)
                            <a href="{{ route('ePackages.show', ['ePackage' => $ePackage->id]) }}" data-id="{{$ePackage->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$ePackage" actionName="delete">
                        @can('destroy-ePackage')
                        @can('delete', $ePackage)
                            <form class="context-state" action="{{ route('ePackages.destroy',['ePackage' => $ePackage->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$ePackage->id}}">
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
    @section('ePackage-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $ePackages_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>