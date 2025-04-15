{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatChapitre-table')
<div class="card-body table-responsive p-0 crud-card-body" id="etatChapitres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <th style="width: 10px;">
                    <input type="checkbox" class="check-all-rows" />
                </th>
                <x-sortable-column width="27.333333333333332"  field="nom" modelname="etatChapitre" label="{{ ucfirst(__('PkgAutoformation::etatChapitre.nom')) }}" />
                <x-sortable-column width="27.333333333333332" field="sys_color_id" modelname="etatChapitre" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <x-sortable-column width="27.333333333333332" field="formateur_id" modelname="etatChapitre" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatChapitre-table-tbody')
            @foreach ($etatChapitres_data as $etatChapitre)
                <tr id="etatChapitre-row-{{$etatChapitre->id}}">
                    <td>
                        <input type="checkbox" class="check-row" value="{{ $etatChapitre->id }}" data-id="{{ $etatChapitre->id }}">
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $etatChapitre->nom }}" >
                    <x-field :entity="$etatChapitre" field="nom">
                        {{ $etatChapitre->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $etatChapitre->sysColor }}" >
                    <x-field :entity="$etatChapitre" field="sysColor">
                        <x-badge 
                        :text="$etatChapitre->sysColor->name ?? ''" 
                        :background="$etatChapitre->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $etatChapitre->formateur }}" >
                    <x-field :entity="$etatChapitre" field="formateur">
                       
                         {{  $etatChapitre->formateur }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-etatChapitre')
                        @can('update', $etatChapitre)
                            <a href="{{ route('etatChapitres.edit', ['etatChapitre' => $etatChapitre->id]) }}" data-id="{{$etatChapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-etatChapitre')
                        @can('view', $etatChapitre)
                            <a href="{{ route('etatChapitres.show', ['etatChapitre' => $etatChapitre->id]) }}" data-id="{{$etatChapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
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