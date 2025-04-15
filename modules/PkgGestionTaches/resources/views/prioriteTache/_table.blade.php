{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('prioriteTache-table')
<div class="card-body table-responsive p-0 crud-card-body" id="prioriteTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <th style="width: 10px;">
                    <input type="checkbox" class="check-all-rows" />
                </th>
                <x-sortable-column width="41"  field="nom" modelname="prioriteTache" label="{{ ucfirst(__('PkgGestionTaches::prioriteTache.nom')) }}" />
                <x-sortable-column width="41" field="formateur_id" modelname="prioriteTache" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('prioriteTache-table-tbody')
            @foreach ($prioriteTaches_data as $prioriteTache)
                <tr id="prioriteTache-row-{{$prioriteTache->id}}">
                    <td>
                        <input type="checkbox" class="check-row" value="{{ $prioriteTache->id }}" data-id="{{ $prioriteTache->id }}">
                    </td>
                    <td style="max-width: 41%;" class="text-truncate" data-toggle="tooltip" title="{{ $prioriteTache->nom }}" >
                    <x-field :entity="$prioriteTache" field="nom">
                        {{ $prioriteTache->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 41%;" class="text-truncate" data-toggle="tooltip" title="{{ $prioriteTache->formateur }}" >
                    <x-field :entity="$prioriteTache" field="formateur">
                       
                         {{  $prioriteTache->formateur }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-prioriteTache')
                        @can('update', $prioriteTache)
                            <a href="{{ route('prioriteTaches.edit', ['prioriteTache' => $prioriteTache->id]) }}" data-id="{{$prioriteTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-prioriteTache')
                        @can('view', $prioriteTache)
                            <a href="{{ route('prioriteTaches.show', ['prioriteTache' => $prioriteTache->id]) }}" data-id="{{$prioriteTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-prioriteTache')
                        @can('delete', $prioriteTache)
                            <form class="context-state" action="{{ route('prioriteTaches.destroy',['prioriteTache' => $prioriteTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$prioriteTache->id}}">
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
    @section('prioriteTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $prioriteTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>