{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-Core" class="nav-item has-treeview  {{ Request::is('admin/Core*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/Core*') ? 'active' : '' }}">
        <i class="nav-icon {{__('Core::Core.icon')}}"></i>
        <p>
            {{__('Core::Core.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>
@endif

