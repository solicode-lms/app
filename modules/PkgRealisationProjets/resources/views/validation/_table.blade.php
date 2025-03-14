{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('validation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="validations-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="transfert_competence_id" modelname="validation" label="{{ ucfirst(__('PkgCreationProjet::transfertCompetence.singular')) }}" />
                <x-sortable-column field="note" modelname="validation" label="{{ ucfirst(__('PkgRealisationProjets::validation.note')) }}" />
                <x-sortable-column field="message" modelname="validation" label="{{ ucfirst(__('PkgRealisationProjets::validation.message')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('validation-table-tbody')
            @foreach ($validations_data as $validation)
                <tr id="validation-row-{{$validation->id}}">
                    <td>
                     <span @if(strlen($validation->transfertCompetence) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $validation->transfertCompetence }}" 
                        @endif>
                        {{ Str::limit($validation->transfertCompetence, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($validation->note) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $validation->note }}" 
                        @endif>
                        {{ Str::limit($validation->note, 40) }}
                    </span>
                    </td>
                    <td>{!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($validation->message, 50) !!}</td>
                    <td class="text-right">

                        @can('show-validation')
                        @can('view', $validation)
                            <a href="{{ route('validations.show', ['validation' => $validation->id]) }}" data-id="{{$validation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-validation')
                        @can('update', $validation)
                            <a href="{{ route('validations.edit', ['validation' => $validation->id]) }}" data-id="{{$validation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-validation')
                        @can('delete', $validation)
                            <form class="context-state" action="{{ route('validations.destroy',['validation' => $validation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$validation->id}}">
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
    @section('validation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $validations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>