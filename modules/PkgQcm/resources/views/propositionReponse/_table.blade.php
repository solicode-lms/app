{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('propositionReponse-table')
<div class="card-body p-0 crud-card-body" id="propositionReponses-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $propositionReponses_permissions['edit-propositionReponse'] || $propositionReponses_permissions['destroy-propositionReponse'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="propositionReponse" label="{!!ucfirst(__('PkgQcm::propositionReponse.ordre'))!!}" />
                <x-sortable-column :sortable="false" width="39"  field="libelle" modelname="propositionReponse" label="{!!ucfirst(__('PkgQcm::propositionReponse.libelle'))!!}" />
                <x-sortable-column :sortable="true" width="39"  field="is_correcte" modelname="propositionReponse" label="{!!ucfirst(__('PkgQcm::propositionReponse.is_correcte'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('propositionReponse-table-tbody')
            @foreach ($propositionReponses_data as $propositionReponse)
                @php
                    $isEditable = $propositionReponses_permissions['edit-propositionReponse'] && $propositionReponses_permissionsByItem['update'][$propositionReponse->id];
                @endphp
                <tr id="propositionReponse-row-{{$propositionReponse->id}}" data-id="{{$propositionReponse->id}}">
                    <x-checkbox-row :item="$propositionReponse" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class=" text-truncate" data-id="{{$propositionReponse->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $propositionReponse->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 39%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$propositionReponse->id}}" data-field="libelle">
                  
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($propositionReponse->libelle, 30) !!}
                   

                    </td>
                    <td style="max-width: 39%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$propositionReponse->id}}" data-field="is_correcte">
                        <span class="{{ $propositionReponse->is_correcte ? 'text-success' : 'text-danger' }}">
                            {{ $propositionReponse->is_correcte ? 'Oui' : 'Non' }}
                        </span>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($propositionReponses_permissions['edit-propositionReponse'])
                        <x-action-button :entity="$propositionReponse" actionName="edit">
                        @if($propositionReponses_permissionsByItem['update'][$propositionReponse->id])
                            <a href="{{ route('propositionReponses.edit', ['propositionReponse' => $propositionReponse->id]) }}" data-id="{{$propositionReponse->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($propositionReponses_permissions['show-propositionReponse'])
                        <x-action-button :entity="$propositionReponse" actionName="show">
                        @if($propositionReponses_permissionsByItem['view'][$propositionReponse->id])
                            <a href="{{ route('propositionReponses.show', ['propositionReponse' => $propositionReponse->id]) }}" data-id="{{$propositionReponse->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$propositionReponse" actionName="delete">
                        @if($propositionReponses_permissions['destroy-propositionReponse'])
                        @if($propositionReponses_permissionsByItem['delete'][$propositionReponse->id])
                            <form class="context-state" action="{{ route('propositionReponses.destroy',['propositionReponse' => $propositionReponse->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$propositionReponse->id}}">
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
    @section('propositionReponse-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $propositionReponses_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>