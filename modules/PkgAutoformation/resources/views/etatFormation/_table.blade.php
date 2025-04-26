{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatFormation-table')
<div class="card-body p-0 crud-card-body" id="etatFormations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-etatFormation') || Auth::user()->can('destroy-etatFormation');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="nom" modelname="etatFormation" label="{{ucfirst(__('PkgAutoformation::etatFormation.nom'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="sys_color_id" modelname="etatFormation" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="formateur_id" modelname="etatFormation" label="{{ucfirst(__('PkgFormation::formateur.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatFormation-table-tbody')
            @foreach ($etatFormations_data as $etatFormation)
                @php
                    $isEditable = Auth::user()->can('edit-etatFormation') && Auth::user()->can('update', $etatFormation);
                @endphp
                <tr id="etatFormation-row-{{$etatFormation->id}}" data-id="{{$etatFormation->id}}">
                    <x-checkbox-row :item="$etatFormation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatFormation->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $etatFormation->nom }}" >
                    <x-field :entity="$etatFormation" field="nom">
                        {{ $etatFormation->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatFormation->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $etatFormation->sysColor }}" >
                    <x-field :entity="$etatFormation" field="sysColor">
                        <x-badge 
                        :text="$etatFormation->sysColor->name ?? ''" 
                        :background="$etatFormation->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatFormation->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $etatFormation->formateur }}" >
                    <x-field :entity="$etatFormation" field="formateur">
                       
                         {{  $etatFormation->formateur }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-etatFormation')
                        @can('update', $etatFormation)
                            <a href="{{ route('etatFormations.edit', ['etatFormation' => $etatFormation->id]) }}" data-id="{{$etatFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-etatFormation')
                        @can('view', $etatFormation)
                            <a href="{{ route('etatFormations.show', ['etatFormation' => $etatFormation->id]) }}" data-id="{{$etatFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-etatFormation')
                        @can('delete', $etatFormation)
                            <form class="context-state" action="{{ route('etatFormations.destroy',['etatFormation' => $etatFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$etatFormation->id}}">
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
    @section('etatFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>