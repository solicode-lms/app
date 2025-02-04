{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('content')
    <div class="content-header">
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </div>
                        <!-- Inclure le formulaire -->
                        @include('PkgGapp::eMetadataDefinition._fields')
                    </div>
                </div>
            </div>
        </div>
    </section>
@show
