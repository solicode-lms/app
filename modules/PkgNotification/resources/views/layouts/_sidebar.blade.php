{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-notification'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgNotification" class="nav-item has-treeview  {{ Request::is('admin/PkgNotification*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgNotification*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgNotification::PkgNotification.icon')}}"></i>
        <p>
            {{__('PkgNotification::PkgNotification.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-notification') 
        <li class="nav-item" id="menu-notifications">
            <a href="{{ route('notifications.index') }}" class="nav-link {{ Request::is('admin/PkgNotification/notifications') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bell"></i>
                {{__('PkgNotification::notification.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

