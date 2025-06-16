{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatChapitre-table')
<div class="card-body p-0 crud-card-body" id="etatChapitres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $etatChapitres_permissions['edit-etatChapitre'] || $devetatChapitres_permissions['destroy-etatChapitre'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="nom" modelname="etatChapitre" label="{{ucfirst(__('PkgAutoformation::etatChapitre.nom'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="sys_color_id" modelname="etatChapitre" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="formateur_id" modelname="etatChapitre" label="{{ucfirst(__('PkgFormation::formateur.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatChapitre-table-tbody')
            @foreach ($etatChapitres_data as $etatChapitre)
                @php
                    $isEditable = $etatChapitres_permissions['edit-etatChapitre'] && $etatChapitres_permissionsByItem['update'][$etatChapitre->id];
                @endphp
                <tr id="etatChapitre-row-{{$etatChapitre->id}}" data-id="{{$etatChapitre->id}}">
                    <x-checkbox-row :item="$etatChapitre" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatChapitre->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $etatChapitre->nom }}" >
                        {{ $etatChapitre->nom }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatChapitre->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $etatChapitre->sysColor }}" >
                        <x-badge 
                        :text="$etatChapitre->sysColor->name ?? ''" 
                        :background="$etatChapitre->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatChapitre->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $etatChapitre->formateur }}" >
                        {{  $etatChapitre->formateur }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($etatChapitres_permissions['edit-etatChapitre'])
                        <x-action-button :entity="$etatChapitre" actionName="edit">
                        @if($etatChapitres_permissionsByItem['update'][$etatChapitre->id])
                            <a href="{{ route('etatChapitres.edit', ['etatChapitre' => $etatChapitre->id]) }}" data-id="{{$etatChapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($etatChapitres_permissions['show-etatChapitre'])
                        <x-action-button :entity="$etatChapitre" actionName="show">
                        @if($etatChapitres_permissionsByItem['view'][$etatChapitre->id])
                            <a href="{{ route('etatChapitres.show', ['etatChapitre' => $etatChapitre->id]) }}" data-id="{{$etatChapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$etatChapitre" actionName="delete">
                        @if($etatChapitres_permissions['destroy-etatChapitre'])
                        @if($etatChapitres_permissionsByItem['delete'][$etatChapitre->id])
                            <form class="context-state" action="{{ route('etatChapitres.destroy',['etatChapitre' => $etatChapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$etatChapitre->id}}">
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
    @section('etatChapitre-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatChapitres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>