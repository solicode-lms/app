<div class="actions d-flex align-items-center justify-content-end">
    @can($createPermission ?? '')
        <a href="{{ $createRoute ?? '#' }}" data-target="#entityModal" class="btn btn-info btn-sm mr-2 context-state addEntityButton">
            <i class="fas fa-plus"></i>
            {{ $createText ?? __('Core::msg.add') }}
        </a>
    @endcan

    <div class="dropdown">
        <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-download"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
          
            @can($importPermission ?? '')
                <form class="dropdown-item" action="{{ $importRoute ?? '#' }}" method="post" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <label for="upload">
                        <i class="fas fa-file-download"></i>
                        {{ $importText ?? __('Core::msg.import') }}
                    </label>
                    <input type="file" id="upload" name="file" style="display:none;" onchange="submitForm()" />
                    
                </form>
                <script>
                    // TODO : Il faut génrer ce cod dnas CrudManager, et le généraliser
                    function submitForm() {
                        document.getElementById("importForm").submit();
                    }
                </script>
            @endcan

            @can($exportPermission ?? '')
                <form class="dropdown-item">
                    <a href="{{ $exportXlsxRoute ?? '#' }}">
                        <i class="fas fa-file-excel"></i>
                        {{ $exportText ?? __('Core::msg.export') }}
                    </a>
                </form>
                <form class="dropdown-item">
                    <a href="{{ $exportCsvRoute ?? '#' }}">
                        <i class="fas fa-file-csv"></i>
                        {{ $exportText ?? __('Core::msg.export') }}
                    </a>
                </form>
            @endcan
        </div>
    </div>
</div>
