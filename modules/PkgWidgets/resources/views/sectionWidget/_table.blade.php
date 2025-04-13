{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sectionWidget-table')
<div class="card-body table-responsive p-0 crud-card-body" id="sectionWidgets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="8"  field="ordre" modelname="sectionWidget" label="{{ ucfirst(__('PkgWidgets::sectionWidget.ordre')) }}" />
                <x-sortable-column width="10"  field="icone" modelname="sectionWidget" label="{{ ucfirst(__('PkgWidgets::sectionWidget.icone')) }}" />
                <x-sortable-column width="57"  field="titre" modelname="sectionWidget" label="{{ ucfirst(__('PkgWidgets::sectionWidget.titre')) }}" />
                <x-sortable-column width="10" field="sys_color_id" modelname="sectionWidget" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sectionWidget-table-tbody')
            @foreach ($sectionWidgets_data as $sectionWidget)
                <tr id="sectionWidget-row-{{$sectionWidget->id}}">
                    <td style="max-width: 8%;" class="text-truncate" data-toggle="tooltip" title="{{ $sectionWidget->ordre }}" >
                    <x-field :entity="$sectionWidget" field="ordre">
                        {{ $sectionWidget->ordre }}
                    </x-field>
                    </td>
                    <td style="max-width: 10%;" class="text-truncate" data-toggle="tooltip" title="{{ $sectionWidget->icone }}" >
                    <x-field :entity="$sectionWidget" field="icone">
                        <i class="{{ $sectionWidget->icone }}" ></i>
                    </x-field>
                    </td>
                    <td style="max-width: 57%;" class="text-truncate" data-toggle="tooltip" title="{{ $sectionWidget->titre }}" >
                    <x-field :entity="$sectionWidget" field="titre">
                        {{ $sectionWidget->titre }}
                    </x-field>
                    </td>
                    <td style="max-width: 10%;" class="text-truncate" data-toggle="tooltip" title="{{ $sectionWidget->sysColor }}" >
                    <x-field :entity="$sectionWidget" field="sysColor">
                        <x-badge 
                        :text="$sectionWidget->sysColor->name" 
                        :background="$sectionWidget->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-sectionWidget')
                        @can('update', $sectionWidget)
                            <a href="{{ route('sectionWidgets.edit', ['sectionWidget' => $sectionWidget->id]) }}" data-id="{{$sectionWidget->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-sectionWidget')
                        @can('view', $sectionWidget)
                            <a href="{{ route('sectionWidgets.show', ['sectionWidget' => $sectionWidget->id]) }}" data-id="{{$sectionWidget->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-sectionWidget')
                        @can('delete', $sectionWidget)
                            <form class="context-state" action="{{ route('sectionWidgets.destroy',['sectionWidget' => $sectionWidget->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sectionWidget->id}}">
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
    @section('sectionWidget-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sectionWidgets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>