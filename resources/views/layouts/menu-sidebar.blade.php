<li class="nav-item">
    <a href="{{ route('dashbaord') }}" class="nav-link {{ Request::is('admin*') || Request::is('/') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>
            {{ __('Core::dashboard.title') }}
        </p>
    </a>
</li>

{{-- Charger les menus des packages dynamiquement --}}
@foreach (loadDynamicMenus() as $menu)
    {!! $menu !!}
@endforeach