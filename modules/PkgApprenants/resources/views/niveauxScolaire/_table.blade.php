{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauxScolaire-table')
<div class="card-body p-0 crud-card-body" id="niveauxScolaires-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-niveauxScolaire') || Auth::user()->can('destroy-niveauxScolaire');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="82"  field="code" modelname="niveauxScolaire" label="{{ucfirst(__('PkgApprenants::niveauxScolaire.code'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('niveauxScolaire-table-tbody')
            @foreach ($niveauxScolaires_data as $niveauxScolaire)
                @php
                    $isEditable = Auth::user()->can('edit-niveauxScolaire') && Auth::user()->can('update', $niveauxScolaire);
                @endphp
                <tr id="niveauxScolaire-row-{{$niveauxScolaire->id}}" data-id="{{$niveauxScolaire->id}}">
                    <x-checkbox-row :item="$niveauxScolaire" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$niveauxScolaire->id}}" data-field="code"  data-toggle="tooltip" title="{{ $niveauxScolaire->code }}" >
                    <x-field :entity="$niveauxScolaire" field="code">
                        {{ $niveauxScolaire->code }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-niveauxScolaire')
                        <x-action-button :entity="$niveauxScolaire" actionName="edit">
                        @can('update', $niveauxScolaire)
                            <a href="{{ route('niveauxScolaires.edit', ['niveauxScolaire' => $niveauxScolaire->id]) }}" data-id="{{$niveauxScolaire->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @elsecan('show-niveauxScolaire')
                        <x-action-button :entity="$niveauxScolaire" actionName="show">
                        @can('view', $niveauxScolaire)
                            <a href="{{ route('niveauxScolaires.show', ['niveauxScolaire' => $niveauxScolaire->id]) }}" data-id="{{$niveauxScolaire->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$niveauxScolaire" actionName="delete">
                        @can('destroy-niveauxScolaire')
                        @can('delete', $niveauxScolaire)
                            <form class="context-state" action="{{ route('niveauxScolaires.destroy',['niveauxScolaire' => $niveauxScolaire->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$niveauxScolaire->id}}">
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
    @section('niveauxScolaire-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $niveauxScolaires_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>