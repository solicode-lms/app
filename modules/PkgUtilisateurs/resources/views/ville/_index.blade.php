
@section('script')
@parent
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'ville',
        crudSelector: '#ville_crud',
        indexUrl: '{{ route('villes.index') }}',
        createUrl: '{{ route('villes.create') }}',
        editUrl: '{{ route('villes.edit',  ['ville' => ':id']) }}',
        showUrl: '{{ route('villes.show',  ['ville' => ':id']) }}',
        storeUrl: '{{ route('villes.store') }}',
        deleteUrl: '{{ route('villes.destroy',  ['ville' => ':id']) }}',
        csrfToken: '{{ csrf_token() }}',
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::ville.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::ville.singular") }}',
    });
</script>
@endsection

<div id="ville_crud">
    <!-- En-tête -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ curd_index_title('PkgUtilisateurs::ville') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                        <li class="breadcrumb-item active">Villes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Section principale -->
    <section class="content">
        <div class="container-fluid">
            <!-- Barre de recherche et filtres -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter_region">Filtrer par région</label>
                        <select class="form-control" id="filter_region">
                            <option value="">Toutes les régions</option>
                            <option value="region1">Région 1</option>
                            <option value="region2">Région 2</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter_status">Filtrer par statut</label>
                        <select class="form-control" id="filter_status">
                            <option value="">Tous les statuts</option>
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Rechercher..." id="search_input">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button"><i class="fas fa-search"></i> Rechercher</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte contenant les données -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Liste des villes</h3>
                            <div class="card-tools">
                                @can('create-ville')
                                <a href="{{ route('villes.create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Ajouter
                                </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Région</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Inclure ici les lignes dynamiques -->
                                    @include('PkgUtilisateurs::ville._table')
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modale pour ajouter/modifier -->
    <div class="modal fade" id="villeModal" tabindex="-1" role="dialog" aria-labelledby="villeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div id="modal-loading" class="d-flex justify-content-center align-items-center" style="min-height: 200px; display: none;">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <div id="modal-content-container" style="display: none;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="villeModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
    </div>
</div>
