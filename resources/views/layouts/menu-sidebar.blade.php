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