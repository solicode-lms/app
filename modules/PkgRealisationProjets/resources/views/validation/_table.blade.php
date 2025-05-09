{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('validation-table')
<div class="card-body p-0 crud-card-body" id="validations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-validation') || Auth::user()->can('destroy-validation');
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
                    $isEditable = Auth::user()->can('edit-validation') && Auth::user()->can('update', $validation);
                @endphp
                <tr id="validation-row-{{$validation->id}}" data-id="{{$validation->id}}">
                    <x-checkbox-row :item="$validation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$validation->id}}" data-field="transfert_competence_id"  data-toggle="tooltip" title="{{ $validation->transfertCompetence }}" >
                    <x-field :entity="$validation" field="transfertCompetence">
                       
                         {{  $validation->transfertCompetence }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$validation->id}}" data-field="note"  data-toggle="tooltip" title="{{ $validation->note }}" >
                    <x-field :entity="$validation" field="note">
                        {{ $validation->note }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$validation->id}}" data-field="message"  data-toggle="tooltip" title="{{ $validation->message }}" >
                    <x-field :entity="$validation" field="message">
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($validation->message, 30) !!}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-validation')
                        <x-action-button :entity="$validation" actionName="edit">
                        @can('update', $validation)
                            <a href="{{ route('validations.edit', ['validation' => $validation->id]) }}" data-id="{{$validation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-validation')
                        <x-action-button :entity="$validation" actionName="show">
                        @can('view', $validation)
                            <a href="{{ route('validations.show', ['validation' => $validation->id]) }}" data-id="{{$validation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$validation" actionName="delete">
                        @can('destroy-validation')
                        @can('delete', $validation)
                            <form class="context-state" action="{{ route('validations.destroy',['validation' => $validation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$validation->id}}">
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
    @section('validation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $validations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>