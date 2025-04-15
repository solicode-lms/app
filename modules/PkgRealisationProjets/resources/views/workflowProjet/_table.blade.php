{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('workflowProjet-table')
<div class="card-body table-responsive p-0 crud-card-body" id="workflowProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <th style="width: 10px;">
                    <input type="checkbox" class="check-all-rows" />
                </th>
                <x-sortable-column width="27.333333333333332"  field="code" modelname="workflowProjet" label="{{ ucfirst(__('PkgRealisationProjets::workflowProjet.code')) }}" />
                <x-sortable-column width="27.333333333333332"  field="titre" modelname="workflowProjet" label="{{ ucfirst(__('PkgRealisationProjets::workflowProjet.titre')) }}" />
                <x-sortable-column width="27.333333333333332" field="sys_color_id" modelname="workflowProjet" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('workflowProjet-table-tbody')
            @foreach ($workflowProjets_data as $workflowProjet)
                <tr id="workflowProjet-row-{{$workflowProjet->id}}">
                    <td>
                        <input type="checkbox" class="check-row" value="{{ $workflowProjet->id }}" data-id="{{ $workflowProjet->id }}">
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowProjet->code }}" >
                    <x-field :entity="$workflowProjet" field="code">
                        {{ $workflowProjet->code }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowProjet->titre }}" >
                    <x-field :entity="$workflowProjet" field="titre">
                        {{ $workflowProjet->titre }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $workflowProjet->sysColor }}" >
                    <x-field :entity="$workflowProjet" field="sysColor">
                        <x-badge 
                        :text="$workflowProjet->sysColor->name ?? ''" 
                        :background="$workflowProjet->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-workflowProjet')
                        @can('update', $workflowProjet)
                            <a href="{{ route('workflowProjets.edit', ['workflowProjet' => $workflowProjet->id]) }}" data-id="{{$workflowProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-workflowProjet')
                        @can('view', $workflowProjet)
                            <a href="{{ route('workflowProjets.show', ['workflowProjet' => $workflowProjet->id]) }}" data-id="{{$workflowProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-workflowProjet')
                        @can('delete', $workflowProjet)
                            <form class="context-state" action="{{ route('workflowProjets.destroy',['workflowProjet' => $workflowProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$workflowProjet->id}}">
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
    @section('workflowProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $workflowProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>