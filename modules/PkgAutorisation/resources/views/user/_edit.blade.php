{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'user',
        contextKey: 'user.edit_{{ $itemUser->id}}',
        cardTabSelector: '#card-tab-user', 
        formSelector: '#userForm',
        editUrl: '{{ route('users.edit',  ['user' => ':id']) }}',
        indexUrl: '{{ route('users.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutorisation::user.singular") }}',
    });
</script>
<script>
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-user" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-user-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-user"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="user-hasmany-tabs-home-tab" data-toggle="pill" href="#user-hasmany-tabs-home" role="tab" aria-controls="user-hasmany-tabs-home" aria-selected="true">{{__('PkgAutorisation::user.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="user-hasmany-tabs-apprenant-tab" data-toggle="pill" href="#user-hasmany-tabs-apprenant" role="tab" aria-controls="user-hasmany-tabs-apprenant" aria-selected="false">{{__('PkgApprenants::apprenant.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="user-hasmany-tabs-formateur-tab" data-toggle="pill" href="#user-hasmany-tabs-formateur" role="tab" aria-controls="user-hasmany-tabs-formateur" aria-selected="false">{{__('PkgFormation::formateur.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="user-hasmany-tabs-profile-tab" data-toggle="pill" href="#user-hasmany-tabs-profile" role="tab" aria-controls="user-hasmany-tabs-profile" aria-selected="false">{{__('PkgAutorisation::profile.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="user-hasmany-tabs-userModelFilter-tab" data-toggle="pill" href="#user-hasmany-tabs-userModelFilter" role="tab" aria-controls="user-hasmany-tabs-userModelFilter" aria-selected="false">{{__('Core::userModelFilter.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="user-hasmany-tabs-widgetUtilisateur-tab" data-toggle="pill" href="#user-hasmany-tabs-widgetUtilisateur" role="tab" aria-controls="user-hasmany-tabs-widgetUtilisateur" aria-selected="false">{{__('PkgWidgets::widgetUtilisateur.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-user-tabContent">
                            <div class="tab-pane fade show active" id="user-hasmany-tabs-home" role="tabpanel" aria-labelledby="user-hasmany-tabs-home-tab">
                                @include('PkgAutorisation::user._fields')
                            </div>

                            <div class="tab-pane fade" id="user-hasmany-tabs-apprenant" role="tabpanel" aria-labelledby="user-hasmany-tabs-apprenant-tab">
                                @include('PkgApprenants::apprenant._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'user.edit_' . $itemUser->id])
                            </div>
                            <div class="tab-pane fade" id="user-hasmany-tabs-formateur" role="tabpanel" aria-labelledby="user-hasmany-tabs-formateur-tab">
                                @include('PkgFormation::formateur._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'user.edit_' . $itemUser->id])
                            </div>
                            <div class="tab-pane fade" id="user-hasmany-tabs-profile" role="tabpanel" aria-labelledby="user-hasmany-tabs-profile-tab">
                                @include('PkgAutorisation::profile._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'user.edit_' . $itemUser->id])
                            </div>
                            <div class="tab-pane fade" id="user-hasmany-tabs-userModelFilter" role="tabpanel" aria-labelledby="user-hasmany-tabs-userModelFilter-tab">
                                @include('Core::userModelFilter._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'user.edit_' . $itemUser->id])
                            </div>
                            <div class="tab-pane fade" id="user-hasmany-tabs-widgetUtilisateur" role="tabpanel" aria-labelledby="user-hasmany-tabs-widgetUtilisateur-tab">
                                @include('PkgWidgets::widgetUtilisateur._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'user.edit_' . $itemUser->id])
                            </div>

                           
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                </div>
            </div>
        </div>
    </section>
@show
