{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('specialite-table')
<div class="card-body p-0 crud-card-body" id="specialites-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-specialite') || Auth::user()->can('destroy-specialite');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="41"  field="nom" modelname="specialite" label="{{ucfirst(__('PkgFormation::specialite.nom'))}}" />
                <x-sortable-column :sortable="true" width="41"  field="formateurs" modelname="specialite" label="{{ucfirst(__('PkgFormation::formateur.plural'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('specialite-table-tbody')
            @foreach ($specialites_data as $specialite)
                @php
                    $isEditable = Auth::user()->can('edit-specialite') && Auth::user()->can('update', $specialite);
                @endphp
                <tr id="specialite-row-{{$specialite->id}}" data-id="{{$specialite->id}}">
                    <x-checkbox-row :item="$specialite" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$specialite->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $specialite->nom }}" >
                    <x-field :entity="$specialite" field="nom">
                        {{ $specialite->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$specialite->id}}" data-field="formateurs"  data-toggle="tooltip" title="{{ $specialite->formateurs }}" >
                    <x-field :entity="$specialite" field="formateurs">
                        <ul>
                            @foreach ($specialite->formateurs as $formateur)
                                <li @if(strlen($formateur) > 30) data-toggle="tooltip" title="{{$formateur}}"  @endif>@limit($formateur, 30)</li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-specialite')
                        <x-action-button :entity="$specialite" actionName="edit">
                        @can('update', $specialite)
                            <a href="{{ route('specialites.edit', ['specialite' => $specialite->id]) }}" data-id="{{$specialite->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-specialite')
                        <x-action-button :entity="$specialite" actionName="show">
                        @can('view', $specialite)
                            <a href="{{ route('specialites.show', ['specialite' => $specialite->id]) }}" data-id="{{$specialite->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$specialite" actionName="delete">
                        @can('destroy-specialite')
                        @can('delete', $specialite)
                            <form class="context-state" action="{{ route('specialites.destroy',['specialite' => $specialite->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$specialite->id}}">
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
    @section('specialite-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $specialites_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>