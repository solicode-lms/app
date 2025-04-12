{{-- Ensuite, on hérite du tableau original --}}
@extends('PkgGestionTaches::realisationTache._table')



{{-- 🔁 Personnalisation des titres --}}
@section('realisationTache-table-th-livrables')
<th>📄 Livrables spécifiques</th>
@endsection

@section('realisationTache-table-th-realisationTache')
<th>🧩 Tâche à réaliser</th>
@endsection
