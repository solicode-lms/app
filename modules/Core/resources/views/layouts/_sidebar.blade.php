{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['show-feature', 'show-featureDomain', 'show-sysColor', 'show-sysController', 'show-sysModel', 'show-sysModule'])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/Core*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/Core*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('Core::Core.icon')}}"></i>
        <p>
            {{__('Core::Core.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('show-feature') 
        <li class="nav-item">
            <a href="{{ route('features.index') }}" class="nav-link {{ Request::is('admin/Core/features') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Features</p>
            </a>
        </li>
        @endcan
        @can('show-featureDomain') 
        <li class="nav-item">
            <a href="{{ route('featureDomains.index') }}" class="nav-link {{ Request::is('admin/Core/featureDomains') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>FeatureDomains</p>
            </a>
        </li>
        @endcan
        @can('show-sysColor') 
        <li class="nav-item">
            <a href="{{ route('sysColors.index') }}" class="nav-link {{ Request::is('admin/Core/sysColors') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>SysColors</p>
            </a>
        </li>
        @endcan
        @can('show-sysController') 
        <li class="nav-item">
            <a href="{{ route('sysControllers.index') }}" class="nav-link {{ Request::is('admin/Core/sysControllers') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>SysControllers</p>
            </a>
        </li>
        @endcan
        @can('show-sysModel') 
        <li class="nav-item">
            <a href="{{ route('sysModels.index') }}" class="nav-link {{ Request::is('admin/Core/sysModels') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>SysModels</p>
            </a>
        </li>
        @endcan
        @can('show-sysModule') 
        <li class="nav-item">
            <a href="{{ route('sysModules.index') }}" class="nav-link {{ Request::is('admin/Core/sysModules') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>SysModules</p>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

