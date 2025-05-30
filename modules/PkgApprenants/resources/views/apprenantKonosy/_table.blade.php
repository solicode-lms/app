{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('apprenantKonosy-table')
<div class="card-body p-0 crud-card-body" id="apprenantKonosies-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-apprenantKonosy') || Auth::user()->can('destroy-apprenantKonosy');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="82"  field="Nom" modelname="apprenantKonosy" label="{{ucfirst(__('PkgApprenants::apprenantKonosy.Nom'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('apprenantKonosy-table-tbody')
            @foreach ($apprenantKonosies_data as $apprenantKonosy)
                @php
                    $isEditable = Auth::user()->can('edit-apprenantKonosy') && Auth::user()->can('update', $apprenantKonosy);
                @endphp
                <tr id="apprenantKonosy-row-{{$apprenantKonosy->id}}" data-id="{{$apprenantKonosy->id}}">
                    <x-checkbox-row :item="$apprenantKonosy" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$apprenantKonosy->id}}" data-field="Nom"  data-toggle="tooltip" title="{{ $apprenantKonosy->Nom }}" >
                    <x-field :entity="$apprenantKonosy" field="Nom">
                        {{ $apprenantKonosy->Nom }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-apprenantKonosy')
                        <x-action-button :entity="$apprenantKonosy" actionName="edit">
                        @can('update', $apprenantKonosy)
                            <a href="{{ route('apprenantKonosies.edit', ['apprenantKonosy' => $apprenantKonosy->id]) }}" data-id="{{$apprenantKonosy->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-apprenantKonosy')
                        <x-action-button :entity="$apprenantKonosy" actionName="show">
                        @can('view', $apprenantKonosy)
                            <a href="{{ route('apprenantKonosies.show', ['apprenantKonosy' => $apprenantKonosy->id]) }}" data-id="{{$apprenantKonosy->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$apprenantKonosy" actionName="delete">
                        @can('destroy-apprenantKonosy')
                        @can('delete', $apprenantKonosy)
                            <form class="context-state" action="{{ route('apprenantKonosies.destroy',['apprenantKonosy' => $apprenantKonosy->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$apprenantKonosy->id}}">
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
    @section('apprenantKonosy-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $apprenantKonosies_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>