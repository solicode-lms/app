{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('module-table')
<div class="card-body p-0 crud-card-body" id="modules-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $modules_permissions['edit-module'] || $modules_permissions['destroy-module'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="16.4"  field="code" modelname="module" label="{!!ucfirst(__('PkgFormation::module.code'))!!}" />
                <x-sortable-column :sortable="true" width="16.4"  field="nom" modelname="module" label="{!!ucfirst(__('PkgFormation::module.nom'))!!}" />
                <x-sortable-column :sortable="true" width="16.4"  field="masse_horaire" modelname="module" label="{!!ucfirst(__('PkgFormation::module.masse_horaire'))!!}" />
                <x-sortable-column :sortable="true" width="16.4" field="filiere_id" modelname="module" label="{!!ucfirst(__('PkgFormation::filiere.singular'))!!}" />
                <x-sortable-column :sortable="true" width="16.4"  field="Competence" modelname="module" label="{!!ucfirst(__('PkgCompetences::competence.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('module-table-tbody')
            @foreach ($modules_data as $module)
                @php
                    $isEditable = $modules_permissions['edit-module'] && $modules_permissionsByItem['update'][$module->id];
                @endphp
                <tr id="module-row-{{$module->id}}" data-id="{{$module->id}}">
                    <x-checkbox-row :item="$module" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$module->id}}" data-field="code"  data-toggle="tooltip" title="{{ $module->code }}" >
                        {{ $module->code }}

                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$module->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $module->nom }}" >
                        {{ $module->nom }}

                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$module->id}}" data-field="masse_horaire"  data-toggle="tooltip" title="{{ $module->masse_horaire }}" >
                        {{ $module->masse_horaire }}

                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$module->id}}" data-field="filiere_id"  data-toggle="tooltip" title="{{ $module->filiere }}" >
                        {{  $module->filiere }}

                    </td>
                    <td style="max-width: 16.4%;" class=" text-truncate" data-id="{{$module->id}}" data-field="Competence"  data-toggle="tooltip" title="{{ $module->competences }}" >
                        <ul>
                            @foreach ($module->competences as $competence)
                                <li>{{$competence}} </li>
                            @endforeach
                        </ul>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($modules_permissions['edit-module'])
                        <x-action-button :entity="$module" actionName="edit">
                        @if($modules_permissionsByItem['update'][$module->id])
                            <a href="{{ route('modules.edit', ['module' => $module->id]) }}" data-id="{{$module->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($modules_permissions['show-module'])
                        <x-action-button :entity="$module" actionName="show">
                        @if($modules_permissionsByItem['view'][$module->id])
                            <a href="{{ route('modules.show', ['module' => $module->id]) }}" data-id="{{$module->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$module" actionName="delete">
                        @if($modules_permissions['destroy-module'])
                        @if($modules_permissionsByItem['delete'][$module->id])
                            <form class="context-state" action="{{ route('modules.destroy',['module' => $module->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$module->id}}">
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
    @section('module-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $modules_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>