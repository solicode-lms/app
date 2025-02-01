<script>
  
</script>
<div style="position: absolute;color: red" >
    {{ $id }}  {{ now()->format('Y-m-d H:i:s') }} 
</div>
<div class="modal fade  crud-modal" id="{{ $id }}"  role="dialog" aria-labelledby="{{ $id }}Label" >
    <div class="modal-dialog full-screen-modal  modal-md" role="document">
        <div class="modal-content">

            <div id="modal-loading"  class="d-flex justify-content-center align-items-center" style="display: none; min-height: 200px;  ">
                <div class="spinner-border text-primary" role="status">
                </div>
            </div>

            <!-- Contenu injecté -->
            <div id="modal-content-container" style="display: none;">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">modal</div>
            </div>
        </div>
    </div>
</div>