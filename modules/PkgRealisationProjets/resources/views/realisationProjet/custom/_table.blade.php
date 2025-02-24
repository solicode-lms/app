@extends('PkgRealisationProjets::realisationProjet._table')


@section('realisationProjet-table-tbody')

@foreach ($realisationProjets_data as $realisationProjet)
<tr id="realisationProjet-row-{{$realisationProjet->id}}">
    <td>@limit($realisationProjet->affectationProjet, 50)</td>
    <td>@limit($realisationProjet->apprenant, 50)</td>
    <td>
       
        @if ($realisationProjet->etatsRealisationProjet?->titre == "Terminé")
        <span class="badge badge-primary">@limit($realisationProjet->etatsRealisationProjet, 10)</span>
        @else
        <span class="badge badge-success">@limit($realisationProjet->etatsRealisationProjet, 10)</span>
        @endif
    </td>
    <td>
        <ul>
            @foreach ($realisationProjet->livrablesRealisations as $livrablesRealisation)
                <li>
                    <a href="{{$livrablesRealisation->lien}}" target="_blank">@limit($livrablesRealisation->livrable->titre, 20)<a>
                </li>
            @endforeach
        </ul>
    </td>
    <td>
        <ul>
            @foreach ($realisationProjet->validations as $validation)
                <li>{{ $validation }}</li>
            @endforeach
        </ul>
    </td>
    <td class="text-right">

        @can('show-realisationProjet')
        @can('view', $realisationProjet)
            <a href="{{ route('realisationProjets.show', ['realisationProjet' => $realisationProjet->id]) }}" data-id="{{$realisationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                <i class="far fa-eye"></i>
            </a>
        @endcan
        @endcan
        @can('edit-realisationProjet')
        @can('update', $realisationProjet)
            <a href="{{ route('realisationProjets.edit', ['realisationProjet' => $realisationProjet->id]) }}" data-id="{{$realisationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                <i class="fas fa-pen-square"></i>
            </a>
        @endcan
        @endcan
        @can('destroy-realisationProjet')
        @can('delete', $realisationProjet)
            <form class="context-state" action="{{ route('realisationProjets.destroy',['realisationProjet' => $realisationProjet->id]) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$realisationProjet->id}}">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        @endcan
        @endcan
    </td>
</tr>
@endforeach


@endsection