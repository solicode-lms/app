{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('specialite-table')
<div class="card-body p-0 crud-card-body" id="specialites-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $specialites_permissions['edit-specialite'] || $specialites_permissions['destroy-specialite'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41"  field="nom" modelname="specialite" label="{!!ucfirst(__('PkgFormation::specialite.nom'))!!}" />
                <x-sortable-column :sortable="true" width="41"  field="formateurs" modelname="specialite" label="{!!ucfirst(__('PkgFormation::formateur.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('specialite-table-tbody')
            @foreach ($specialites_data as $specialite)
                @php
                    $isEditable = $specialites_permissions['edit-specialite'] && $specialites_permissionsByItem['update'][$specialite->id];
                @endphp
                <tr id="specialite-row-{{$specialite->id}}" data-id="{{$specialite->id}}">
                    <x-checkbox-row :item="$specialite" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$specialite->id}}" data-field="nom">
                        {{ $specialite->nom }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$specialite->id}}" data-field="formateurs">
                        <ul>
                            @foreach ($specialite->formateurs as $formateur)
                                <li @if(strlen($formateur) > 30) data-toggle="tooltip" title="{{$formateur}}"  @endif>@limit($formateur, 30)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($specialites_permissions['edit-specialite'])
                        <x-action-button :entity="$specialite" actionName="edit">
                        @if($specialites_permissionsByItem['update'][$specialite->id])
                            <a href="{{ route('specialites.edit', ['specialite' => $specialite->id]) }}" data-id="{{$specialite->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($specialites_permissions['show-specialite'])
                        <x-action-button :entity="$specialite" actionName="show">
                        @if($specialites_permissionsByItem['view'][$specialite->id])
                            <a href="{{ route('specialites.show', ['specialite' => $specialite->id]) }}" data-id="{{$specialite->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$specialite" actionName="delete">
                        @if($specialites_permissions['destroy-specialite'])
                        @if($specialites_permissionsByItem['delete'][$specialite->id])
                            <form class="context-state" action="{{ route('specialites.destroy',['specialite' => $specialite->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$specialite->id}}">
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
    @section('specialite-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $specialites_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>