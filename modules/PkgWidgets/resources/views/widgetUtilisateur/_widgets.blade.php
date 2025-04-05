
@section('widgetUtilisateur-widgets')

@section('widgetUtilisateur-table-tbody')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- Overview boxes -->
        <div class="row widgets_container">
        @foreach ($widgetUtilisateurs_data as $widgetUtilisateur)
            <!-- Inclure une vue spécifique au type de widget -->
            @if($widgetUtilisateur->widget->error)
                @include('PkgWidgets::widget.types.error', ['widget' => $widgetUtilisateur->widget])
            @elseif ($widgetUtilisateur->widget->type->type == "card")
             @include('PkgWidgets::widget.types.' . $widgetUtilisateur->widget->type->type, ['widget' => $widgetUtilisateur->widget])
           @endif
        @endforeach
        </div>

        <div class="row widgets_container">
            @foreach ($widgetUtilisateurs_data as $widgetUtilisateur)
                <!-- Inclure une vue spécifique au type de widget -->
                @if($widgetUtilisateur->widget->error)
                    @include('PkgWidgets::widget.types.error', ['widget' => $widgetUtilisateur->widget])
                @elseif ($widgetUtilisateur->widget->type->type != "card")
                 @include('PkgWidgets::widget.types.' . $widgetUtilisateur->widget->type->type, ['widget' => $widgetUtilisateur->widget])
               @endif
            @endforeach
        </div>

    </div><!-- /.container-fluid -->
</div>
@show
@show

<div class="card-footer">
    @section('widgetUtilisateur-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgetUtilisateurs_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>