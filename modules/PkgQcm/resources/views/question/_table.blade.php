{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('question-table')
<div class="card-body p-0 crud-card-body" id="questions-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $questions_permissions['edit-question'] || $questions_permissions['destroy-question'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="question" label="{!!ucfirst(__('PkgQcm::question.ordre'))!!}" />
                <x-sortable-column :sortable="false" width="26"  field="enonce" modelname="question" label="{!!ucfirst(__('PkgQcm::question.enonce'))!!}" />
                <x-sortable-column :sortable="false" width="26"  field="PropositionReponse" modelname="question" label="{!!ucfirst(__('PkgQcm::propositionReponse.plural'))!!}" />
                <x-sortable-column :sortable="true" width="26" field="unite_apprentissage_id" modelname="question" label="{!!ucfirst(__('PkgCompetences::uniteApprentissage.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('question-table-tbody')
            @foreach ($questions_data as $question)
                @php
                    $isEditable = $questions_permissions['edit-question'] && $questions_permissionsByItem['update'][$question->id];
                @endphp
                <tr id="question-row-{{$question->id}}" data-id="{{$question->id}}">
                    <x-checkbox-row :item="$question" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class=" text-truncate" data-id="{{$question->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $question->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$question->id}}" data-field="enonce">
                  
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($question->enonce, 30) !!}
                   

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class=" text-truncate" data-id="{{$question->id}}" data-field="PropositionReponse">
                        <ul>
                            @foreach ($question->propositionReponses as $propositionReponse)
                                <li>{{$propositionReponse}} </li>
                            @endforeach
                        </ul>

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$question->id}}" data-field="unite_apprentissage_id">
                        {{  $question->uniteApprentissage }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($questions_permissions['edit-question'])
                        <x-action-button :entity="$question" actionName="edit">
                        @if($questions_permissionsByItem['update'][$question->id])
                            <a href="{{ route('questions.edit', ['question' => $question->id]) }}" data-id="{{$question->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($questions_permissions['show-question'])
                        <x-action-button :entity="$question" actionName="show">
                        @if($questions_permissionsByItem['view'][$question->id])
                            <a href="{{ route('questions.show', ['question' => $question->id]) }}" data-id="{{$question->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$question" actionName="delete">
                        @if($questions_permissions['destroy-question'])
                        @if($questions_permissionsByItem['delete'][$question->id])
                            <form class="context-state" action="{{ route('questions.destroy',['question' => $question->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$question->id}}">
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
    @section('question-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $questions_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>