@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\File;

    $dynamicMenus = [];

    // Récupérer la liste des modules actifs, triés par la colonne 'order'
    $modules = DB::table('sys_modules')
        ->orderBy('ordre', 'asc')
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

{{-- Tableau de bord V2 par Acteur --}}
@auth
    @if(Auth::user()->hasAnyRole(['admin', 'root']))
        <li class="nav-item has-treeview {{ Request::is('tableau-bord*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('tableau-bord*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>
                    Tableaux de bord (V2)
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('tableau-bord.responsable') }}" class="nav-link {{ Request::is('tableau-bord/responsable*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-circle text-primary"></i>
                        <p>Responsable</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tableau-bord.formateur') }}" class="nav-link {{ Request::is('tableau-bord/formateur*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-circle text-info"></i>
                        <p>Formateur</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tableau-bord.apprenant') }}" class="nav-link {{ Request::is('tableau-bord/apprenant*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-circle text-success"></i>
                        <p>Apprenant</p>
                    </a>
                </li>
            </ul>
        </li>
    @elseif(Auth::user()->hasAnyRole(['formateur', 'admin-formateur']))
        <li class="nav-item">
            <a href="{{ route('tableau-bord.formateur') }}" class="nav-link {{ Request::is('tableau-bord/formateur*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>Tableau de bord (V2)</p>
            </a>
        </li>
    @elseif(Auth::user()->hasRole('apprenant'))
        <li class="nav-item">
            <a href="{{ route('tableau-bord.apprenant') }}" class="nav-link {{ Request::is('tableau-bord/apprenant*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>Tableau de bord (V2)</p>
            </a>
        </li>
    @else
        <li class="nav-item">
            <a href="{{ route('tableau-bord.responsable') }}" class="nav-link {{ Request::is('tableau-bord*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>Tableau de bord (V2)</p>
            </a>
        </li>
    @endif
@endauth


{{-- Charger les menus des modules dynamiquement --}}
@foreach ($dynamicMenus as $menu)
    @includeIf($menu)
@endforeach