{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('anneeFormation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="anneeFormations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="28.333333333333332"  field="titre" modelname="anneeFormation" label="{{ ucfirst(__('PkgFormation::anneeFormation.titre')) }}" />
                <x-sortable-column width="28.333333333333332"  field="date_debut" modelname="anneeFormation" label="{{ ucfirst(__('PkgFormation::anneeFormation.date_debut')) }}" />
                <x-sortable-column width="28.333333333333332"  field="date_fin" modelname="anneeFormation" label="{{ ucfirst(__('PkgFormation::anneeFormation.date_fin')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('anneeFormation-table-tbody')
            @foreach ($anneeFormations_data as $anneeFormation)
                <tr id="anneeFormation-row-{{$anneeFormation->id}}">
                    <td style="max-width: 28.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $anneeFormation->titre }}" >
                    <x-field :data="$anneeFormation" field="titre">
                        {{ $anneeFormation->titre }}
                    </x-field>
                    </td>
                    <td style="max-width: 28.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $anneeFormation->date_debut }}" >
                    <x-field :data="$anneeFormation" field="date_debut">
                        {{ $anneeFormation->date_debut }}
                    </x-field>
                    </td>
                    <td style="max-width: 28.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $anneeFormation->date_fin }}" >
                    <x-field :data="$anneeFormation" field="date_fin">
                        {{ $anneeFormation->date_fin }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-anneeFormation')
                        @can('update', $anneeFormation)
                            <a href="{{ route('anneeFormations.edit', ['anneeFormation' => $anneeFormation->id]) }}" data-id="{{$anneeFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-anneeFormation')
                        @can('view', $anneeFormation)
                            <a href="{{ route('anneeFormations.show', ['anneeFormation' => $anneeFormation->id]) }}" data-id="{{$anneeFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-anneeFormation')
                        @can('delete', $anneeFormation)
                            <form class="context-state" action="{{ route('anneeFormations.destroy',['anneeFormation' => $anneeFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$anneeFormation->id}}">
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
    @section('anneeFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $anneeFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>