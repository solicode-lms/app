{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrablesRealisation-table')
<div class="card-body p-0 crud-card-body" id="livrablesRealisations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $livrablesRealisations_permissions['edit-livrablesRealisation'] || $livrablesRealisations_permissions['destroy-livrablesRealisation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="livrable_id" modelname="livrablesRealisation" label="{{ucfirst(__('PkgCreationProjet::livrable.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="lien" modelname="livrablesRealisation" label="{{ucfirst(__('PkgRealisationProjets::livrablesRealisation.lien'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="titre" modelname="livrablesRealisation" label="{{ucfirst(__('PkgRealisationProjets::livrablesRealisation.titre'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('livrablesRealisation-table-tbody')
            @foreach ($livrablesRealisations_data as $livrablesRealisation)
                @php
                    $isEditable = $livrablesRealisations_permissions['edit-livrablesRealisation'] && $livrablesRealisations_permissionsByItem['update'][$livrablesRealisation->id];
                @endphp
                <tr id="livrablesRealisation-row-{{$livrablesRealisation->id}}" data-id="{{$livrablesRealisation->id}}">
                    <x-checkbox-row :item="$livrablesRealisation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$livrablesRealisation->id}}" data-field="livrable_id"  data-toggle="tooltip" title="{{ $livrablesRealisation->livrable }}" >
                        {{  $livrablesRealisation->livrable }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$livrablesRealisation->id}}" data-field="lien"  data-toggle="tooltip" title="{{ $livrablesRealisation->lien }}" >
                        {{ $livrablesRealisation->lien }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$livrablesRealisation->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $livrablesRealisation->titre }}" >
                        {{ $livrablesRealisation->titre }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($livrablesRealisations_permissions['edit-livrablesRealisation'])
                        <x-action-button :entity="$livrablesRealisation" actionName="edit">
                        @if($livrablesRealisations_permissionsByItem['update'][$livrablesRealisation->id])
                            <a href="{{ route('livrablesRealisations.edit', ['livrablesRealisation' => $livrablesRealisation->id]) }}" data-id="{{$livrablesRealisation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($livrablesRealisations_permissions['show-livrablesRealisation'])
                        <x-action-button :entity="$livrablesRealisation" actionName="show">
                        @if($livrablesRealisations_permissionsByItem['view'][$livrablesRealisation->id])
                            <a href="{{ route('livrablesRealisations.show', ['livrablesRealisation' => $livrablesRealisation->id]) }}" data-id="{{$livrablesRealisation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$livrablesRealisation" actionName="delete">
                        @if($livrablesRealisations_permissions['destroy-livrablesRealisation'])
                        @if($livrablesRealisations_permissionsByItem['delete'][$livrablesRealisation->id])
                            <form class="context-state" action="{{ route('livrablesRealisations.destroy',['livrablesRealisation' => $livrablesRealisation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$livrablesRealisation->id}}">
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
    @section('livrablesRealisation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $livrablesRealisations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>