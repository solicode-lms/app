{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatChapitre-table')
<div class="card-body p-0 crud-card-body" id="etatChapitres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-etatChapitre') || Auth::user()->can('destroy-etatChapitre');
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
                    $isEditable = Auth::user()->can('edit-etatChapitre') && Auth::user()->can('update', $etatChapitre);
                @endphp
                <tr id="etatChapitre-row-{{$etatChapitre->id}}" data-id="{{$etatChapitre->id}}">
                    <x-checkbox-row :item="$etatChapitre" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatChapitre->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $etatChapitre->nom }}" >
                    <x-field :entity="$etatChapitre" field="nom">
                        {{ $etatChapitre->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatChapitre->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $etatChapitre->sysColor }}" >
                    <x-field :entity="$etatChapitre" field="sysColor">
                        <x-badge 
                        :text="$etatChapitre->sysColor->name ?? ''" 
                        :background="$etatChapitre->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatChapitre->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $etatChapitre->formateur }}" >
                    <x-field :entity="$etatChapitre" field="formateur">
                       
                         {{  $etatChapitre->formateur }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-etatChapitre')
                        <x-action-button :entity="$etatChapitre" actionName="edit">
                        @can('update', $etatChapitre)
                            <a href="{{ route('etatChapitres.edit', ['etatChapitre' => $etatChapitre->id]) }}" data-id="{{$etatChapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-etatChapitre')
                        <x-action-button :entity="$etatChapitre" actionName="show">
                        @can('view', $etatChapitre)
                            <a href="{{ route('etatChapitres.show', ['etatChapitre' => $etatChapitre->id]) }}" data-id="{{$etatChapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$etatChapitre" actionName="delete">
                        @can('destroy-etatChapitre')
                        @can('delete', $etatChapitre)
                            <form class="context-state" action="{{ route('etatChapitres.destroy',['etatChapitre' => $etatChapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$etatChapitre->id}}">
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
    @section('etatChapitre-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatChapitres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>