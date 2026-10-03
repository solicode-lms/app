<section id="{{ $id ?? 'crud-header' }}" class="content-header crud-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="mb-0">
                    <i class="{{ $icon ?? 'fas fa-folder' }} {{ $iconColor ?? 'text-info' }}"></i>
                    {!! $title ?? __('Titre par défaut') !!}
                    @if(!empty($scopeVariables))
                        <div class="d-inline-block ml-2" style="font-size: 0.6em; vertical-align: middle;">
                            @foreach($scopeVariables as $key => $value)
                                <span class="badge badge-light border font-weight-normal text-muted mr-1" title="Filtre actif">
                                    <i class="fas fa-filter text-info mr-1"></i>
                                    {{ $key }} : <span class="text-dark font-weight-bold">{{ $value }}</span>
                                </span>
                            @endforeach
                        </div>
                    @endif
                </h1>
            </div>
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right">
                    @foreach ($breadcrumbs as $breadcrumb)
                        @if ($loop->last)
                            <li class="breadcrumb-item active">{{ $breadcrumb['label'] }}</li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] ?? '#' }}">{{ $breadcrumb['label'] }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</section>