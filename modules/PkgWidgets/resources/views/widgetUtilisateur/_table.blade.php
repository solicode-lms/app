{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetUtilisateur-table')
<div class="card-body table-responsive p-0 crud-card-body" id="widgetUtilisateurs-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-widgetUtilisateur') || Auth::user()->can('destroy-widgetUtilisateur');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column width="16.4"  field="ordre" modelname="widgetUtilisateur" label="{{ ucfirst(__('PkgWidgets::widgetUtilisateur.ordre')) }}" />
                <x-sortable-column width="16.4" field="widget_id" modelname="widgetUtilisateur" label="{{ ucfirst(__('PkgWidgets::widget.singular')) }}" />
                <x-sortable-column width="16.4"  field="package" modelname="widgetUtilisateur" label="{{ ucfirst(__('PkgWidgets::widgetUtilisateur.package')) }}" />
                <x-sortable-column width="16.4"  field="type" modelname="widgetUtilisateur" label="{{ ucfirst(__('PkgWidgets::widgetUtilisateur.type')) }}" />
                <x-sortable-column width="16.4"  field="visible" modelname="widgetUtilisateur" label="{{ ucfirst(__('PkgWidgets::widgetUtilisateur.visible')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widgetUtilisateur-table-tbody')
            @foreach ($widgetUtilisateurs_data as $widgetUtilisateur)
                <tr id="widgetUtilisateur-row-{{$widgetUtilisateur->id}}">
                    <x-checkbox-row :item="$widgetUtilisateur" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 16.4%;" class="text-truncate" data-toggle="tooltip" title="{{ $widgetUtilisateur->ordre }}" >
                    <x-field :entity="$widgetUtilisateur" field="ordre">
                        {{ $widgetUtilisateur->ordre }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="text-truncate" data-toggle="tooltip" title="{{ $widgetUtilisateur->widget }}" >
                    <x-field :entity="$widgetUtilisateur" field="widget">
                       
                         {{  $widgetUtilisateur->widget }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="text-truncate" data-toggle="tooltip" title="{{ $widgetUtilisateur->package }}" >
                    <x-field :entity="$widgetUtilisateur" field="package">
                        {{ $widgetUtilisateur->package }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="text-truncate" data-toggle="tooltip" title="{{ $widgetUtilisateur->type }}" >
                    <x-field :entity="$widgetUtilisateur" field="type">
                        {{ $widgetUtilisateur->type }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="text-truncate" data-toggle="tooltip" title="{{ $widgetUtilisateur->visible }}" >
                    <x-field :entity="$widgetUtilisateur" field="visible">
                        <span class="{{ $widgetUtilisateur->visible ? 'text-success' : 'text-danger' }}">
                            {{ $widgetUtilisateur->visible ? 'Oui' : 'Non' }}
                        </span>
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-widgetUtilisateur')
                        @can('update', $widgetUtilisateur)
                            <a href="{{ route('widgetUtilisateurs.edit', ['widgetUtilisateur' => $widgetUtilisateur->id]) }}" data-id="{{$widgetUtilisateur->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-widgetUtilisateur')
                        @can('view', $widgetUtilisateur)
                            <a href="{{ route('widgetUtilisateurs.show', ['widgetUtilisateur' => $widgetUtilisateur->id]) }}" data-id="{{$widgetUtilisateur->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-widgetUtilisateur')
                        @can('delete', $widgetUtilisateur)
                            <form class="context-state" action="{{ route('widgetUtilisateurs.destroy',['widgetUtilisateur' => $widgetUtilisateur->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$widgetUtilisateur->id}}">
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
    @section('widgetUtilisateur-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgetUtilisateurs_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>