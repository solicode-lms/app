{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('groupe-table')
<div class="card-body p-0 crud-card-body" id="groupes-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-groupe') || Auth::user()->can('destroy-groupe');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="code" modelname="groupe" label="{{ucfirst(__('PkgApprenants::groupe.code'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="filiere_id" modelname="groupe" label="{{ucfirst(__('PkgFormation::filiere.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="formateurs" modelname="groupe" label="{{ucfirst(__('PkgFormation::formateur.plural'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('groupe-table-tbody')
            @foreach ($groupes_data as $groupe)
                @php
                    $isEditable = Auth::user()->can('edit-groupe') && Auth::user()->can('update', $groupe);
                @endphp
                <tr id="groupe-row-{{$groupe->id}}" data-id="{{$groupe->id}}">
                    <x-checkbox-row :item="$groupe" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$groupe->id}}" data-field="code"  data-toggle="tooltip" title="{{ $groupe->code }}" >
                    <x-field :entity="$groupe" field="code">
                        {{ $groupe->code }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$groupe->id}}" data-field="filiere_id"  data-toggle="tooltip" title="{{ $groupe->filiere }}" >
                    <x-field :entity="$groupe" field="filiere">
                       
                         {{  $groupe->filiere }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$groupe->id}}" data-field="formateurs"  data-toggle="tooltip" title="{{ $groupe->formateurs }}" >
                    <x-field :entity="$groupe" field="formateurs">
                        <ul>
                            @foreach ($groupe->formateurs as $formateur)
                                <li @if(strlen($formateur) > 30) data-toggle="tooltip" title="{{$formateur}}"  @endif>@limit($formateur, 30)</li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-groupe')
                        <x-action-button :entity="$groupe" actionName="edit">
                        @can('update', $groupe)
                            <a href="{{ route('groupes.edit', ['groupe' => $groupe->id]) }}" data-id="{{$groupe->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @elsecan('show-groupe')
                        <x-action-button :entity="$groupe" actionName="show">
                        @can('view', $groupe)
                            <a href="{{ route('groupes.show', ['groupe' => $groupe->id]) }}" data-id="{{$groupe->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$groupe" actionName="delete">
                        @can('destroy-groupe')
                        @can('delete', $groupe)
                            <form class="context-state" action="{{ route('groupes.destroy',['groupe' => $groupe->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$groupe->id}}">
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
    @section('groupe-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $groupes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>