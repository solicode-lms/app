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
                        
                        @if($widget->error)
                            @include('PkgWidgets::widget.types.error', ['widget' => $widget])
                        @elseif($widget->type->type == 'card')
                         @include('PkgWidgets::widget.types.' . $widget->type->type, ['widget' => $widget])
                        @elseif($widget->type->type == 'table')
                        @include('PkgWidgets::widget.types.' . $widget->type->type, ['widget' => $widget])
                       @endif
                    @endforeach

              
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
