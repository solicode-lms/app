{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('anneeFormation-table')
<div class="card-body p-0 crud-card-body" id="anneeFormations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $anneeFormations_permissions['edit-anneeFormation'] || $anneeFormations_permissions['destroy-anneeFormation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="titre" modelname="anneeFormation" label="{!!ucfirst(__('PkgFormation::anneeFormation.titre'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="date_debut" modelname="anneeFormation" label="{!!ucfirst(__('PkgFormation::anneeFormation.date_debut'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="date_fin" modelname="anneeFormation" label="{!!ucfirst(__('PkgFormation::anneeFormation.date_fin'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('anneeFormation-table-tbody')
            @foreach ($anneeFormations_data as $anneeFormation)
                @php
                    $isEditable = $anneeFormations_permissions['edit-anneeFormation'] && $anneeFormations_permissionsByItem['update'][$anneeFormation->id];
                @endphp
                <tr id="anneeFormation-row-{{$anneeFormation->id}}" data-id="{{$anneeFormation->id}}">
                    <x-checkbox-row :item="$anneeFormation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$anneeFormation->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $anneeFormation->titre }}" >
                        {{ $anneeFormation->titre }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$anneeFormation->id}}" data-field="date_debut"  data-toggle="tooltip" title="{{ $anneeFormation->date_debut }}" >
                        <x-deadline-display :value="$anneeFormation->date_debut" />
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$anneeFormation->id}}" data-field="date_fin"  data-toggle="tooltip" title="{{ $anneeFormation->date_fin }}" >
                        <x-deadline-display :value="$anneeFormation->date_fin" />
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($anneeFormations_permissions['edit-anneeFormation'])
                        <x-action-button :entity="$anneeFormation" actionName="edit">
                        @if($anneeFormations_permissionsByItem['update'][$anneeFormation->id])
                            <a href="{{ route('anneeFormations.edit', ['anneeFormation' => $anneeFormation->id]) }}" data-id="{{$anneeFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($anneeFormations_permissions['show-anneeFormation'])
                        <x-action-button :entity="$anneeFormation" actionName="show">
                        @if($anneeFormations_permissionsByItem['view'][$anneeFormation->id])
                            <a href="{{ route('anneeFormations.show', ['anneeFormation' => $anneeFormation->id]) }}" data-id="{{$anneeFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$anneeFormation" actionName="delete">
                        @if($anneeFormations_permissions['destroy-anneeFormation'])
                        @if($anneeFormations_permissionsByItem['delete'][$anneeFormation->id])
                            <form class="context-state" action="{{ route('anneeFormations.destroy',['anneeFormation' => $anneeFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$anneeFormation->id}}">
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
    @section('anneeFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $anneeFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>