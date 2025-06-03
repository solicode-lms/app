{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatEvaluationProjet-table')
<div class="card-body p-0 crud-card-body" id="etatEvaluationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-etatEvaluationProjet') || Auth::user()->can('destroy-etatEvaluationProjet');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="etatEvaluationProjet" label="{{ucfirst(__('PkgValidationProjets::etatEvaluationProjet.ordre'))}}" />
                <x-sortable-column :sortable="true" width="26"  field="code" modelname="etatEvaluationProjet" label="{{ucfirst(__('PkgValidationProjets::etatEvaluationProjet.code'))}}" />
                <x-sortable-column :sortable="true" width="26"  field="titre" modelname="etatEvaluationProjet" label="{{ucfirst(__('PkgValidationProjets::etatEvaluationProjet.titre'))}}" />
                <x-sortable-column :sortable="true" width="26" field="sys_color_id" modelname="etatEvaluationProjet" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatEvaluationProjet-table-tbody')
            @foreach ($etatEvaluationProjets_data as $etatEvaluationProjet)
                @php
                    $isEditable = Auth::user()->can('edit-etatEvaluationProjet') && Auth::user()->can('update', $etatEvaluationProjet);
                @endphp
                <tr id="etatEvaluationProjet-row-{{$etatEvaluationProjet->id}}" data-id="{{$etatEvaluationProjet->id}}">
                    <x-checkbox-row :item="$etatEvaluationProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatEvaluationProjet->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $etatEvaluationProjet->ordre }}" >
                    <x-field :entity="$etatEvaluationProjet" field="ordre">
                         <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $etatEvaluationProjet->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>
                    </x-field>
                    </td>
                    <td style="max-width: 26%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatEvaluationProjet->id}}" data-field="code"  data-toggle="tooltip" title="{{ $etatEvaluationProjet->code }}" >
                    <x-field :entity="$etatEvaluationProjet" field="code">
                        {{ $etatEvaluationProjet->code }}
                    </x-field>
                    </td>
                    <td style="max-width: 26%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatEvaluationProjet->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $etatEvaluationProjet->titre }}" >
                    <x-field :entity="$etatEvaluationProjet" field="titre">
                        {{ $etatEvaluationProjet->titre }}
                    </x-field>
                    </td>
                    <td style="max-width: 26%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatEvaluationProjet->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $etatEvaluationProjet->sysColor }}" >
                    <x-field :entity="$etatEvaluationProjet" field="sysColor">
                        <x-badge 
                        :text="$etatEvaluationProjet->sysColor->name ?? ''" 
                        :background="$etatEvaluationProjet->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-etatEvaluationProjet')
                        <x-action-button :entity="$etatEvaluationProjet" actionName="edit">
                        @can('update', $etatEvaluationProjet)
                            <a href="{{ route('etatEvaluationProjets.edit', ['etatEvaluationProjet' => $etatEvaluationProjet->id]) }}" data-id="{{$etatEvaluationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-etatEvaluationProjet')
                        <x-action-button :entity="$etatEvaluationProjet" actionName="show">
                        @can('view', $etatEvaluationProjet)
                            <a href="{{ route('etatEvaluationProjets.show', ['etatEvaluationProjet' => $etatEvaluationProjet->id]) }}" data-id="{{$etatEvaluationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$etatEvaluationProjet" actionName="delete">
                        @can('destroy-etatEvaluationProjet')
                        @can('delete', $etatEvaluationProjet)
                            <form class="context-state" action="{{ route('etatEvaluationProjets.destroy',['etatEvaluationProjet' => $etatEvaluationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$etatEvaluationProjet->id}}">
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
    @section('etatEvaluationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatEvaluationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>