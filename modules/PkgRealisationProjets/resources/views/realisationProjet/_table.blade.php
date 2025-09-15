{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationProjet-table')
<div class="card-body p-0 crud-card-body" id="realisationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $realisationProjets_permissions['edit-realisationProjet'] || $realisationProjets_permissions['destroy-realisationProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="affectation_projet_id" modelname="realisationProjet" label="{!!ucfirst(__('PkgRealisationProjets::affectationProjet.singular'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="etats_realisation_projet_id" modelname="realisationProjet" label="{!!ucfirst(__('PkgRealisationProjets::realisationProjet.etats_realisation_projet_id'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="LivrablesRealisation" modelname="realisationProjet" label="{!!ucfirst(__('PkgRealisationProjets::realisationProjet.livrables'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationProjet-table-tbody')
            @foreach ($realisationProjets_data as $realisationProjet)
                @php
                    $isEditable = $realisationProjets_permissions['edit-realisationProjet'] && $realisationProjets_permissionsByItem['update'][$realisationProjet->id];
                @endphp
                <tr id="realisationProjet-row-{{$realisationProjet->id}}" data-id="{{$realisationProjet->id}}">
                    <x-checkbox-row :item="$realisationProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class=" text-truncate" data-id="{{$realisationProjet->id}}" data-field="affectation_projet_id" >
                        @include('PkgRealisationProjets::realisationProjet.custom.fields.affectationProjet', ['entity' => $realisationProjet])
                    </td>
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationProjet->id}}" data-field="etats_realisation_projet_id" >
                        @include('PkgRealisationProjets::realisationProjet.custom.fields.etatsRealisationProjet', ['entity' => $realisationProjet])
                    </td>
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class=" text-truncate" data-id="{{$realisationProjet->id}}" data-field="LivrablesRealisation" >
                        @include('PkgRealisationProjets::realisationProjet.custom.fields.livrablesRealisations', ['entity' => $realisationProjet])
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($realisationProjets_permissions['edit-realisationProjet'])
                        <x-action-button :entity="$realisationProjet" actionName="edit">
                        @if($realisationProjets_permissionsByItem['update'][$realisationProjet->id])
                            <a href="{{ route('realisationProjets.edit', ['realisationProjet' => $realisationProjet->id]) }}" data-id="{{$realisationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($realisationProjets_permissions['show-realisationProjet'])
                        <x-action-button :entity="$realisationProjet" actionName="show">
                        @if($realisationProjets_permissionsByItem['view'][$realisationProjet->id])
                            <a href="{{ route('realisationProjets.show', ['realisationProjet' => $realisationProjet->id]) }}" data-id="{{$realisationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$realisationProjet" actionName="delete">
                        @if($realisationProjets_permissions['destroy-realisationProjet'])
                        @if($realisationProjets_permissionsByItem['delete'][$realisationProjet->id])
                            <form class="context-state" action="{{ route('realisationProjets.destroy',['realisationProjet' => $realisationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationProjet->id}}">
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
    @section('realisationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>