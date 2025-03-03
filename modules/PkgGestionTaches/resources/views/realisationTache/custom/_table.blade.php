{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('PkgGestionTaches::realisationTache._table')


@section('realisationTache-table-tbody')
@foreach ($realisationTaches_data as $realisationTache)
    <tr id="realisationTache-row-{{$realisationTache->id}}">
        <td>@limit($realisationTache->tache, 50)</td>
        <td>@limit($realisationTache->realisationProjet, 50)</td>
        <td>
            
            @php
            $etat = $realisationTache->etatRealisationTache?->nom ?? '';
            $couleurs = [
                "En cours" => "badge-success",
                "Terminé" => "badge-primary",
                "Annulé" => "badge-secondary",
                "En validation" => "badge-warning",
                "En pause" => "badge-danger",
                "En présentation" => "badge-info"
            ];
            $classeBadge = $couleurs[$etat] ?? "badge-secondary";
            @endphp
            <span class="badge {{ $classeBadge }}">@limit($realisationTache->etatRealisationTache, 50)</span>
        </td>
        <td class="text-right">

            @can('show-realisationTache')
            @can('view', $realisationTache)
                <a href="{{ route('realisationTaches.show', ['realisationTache' => $realisationTache->id]) }}" data-id="{{$realisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                    <i class="far fa-eye"></i>
                </a>
            @endcan
            @endcan
            @can('edit-realisationTache')
            @can('update', $realisationTache)
                <a href="{{ route('realisationTaches.edit', ['realisationTache' => $realisationTache->id]) }}" data-id="{{$realisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                    <i class="fas fa-pen-square"></i>
                </a>
            @endcan
            @endcan
            @can('destroy-realisationTache')
            @can('delete', $realisationTache)
                <form class="context-state" action="{{ route('realisationTaches.destroy',['realisationTache' => $realisationTache->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$realisationTache->id}}">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            @endcan
            @endcan
        </td>
    </tr>
@endforeach
@endsection