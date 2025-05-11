{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('natureLivrable-table')
<div class="card-body p-0 crud-card-body" id="natureLivrables-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-natureLivrable') || Auth::user()->can('destroy-natureLivrable');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="82"  field="nom" modelname="natureLivrable" label="{{ucfirst(__('PkgCreationProjet::natureLivrable.nom'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('natureLivrable-table-tbody')
            @foreach ($natureLivrables_data as $natureLivrable)
                @php
                    $isEditable = Auth::user()->can('edit-natureLivrable') && Auth::user()->can('update', $natureLivrable);
                @endphp
                <tr id="natureLivrable-row-{{$natureLivrable->id}}" data-id="{{$natureLivrable->id}}">
                    <x-checkbox-row :item="$natureLivrable" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$natureLivrable->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $natureLivrable->nom }}" >
                    <x-field :entity="$natureLivrable" field="nom">
                        {{ $natureLivrable->nom }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-natureLivrable')
                        <x-action-button :entity="$natureLivrable" actionName="edit">
                        @can('update', $natureLivrable)
                            <a href="{{ route('natureLivrables.edit', ['natureLivrable' => $natureLivrable->id]) }}" data-id="{{$natureLivrable->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-natureLivrable')
                        <x-action-button :entity="$natureLivrable" actionName="show">
                        @can('view', $natureLivrable)
                            <a href="{{ route('natureLivrables.show', ['natureLivrable' => $natureLivrable->id]) }}" data-id="{{$natureLivrable->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$natureLivrable" actionName="delete">
                        @can('destroy-natureLivrable')
                        @can('delete', $natureLivrable)
                            <form class="context-state" action="{{ route('natureLivrables.destroy',['natureLivrable' => $natureLivrable->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$natureLivrable->id}}">
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
    @section('natureLivrable-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $natureLivrables_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>