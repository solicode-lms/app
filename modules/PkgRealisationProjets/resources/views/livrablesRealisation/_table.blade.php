{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrablesRealisation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="livrablesRealisations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-livrablesRealisation') || Auth::user()->can('destroy-livrablesRealisation');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="27.333333333333332" field="livrable_id" modelname="livrablesRealisation" label="{{ ucfirst(__('PkgCreationProjet::livrable.singular')) }}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="lien" modelname="livrablesRealisation" label="{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.lien')) }}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="titre" modelname="livrablesRealisation" label="{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.titre')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('livrablesRealisation-table-tbody')
            @foreach ($livrablesRealisations_data as $livrablesRealisation)
                <tr id="livrablesRealisation-row-{{$livrablesRealisation->id}}" data-id="{{$livrablesRealisation->id}}">
                    <x-checkbox-row :item="$livrablesRealisation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $livrablesRealisation->livrable }}" >
                    <x-field :entity="$livrablesRealisation" field="livrable">
                       
                         {{  $livrablesRealisation->livrable }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $livrablesRealisation->lien }}" >
                    <x-field :entity="$livrablesRealisation" field="lien">
                        {{ $livrablesRealisation->lien }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $livrablesRealisation->titre }}" >
                    <x-field :entity="$livrablesRealisation" field="titre">
                        {{ $livrablesRealisation->titre }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-livrablesRealisation')
                        @can('update', $livrablesRealisation)
                            <a href="{{ route('livrablesRealisations.edit', ['livrablesRealisation' => $livrablesRealisation->id]) }}" data-id="{{$livrablesRealisation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-livrablesRealisation')
                        @can('view', $livrablesRealisation)
                            <a href="{{ route('livrablesRealisations.show', ['livrablesRealisation' => $livrablesRealisation->id]) }}" data-id="{{$livrablesRealisation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-livrablesRealisation')
                        @can('delete', $livrablesRealisation)
                            <form class="context-state" action="{{ route('livrablesRealisations.destroy',['livrablesRealisation' => $livrablesRealisation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$livrablesRealisation->id}}">
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
    @section('livrablesRealisation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $livrablesRealisations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>