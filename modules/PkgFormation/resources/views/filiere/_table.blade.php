{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('filiere-table')
<div class="card-body p-0 crud-card-body" id="filieres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-filiere') || Auth::user()->can('destroy-filiere');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="41"  field="code" modelname="filiere" label="{{ucfirst(__('PkgFormation::filiere.code'))}}" />
                <x-sortable-column :sortable="true" width="41"  field="nom" modelname="filiere" label="{{ucfirst(__('PkgFormation::filiere.nom'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('filiere-table-tbody')
            @foreach ($filieres_data as $filiere)
                @php
                    $isEditable = Auth::user()->can('edit-filiere') && Auth::user()->can('update', $filiere);
                @endphp
                <tr id="filiere-row-{{$filiere->id}}" data-id="{{$filiere->id}}">
                    <x-checkbox-row :item="$filiere" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$filiere->id}}" data-field="code"  data-toggle="tooltip" title="{{ $filiere->code }}" >
                    <x-field :entity="$filiere" field="code">
                        {{ $filiere->code }}
                    </x-field>
                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$filiere->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $filiere->nom }}" >
                    <x-field :entity="$filiere" field="nom">
                        {{ $filiere->nom }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-filiere')
                        <x-action-button :entity="$filiere" actionName="edit">
                        @can('update', $filiere)
                            <a href="{{ route('filieres.edit', ['filiere' => $filiere->id]) }}" data-id="{{$filiere->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-filiere')
                        <x-action-button :entity="$filiere" actionName="show">
                        @can('view', $filiere)
                            <a href="{{ route('filieres.show', ['filiere' => $filiere->id]) }}" data-id="{{$filiere->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$filiere" actionName="delete">
                        @can('destroy-filiere')
                        @can('delete', $filiere)
                            <form class="context-state" action="{{ route('filieres.destroy',['filiere' => $filiere->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$filiere->id}}">
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
    @section('filiere-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $filieres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>