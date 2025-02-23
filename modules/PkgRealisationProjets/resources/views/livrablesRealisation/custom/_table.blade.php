{{-- //TODO : gapp : ajouter l'affichage de cmps partype : type Lien --}}

@extends('PkgRealisationProjets::livrablesRealisation._table')



@section('livrablesRealisation-table-tbody')
            @foreach ($livrablesRealisations_data as $livrablesRealisation)
                <tr id="livrablesRealisation-row-{{$livrablesRealisation->id}}">
                    <td> @limit($livrablesRealisation->livrable, 50) </td>
                    <td> 
                        <a href="{{$livrablesRealisation->lien}}" target="_blank">@limit($livrablesRealisation->lien, 50)<a>
                    </td>
                    <td>@limit($livrablesRealisation->titre, 50)</td>
                    <td class="text-right">

                        @can('show-livrablesRealisation')
                        @can('view', $livrablesRealisation)
                            <a href="{{ route('livrablesRealisations.show', ['livrablesRealisation' => $livrablesRealisation->id]) }}" data-id="{{$livrablesRealisation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-livrablesRealisation')
                        @can('update', $livrablesRealisation)
                            <a href="{{ route('livrablesRealisations.edit', ['livrablesRealisation' => $livrablesRealisation->id]) }}" data-id="{{$livrablesRealisation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-livrablesRealisation')
                        @can('delete', $livrablesRealisation)
                            <form class="context-state" action="{{ route('livrablesRealisations.destroy',['livrablesRealisation' => $livrablesRealisation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$livrablesRealisation->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                    </td>
                </tr>
            @endforeach
@endsection