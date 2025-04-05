
@section('widgetUtilisateur-widgets')

@section('widgetUtilisateur-table-tbody')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- Overview boxes -->
        <div class="row widgets_mini_contrainer">
        @foreach ($widgetUtilisateurs_data as $widgetUtilisateur)
            <!-- Inclure une vue spécifique au type de widget -->
            @if($widgetUtilisateur->widget->error)
                @include('PkgWidgets::widget.types.error', ['widget' => $widgetUtilisateur->widget])
            @elseif ($widgetUtilisateur->widget->type->type == "card")
             @include('PkgWidgets::widget.types.' . $widgetUtilisateur->widget->type->type, ['widget' => $widgetUtilisateur->widget])
           @endif
        @endforeach
        </div>

        <div class="row widgets_contrainer">
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


<script>
$(function () {

  // Make the dashboard widgets sortable Using jquery UI
  $('.widgets_contrainer').sortable({
    placeholder: 'sort-highlight',
    handle: '.card-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex: 999999 ,
    tolerance: "pointer",
    update: function () {
        console.log("modification de widgets position");
    }
  })
  $('.widgets_contrainer .card-header').css('cursor', 'move')

    // Make the dashboard widgets sortable Using jquery UI
    $('.widgets_mini_contrainer').sortable({
    placeholder: 'sort-highlight',
    handle: '.icon',
    forcePlaceholderSize: true,
    zIndex: 999999 ,
    tolerance: "pointer",
    update: function () {
        console.log("modification de widgets position");
    }
  })
  $('.widgets_mini_contrainer .icon').css('cursor', 'move')

});
</script>