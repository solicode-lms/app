{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrable-table')
<div class="card-body p-0 crud-card-body" id="livrables-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-livrable') || Auth::user()->can('destroy-livrable');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="41" field="nature_livrable_id" modelname="livrable" label="{{ucfirst(__('PkgCreationProjet::natureLivrable.singular'))}}" />
                <x-sortable-column :sortable="true" width="41"  field="titre" modelname="livrable" label="{{ucfirst(__('PkgCreationProjet::livrable.titre'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('livrable-table-tbody')
            @foreach ($livrables_data as $livrable)
                @php
                    $isEditable = Auth::user()->can('edit-livrable') && Auth::user()->can('update', $livrable);
                @endphp
                <tr id="livrable-row-{{$livrable->id}}" data-id="{{$livrable->id}}">
                    <x-checkbox-row :item="$livrable" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$livrable->id}}" data-field="nature_livrable_id"  data-toggle="tooltip" title="{{ $livrable->natureLivrable }}" >
                    <x-field :entity="$livrable" field="natureLivrable">
                       
                         {{  $livrable->natureLivrable }}
                    </x-field>
                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$livrable->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $livrable->titre }}" >
                    <x-field :entity="$livrable" field="titre">
                        {{ $livrable->titre }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-livrable')
                        <x-action-button :entity="$livrable" actionName="edit">
                        @can('update', $livrable)
                            <a href="{{ route('livrables.edit', ['livrable' => $livrable->id]) }}" data-id="{{$livrable->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-livrable')
                        <x-action-button :entity="$livrable" actionName="show">
                        @can('view', $livrable)
                            <a href="{{ route('livrables.show', ['livrable' => $livrable->id]) }}" data-id="{{$livrable->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$livrable" actionName="delete">
                        @can('destroy-livrable')
                        @can('delete', $livrable)
                            <form class="context-state" action="{{ route('livrables.destroy',['livrable' => $livrable->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$livrable->id}}">
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
    @section('livrable-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $livrables_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>