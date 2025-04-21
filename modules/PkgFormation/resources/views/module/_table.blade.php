{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('module-table')
<div class="card-body table-responsive p-0 crud-card-body" id="modules-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-module') || Auth::user()->can('destroy-module');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="16.4"  field="code" modelname="module" label="{{ucfirst(__('PkgFormation::module.code'))}}" />
                <x-sortable-column :sortable="true" width="16.4"  field="nom" modelname="module" label="{{ucfirst(__('PkgFormation::module.nom'))}}" />
                <x-sortable-column :sortable="true" width="16.4"  field="masse_horaire" modelname="module" label="{{ucfirst(__('PkgFormation::module.masse_horaire'))}}" />
                <x-sortable-column :sortable="true" width="16.4" field="filiere_id" modelname="module" label="{{ucfirst(__('PkgFormation::filiere.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.4"  field="Competence" modelname="module" label="{{ucfirst(__('PkgCompetences::competence.plural'))}}" />

                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('module-table-tbody')
            @foreach ($modules_data as $module)
                @php
                    $isEditable = Auth::user()->can('edit-module') && Auth::user()->can('update', $module);
                @endphp
                <tr id="module-row-{{$module->id}}" data-id="{{$module->id}}">
                    <x-checkbox-row :item="$module" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$module->id}}" data-field="code"  data-toggle="tooltip" title="{{ $module->code }}" >
                    <x-field :entity="$module" field="code">
                        {{ $module->code }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$module->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $module->nom }}" >
                    <x-field :entity="$module" field="nom">
                        {{ $module->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$module->id}}" data-field="masse_horaire"  data-toggle="tooltip" title="{{ $module->masse_horaire }}" >
                    <x-field :entity="$module" field="masse_horaire">
                        {{ $module->masse_horaire }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$module->id}}" data-field="filiere_id"  data-toggle="tooltip" title="{{ $module->filiere }}" >
                    <x-field :entity="$module" field="filiere">
                       
                         {{  $module->filiere }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$module->id}}" data-field="Competence"  data-toggle="tooltip" title="{{ $module->competences }}" >
                    <x-field :entity="$module" field="competences">
                        <ul>
                            @foreach ($module->competences as $competence)
                                <li>{{$competence}} </li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-module')
                        @can('update', $module)
                            <a href="{{ route('modules.edit', ['module' => $module->id]) }}" data-id="{{$module->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-module')
                        @can('view', $module)
                            <a href="{{ route('modules.show', ['module' => $module->id]) }}" data-id="{{$module->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-module')
                        @can('delete', $module)
                            <form class="context-state" action="{{ route('modules.destroy',['module' => $module->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$module->id}}">
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
    @section('module-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $modules_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>