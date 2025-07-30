@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\File;

    $dynamicMenus = [];

    // Récupérer la liste des modules actifs, triés par la colonne 'order'
    $modules = DB::table('sys_modules')
        ->orderBy('order', 'asc')
        ->pluck('slug'); // 'slug' doit correspondre au nom du dossier

    foreach ($modules as $moduleName) {
        $menuPath = base_path("modules/$moduleName/resources/views/layouts/_sidebar.blade.php");

        if (File::exists($menuPath)) {
            $viewPath = $moduleName . '::layouts._sidebar';
            $dynamicMenus[] = $viewPath;
        }
    }
@endphp

<li class="nav-item">
    <a href="{{ route('dashbaord') }}" class="nav-link {{ Request::is('admin')  ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>
            {{ __('Core::dashboard.title') }}
        </p>
    </a>
</li>


{{-- Charger les menus des modules dynamiquement --}}
@foreach ($dynamicMenus as $menu)
    @includeIf($menu)
@endforeach