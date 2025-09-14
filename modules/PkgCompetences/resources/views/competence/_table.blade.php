{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('competence-table')
<div class="card-body p-0 crud-card-body" id="competences-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $competences_permissions['edit-competence'] || $competences_permissions['destroy-competence'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="6"  field="code" modelname="competence" label="{!!ucfirst(__('PkgCompetences::competence.code'))!!}" />
                <x-sortable-column :sortable="true" width="10"  field="mini_code" modelname="competence" label="{!!ucfirst(__('PkgCompetences::competence.mini_code'))!!}" />
                <x-sortable-column :sortable="true" width="33"  field="nom" modelname="competence" label="{!!ucfirst(__('PkgCompetences::competence.nom'))!!}" />
                <x-sortable-column :sortable="true" width="33" field="module_id" modelname="competence" label="{!!ucfirst(__('PkgFormation::module.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('competence-table-tbody')
            @foreach ($competences_data as $competence)
                @php
                    $isEditable = $competences_permissions['edit-competence'] && $competences_permissionsByItem['update'][$competence->id];
                @endphp
                <tr id="competence-row-{{$competence->id}}" data-id="{{$competence->id}}">
                    <x-checkbox-row :item="$competence" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 6%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$competence->id}}" data-field="code">
                        {{ $competence->code }}

                    </td>
                    <td style="max-width: 10%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$competence->id}}" data-field="mini_code">
                        {{ $competence->mini_code }}

                    </td>
                    <td style="max-width: 33%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$competence->id}}" data-field="nom">
                        {{ $competence->nom }}

                    </td>
                    <td style="max-width: 33%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$competence->id}}" data-field="module_id">
                        {{  $competence->module }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($competences_permissions['edit-competence'])
                        <x-action-button :entity="$competence" actionName="edit">
                        @if($competences_permissionsByItem['update'][$competence->id])
                            <a href="{{ route('competences.edit', ['competence' => $competence->id]) }}" data-id="{{$competence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($competences_permissions['show-competence'])
                        <x-action-button :entity="$competence" actionName="show">
                        @if($competences_permissionsByItem['view'][$competence->id])
                            <a href="{{ route('competences.show', ['competence' => $competence->id]) }}" data-id="{{$competence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$competence" actionName="delete">
                        @if($competences_permissions['destroy-competence'])
                        @if($competences_permissionsByItem['delete'][$competence->id])
                            <form class="context-state" action="{{ route('competences.destroy',['competence' => $competence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$competence->id}}">
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
    @section('competence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $competences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>