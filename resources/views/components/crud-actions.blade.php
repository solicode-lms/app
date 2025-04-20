<div class="actions d-flex align-items-center justify-content-end crud-action">
    @can($createPermission ?? '')
    @can('create', $instanceItem)
        <a href="{{ $createRoute ?? '#' }}" data-target="#entityModal" data-toggle="tooltip" title=" {{ $createText ?? __('Core::msg.add') }}" class="btn btn-sm btn-outline-info mr-2 context-state addEntityButton">
            <i class="fas fa-plus"></i>
        </a>
    @endcan
    @endcan


    

    @canany([$importPermission,$exportPermission])
    <div class="dropdown mr-2">
        <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-download"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
           
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
            @can($importPermission ?? '')
            <form class="dropdown-item" action="{{ $importRoute ?? '#' }}" method="post" enctype="multipart/form-data" id="importForm">
                @csrf
                <label for="upload">
                    <i class="fas fa-file-upload"></i>
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
        </div>
    </div>
    @endcan



    @php
    $hasAlternativeViews = collect($viewTypes)->pluck('type')->contains(function ($type) {
        return $type !== 'table';
    });
@endphp
@if ($hasAlternativeViews)
    <div class="dropdown mr-2">
        <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-eye"></i> Vue
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
            @foreach ($viewTypes as $type)
                <button class="dropdown-item view-switch-option {{ $viewType === $type['type'] ? 'active' : '' }}"
                        data-view-type="{{ $type['type'] }}">
                    <i class="{{ $type['icon'] }} mr-2"></i> {{ $type['label'] }}
                </button>
            @endforeach
        </div>
    </div>
@endif

    
    <button id="toggle-filter" class="btn btn-sm btn-outline-info ml-2" title="filtrer" data-toggle="tooltip" data-visible="1">
        <i class="fas fa-filter"></i> 
    </button>


</div>
