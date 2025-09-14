{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('microCompetence-table')
<div class="card-body p-0 crud-card-body" id="microCompetences-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $microCompetences_permissions['edit-microCompetence'] || $microCompetences_permissions['destroy-microCompetence'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="microCompetence" label="{!!ucfirst(__('PkgCompetences::microCompetence.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="6"  field="code" modelname="microCompetence" label="{!!ucfirst(__('PkgCompetences::microCompetence.code'))!!}" />
                <x-sortable-column :sortable="true" width="27.5"  field="titre" modelname="microCompetence" label="{!!ucfirst(__('PkgCompetences::microCompetence.titre'))!!}" />
                <x-sortable-column :sortable="true" width="11" field="competence_id" modelname="microCompetence" label="{!!ucfirst(__('PkgCompetences::competence.singular'))!!}" />
                <x-sortable-column :sortable="true" width="6"  field="lien" modelname="microCompetence" label="{!!ucfirst(__('PkgCompetences::microCompetence.lien'))!!}" />
                <x-sortable-column :sortable="false" width="27.5"  field="UniteApprentissage" modelname="microCompetence" label="{!!ucfirst(__('PkgCompetences::uniteApprentissage.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('microCompetence-table-tbody')
            @foreach ($microCompetences_data as $microCompetence)
                @php
                    $isEditable = $microCompetences_permissions['edit-microCompetence'] && $microCompetences_permissionsByItem['update'][$microCompetence->id];
                @endphp
                <tr id="microCompetence-row-{{$microCompetence->id}}" data-id="{{$microCompetence->id}}">
                    <x-checkbox-row :item="$microCompetence" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$microCompetence->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $microCompetence->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 6%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$microCompetence->id}}" data-field="code">
                        {{ $microCompetence->code }}

                    </td>
                    <td style="max-width: 27.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$microCompetence->id}}" data-field="titre">
                        {{ $microCompetence->titre }}

                    </td>
                    <td style="max-width: 11%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$microCompetence->id}}" data-field="competence_id">
                        {{  $microCompetence->competence }}

                    </td>
                    <td style="max-width: 6%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$microCompetence->id}}" data-field="lien">
     @if($microCompetence->lien)
    <a href="{{ $microCompetence->lien }}" target="_blank">
        <i class="fas fa-link"></i>
    </a>
    @else
    —
    @endif


                    </td>
                    <td style="max-width: 27.5%;white-space: normal;" class=" text-truncate" data-id="{{$microCompetence->id}}" data-field="UniteApprentissage">
                        <ul>
                            @foreach ($microCompetence->uniteApprentissages as $uniteApprentissage)
                                <li>{{$uniteApprentissage}} </li>
                            @endforeach
                        </ul>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">
                        @if($microCompetences_permissions['startFormation-microCompetence'])
                        <x-action-button :entity="$microCompetence" actionName="startFormation">
                            <a 
                            data-toggle="tooltip" 
                            title="Suivre la formation" 
                            href="{{ route('microCompetences.startFormation', ['id' => $microCompetence->id]) }}" 
                            data-id="{{$microCompetence->id}}" 
                            data-url="{{ route('microCompetences.startFormation', ['id' => $microCompetence->id]) }}" 
                            data-action-type="confirm"
                            class="btn btn-default btn-sm context-state actionEntity">
                                <i class="fas fa-graduation-cap"></i>
                            </a>
                        </x-action-button>
                        @endif
                        

                       

                        @if($microCompetences_permissions['edit-microCompetence'])
                        <x-action-button :entity="$microCompetence" actionName="edit">
                        @if($microCompetences_permissionsByItem['update'][$microCompetence->id])
                            <a href="{{ route('microCompetences.edit', ['microCompetence' => $microCompetence->id]) }}" data-id="{{$microCompetence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($microCompetences_permissions['show-microCompetence'])
                        <x-action-button :entity="$microCompetence" actionName="show">
                        @if($microCompetences_permissionsByItem['view'][$microCompetence->id])
                            <a href="{{ route('microCompetences.show', ['microCompetence' => $microCompetence->id]) }}" data-id="{{$microCompetence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$microCompetence" actionName="delete">
                        @if($microCompetences_permissions['destroy-microCompetence'])
                        @if($microCompetences_permissionsByItem['delete'][$microCompetence->id])
                            <form class="context-state" action="{{ route('microCompetences.destroy',['microCompetence' => $microCompetence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$microCompetence->id}}">
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
    @section('microCompetence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $microCompetences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>