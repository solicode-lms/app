{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationTache-table')
<div class="card-body table-responsive p-0 crud-card-body" id="etatRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-etatRealisationTache') || Auth::user()->can('destroy-etatRealisationTache');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="20.5"  field="nom" modelname="etatRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.nom')) }}" />
                <x-sortable-column :sortable="true" width="20.5" field="workflow_tache_id" modelname="etatRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::workflowTache.singular')) }}" />
                <x-sortable-column :sortable="true" width="20.5" field="sys_color_id" modelname="etatRealisationTache" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <x-sortable-column :sortable="true" width="20.5" field="formateur_id" modelname="etatRealisationTache" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatRealisationTache-table-tbody')
            @foreach ($etatRealisationTaches_data as $etatRealisationTache)
                <tr id="etatRealisationTache-row-{{$etatRealisationTache->id}}" data-id="{{$etatRealisationTache->id}}">
                    <x-checkbox-row :item="$etatRealisationTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $etatRealisationTache->nom }}" >
                    <x-field :entity="$etatRealisationTache" field="nom">
                        {{ $etatRealisationTache->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $etatRealisationTache->workflowTache }}" >
                    <x-field :entity="$etatRealisationTache" field="workflowTache">
                       
                         {{  $etatRealisationTache->workflowTache }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $etatRealisationTache->sysColor }}" >
                    <x-field :entity="$etatRealisationTache" field="sysColor">
                        <x-badge 
                        :text="$etatRealisationTache->sysColor->name ?? ''" 
                        :background="$etatRealisationTache->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $etatRealisationTache->formateur }}" >
                    <x-field :entity="$etatRealisationTache" field="formateur">
                       
                         {{  $etatRealisationTache->formateur }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-etatRealisationTache')
                        @can('update', $etatRealisationTache)
                            <a href="{{ route('etatRealisationTaches.edit', ['etatRealisationTache' => $etatRealisationTache->id]) }}" data-id="{{$etatRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-etatRealisationTache')
                        @can('view', $etatRealisationTache)
                            <a href="{{ route('etatRealisationTaches.show', ['etatRealisationTache' => $etatRealisationTache->id]) }}" data-id="{{$etatRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-etatRealisationTache')
                        @can('delete', $etatRealisationTache)
                            <form class="context-state" action="{{ route('etatRealisationTaches.destroy',['etatRealisationTache' => $etatRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$etatRealisationTache->id}}">
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
    @section('etatRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>