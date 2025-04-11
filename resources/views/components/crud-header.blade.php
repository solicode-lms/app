<section id="{{ $id ?? 'crud-header' }}" class="content-header crud-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="mb-0">
                    <i class="{{ $icon ?? 'fas fa-folder' }} {{ $iconColor ?? 'text-info' }}"></i>
                    {!! $title ?? __('Titre par défaut') !!}
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