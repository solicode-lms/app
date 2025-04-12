{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('filiere-table')
<div class="card-body table-responsive p-0 crud-card-body" id="filieres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="42.5"  field="code" modelname="filiere" label="{{ ucfirst(__('PkgFormation::filiere.code')) }}" />
                <x-sortable-column width="42.5"  field="nom" modelname="filiere" label="{{ ucfirst(__('PkgFormation::filiere.nom')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('filiere-table-tbody')
            @foreach ($filieres_data as $filiere)
                <tr id="filiere-row-{{$filiere->id}}">
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $filiere->code }}" >
                    <x-field :data="$filiere" field="code">
                        {{ $filiere->code }}
                    </x-field>
                    </td>
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $filiere->nom }}" >
                    <x-field :data="$filiere" field="nom">
                        {{ $filiere->nom }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-filiere')
                        @can('update', $filiere)
                            <a href="{{ route('filieres.edit', ['filiere' => $filiere->id]) }}" data-id="{{$filiere->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-filiere')
                        @can('view', $filiere)
                            <a href="{{ route('filieres.show', ['filiere' => $filiere->id]) }}" data-id="{{$filiere->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-filiere')
                        @can('delete', $filiere)
                            <form class="context-state" action="{{ route('filieres.destroy',['filiere' => $filiere->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$filiere->id}}">
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
    @section('filiere-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $filieres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>