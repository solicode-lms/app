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
                        @else
                         @include('PkgWidgets::widget.types.' . $widget->type->type, ['widget' => $widget])
                       @endif
                    @endforeach
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
@endsection
