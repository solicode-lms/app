@extends('layouts.admin')


@section('title', __('Core::dashboard.title'))

@section('content')

            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">{{__('Core::dashboard.title')}}</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">





                    <!-- Overview boxes -->
                    <div class="row">

                    <!-- Afficher chaque widget -->
                    @foreach ($widgets as $widget)
                        <!-- Inclure une vue spécifique au type de widget -->
                        @include('PkgWidgets::widget.types.' . $widget->widgetType->type, ['widget' => $widget])
                    @endforeach

                        <!-- Box 1 -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>150</h3>
                                    <p>{{__('Core::dashboard.articles')}}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <a href="posts.html" class="small-box-footer">Voir les détails <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>


                        <!-- Box 2 -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>53</h3>
                                    <p>{{__('Core::dashboard.categories')}}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-th"></i>
                                </div>
                                <a href="categories.html" class="small-box-footer">Voir les détails <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- Box 3 -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>44</h3>
                                    <p>{{__('Core::dashboard.commentaires')}}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <a href="comments.html" class="small-box-footer">Voir les détails <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- Box 4 -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>65</h3>
                                    <p>{{__('Core::dashboard.utilisateurs')}}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <a href="users.html" class="small-box-footer">Voir les détails <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Posts -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{__('Core::dashboard.Derniers_Articles')}}</h3>
                                    <div class="card-tools">
                                        <a href="posts.html" class="btn btn-tool">Voir tout</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">Titre de l'article 1 <span class="badge badge-primary">Nouvelle</span></li>
                                        <li class="list-group-item">Titre de l'article 2 <span class="badge badge-secondary">Terminé</span></li>
                                        <li class="list-group-item">Titre de l'article 3 <span class="badge badge-success">Publié</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Log -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{__('Core::dashboard.Journal_activité')}}</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">John Doe a ajouté un nouveau commentaire</li>
                                        <li class="list-group-item">Margarita a créé une nouvelle catégorie</li>
                                        <li class="list-group-item">James a publié un nouvel article</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->


@endsection
