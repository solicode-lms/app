{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('validation-table')
<div class="card-body p-0 crud-card-body" id="validations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $validations_permissions['edit-validation'] || $validations_permissions['destroy-validation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="transfert_competence_id" modelname="validation" label="{{ucfirst(__('PkgCreationProjet::transfertCompetence.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="note" modelname="validation" label="{{ucfirst(__('PkgRealisationProjets::validation.note'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="message" modelname="validation" label="{{ucfirst(__('PkgRealisationProjets::validation.message'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('validation-table-tbody')
            @foreach ($validations_data as $validation)
                @php
                    $isEditable = $validations_permissions['edit-validation'] && $validations_permissionsByItem['update'][$validation->id];
                @endphp
                <tr id="validation-row-{{$validation->id}}" data-id="{{$validation->id}}">
                    <x-checkbox-row :item="$validation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$validation->id}}" data-field="transfert_competence_id"  data-toggle="tooltip" title="{{ $validation->transfertCompetence }}" >
                        {{  $validation->transfertCompetence }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$validation->id}}" data-field="note"  data-toggle="tooltip" title="{{ $validation->note }}" >
                        {{ $validation->note }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$validation->id}}" data-field="message"  data-toggle="tooltip" title="{{ $validation->message }}" >
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$validation->id}}" data-field="message"  data-toggle="tooltip" title="{{ $validation->message }}" >
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($validation->message, 30) !!}
                    </td>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($validations_permissions['edit-validation'])
                        <x-action-button :entity="$validation" actionName="edit">
                        @if($validations_permissionsByItem['update'][$validation->id])
                            <a href="{{ route('validations.edit', ['validation' => $validation->id]) }}" data-id="{{$validation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($validations_permissions['show-validation'])
                        <x-action-button :entity="$validation" actionName="show">
                        @if($validations_permissionsByItem['view'][$validation->id])
                            <a href="{{ route('validations.show', ['validation' => $validation->id]) }}" data-id="{{$validation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$validation" actionName="delete">
                        @if($validations_permissions['destroy-validation'])
                        @if($validations_permissionsByItem['delete'][$validation->id])
                            <form class="context-state" action="{{ route('validations.destroy',['validation' => $validation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$validation->id}}">
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
    @section('validation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $validations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>