{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('groupe-table')
<div class="card-body p-0 crud-card-body" id="groupes-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $groupes_permissions['edit-groupe'] || $groupes_permissions['destroy-groupe'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="code" modelname="groupe" label="{!!ucfirst(__('PkgApprenants::groupe.code'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="filiere_id" modelname="groupe" label="{!!ucfirst(__('PkgFormation::filiere.singular'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="formateurs" modelname="groupe" label="{!!ucfirst(__('PkgFormation::formateur.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('groupe-table-tbody')
            @foreach ($groupes_data as $groupe)
                @php
                    $isEditable = $groupes_permissions['edit-groupe'] && $groupes_permissionsByItem['update'][$groupe->id];
                @endphp
                <tr id="groupe-row-{{$groupe->id}}" data-id="{{$groupe->id}}">
                    <x-checkbox-row :item="$groupe" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$groupe->id}}" data-field="code">
                        {{ $groupe->code }}

                    </td>
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$groupe->id}}" data-field="filiere_id">
                        {{  $groupe->filiere }}

                    </td>
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$groupe->id}}" data-field="formateurs">
                        <ul>
                            @foreach ($groupe->formateurs as $formateur)
                                <li @if(strlen($formateur) > 30) data-toggle="tooltip" title="{{$formateur}}"  @endif>@limit($formateur, 30)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($groupes_permissions['edit-groupe'])
                        <x-action-button :entity="$groupe" actionName="edit">
                        @if($groupes_permissionsByItem['update'][$groupe->id])
                            <a href="{{ route('groupes.edit', ['groupe' => $groupe->id]) }}" data-id="{{$groupe->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($groupes_permissions['show-groupe'])
                        <x-action-button :entity="$groupe" actionName="show">
                        @if($groupes_permissionsByItem['view'][$groupe->id])
                            <a href="{{ route('groupes.show', ['groupe' => $groupe->id]) }}" data-id="{{$groupe->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$groupe" actionName="delete">
                        @if($groupes_permissions['destroy-groupe'])
                        @if($groupes_permissionsByItem['delete'][$groupe->id])
                            <form class="context-state" action="{{ route('groupes.destroy',['groupe' => $groupe->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$groupe->id}}">
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
    @section('groupe-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $groupes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>