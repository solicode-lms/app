<div class="content-header">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('success') }}.
        </div>
    @endif
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    {{ curd_index_title('PkgCompetences::filiere') }}
                </h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    @can('create-filiere')
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#filiereModal">
                            <i class="fas fa-plus"></i> {{ curd_index_add_label('PkgCompetences::filiere') }}
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
<section class="content" id="section_crud">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card" id="card_crud">
                    <div class="card-header col-md-12">
                        <div class="p-0">
                            <div class="input-group input-group-sm float-sm-right col-md-3 p-0">
                                <input type="text" name="crud_search_input" id="crud_search_input"
                                       class="form-control float-right" placeholder="Recherche">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="data-container">

                       

                        @include('PkgCompetences::filiere._table')
                        
                        <div class="d-md-flex justify-content-between align-items-center p-2">
                            <div class="d-flex align-items-center mb-2 ml-2 mt-2">
                                @can('import-filiere')
                                    <form action="{{ route('filieres.import') }}" method="post" class="mt-2" enctype="multipart/form-data"
                                        id="importForm">
                                        @csrf
                                        <label for="upload" class="btn btn-default btn-sm font-weight-normal">
                                            <i class="fas fa-file-download"></i>
                                            {{ __('Core::msg.import') }}
                                        </label>
                                        <input type="file" id="upload" name="file" style="display:none;" onchange="submitForm()" />
                                    </form>
                                @endcan
                                @can('export-filiere')
                                    <form class="">
                                        <a href="{{ route('filieres.export') }}" class="btn btn-default btn-sm mt-0 mx-2">
                                            <i class="fas fa-file-export"></i>
                                            {{ __('Core::msg.export') }}</a>
                                    </form>
                                @endcan
                            </div>
                        
                            <ul class="pagination m-0 float-right">
                                {{ $data->onEachSide(1)->links() }}
                            </ul>
                        </div>
                        
                        <script>
                            function submitForm() {
                                document.getElementById("importForm").submit();
                            }
                        </script>
                        





                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id='page' value="1">
</section>



<!-- Modal pour Ajouter/Modifier -->
<div class="modal fade" id="filiereModal" tabindex="-1" role="dialog" aria-labelledby="filiereModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        
        <div class="modal-content">
            @include('PkgCompetences::filiere._fields')
        </div>
    </div>
</div>