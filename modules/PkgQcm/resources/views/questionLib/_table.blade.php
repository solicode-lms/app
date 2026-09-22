{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('questionLib-table')
<div class="card-body p-0 crud-card-body" id="questionLibs-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $questionLibs_permissions['edit-questionLib'] || $questionLibs_permissions['destroy-questionLib'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="82" field="unite_apprentissage_id" modelname="questionLib" label="{!!ucfirst(__('PkgCompetences::uniteApprentissage.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('questionLib-table-tbody')
            @foreach ($questionLibs_data as $questionLib)
                @php
                    $isEditable = $questionLibs_permissions['edit-questionLib'] && $questionLibs_permissionsByItem['update'][$questionLib->id];
                @endphp
                <tr id="questionLib-row-{{$questionLib->id}}" data-id="{{$questionLib->id}}">
                    <x-checkbox-row :item="$questionLib" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$questionLib->id}}" data-field="unite_apprentissage_id">
                        {{  $questionLib->uniteApprentissage }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($questionLibs_permissions['edit-questionLib'])
                        <x-action-button :entity="$questionLib" actionName="edit">
                        @if($questionLibs_permissionsByItem['update'][$questionLib->id])
                            <a href="{{ route('questionLibs.edit', ['questionLib' => $questionLib->id]) }}" data-id="{{$questionLib->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($questionLibs_permissions['show-questionLib'])
                        <x-action-button :entity="$questionLib" actionName="show">
                        @if($questionLibs_permissionsByItem['view'][$questionLib->id])
                            <a href="{{ route('questionLibs.show', ['questionLib' => $questionLib->id]) }}" data-id="{{$questionLib->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$questionLib" actionName="delete">
                        @if($questionLibs_permissions['destroy-questionLib'])
                        @if($questionLibs_permissionsByItem['delete'][$questionLib->id])
                            <form class="context-state" action="{{ route('questionLibs.destroy',['questionLib' => $questionLib->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$questionLib->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                        @endif
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
    @section('questionLib-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $questionLibs_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>