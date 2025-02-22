{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-sysColor', 'index-sysModule', 'index-sysController', 'index-featureDomain', 'index-feature', 'index-sysModel'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-Core" class="nav-item has-treeview  {{ Request::is('admin/Core*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/Core*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('Core::Core.icon')}}"></i>
        <p>
            {{__('Core::Core.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-sysColor') 
        <li class="nav-item" id="menu-sysColors">
            <a href="{{ route('sysColors.index') }}" class="nav-link {{ Request::is('admin/Core/sysColors') ? 'active' : '' }}">
                <i class="nav-icon fas fa-palette"></i>
                {{__('Core::sysColor.plural')}}
            </a>
        </li>
        @endcan
        @can('index-sysModule') 
        <li class="nav-item" id="menu-sysModules">
            <a href="{{ route('sysModules.index') }}" class="nav-link {{ Request::is('admin/Core/sysModules') ? 'active' : '' }}">
                <i class="nav-icon fas fa-box"></i>
                {{__('Core::sysModule.plural')}}
            </a>
        </li>
        @endcan
        @can('index-sysController') 
        <li class="nav-item" id="menu-sysControllers">
            <a href="{{ route('sysControllers.index') }}" class="nav-link {{ Request::is('admin/Core/sysControllers') ? 'active' : '' }}">
                <i class="nav-icon fas fa-server"></i>
                {{__('Core::sysController.plural')}}
            </a>
        </li>
        @endcan
        @can('index-featureDomain') 
        <li class="nav-item" id="menu-featureDomains">
            <a href="{{ route('featureDomains.index') }}" class="nav-link {{ Request::is('admin/Core/featureDomains') ? 'active' : '' }}">
                <i class="nav-icon fas fa-th-large"></i>
                {{__('Core::featureDomain.plural')}}
            </a>
        </li>
        @endcan
        @can('index-feature') 
        <li class="nav-item" id="menu-features">
            <a href="{{ route('features.index') }}" class="nav-link {{ Request::is('admin/Core/features') ? 'active' : '' }}">
                <i class="nav-icon fas fa-plug"></i>
                {{__('Core::feature.plural')}}
            </a>
        </li>
        @endcan
        @can('index-sysModel') 
        <li class="nav-item" id="menu-sysModels">
            <a href="{{ route('sysModels.index') }}" class="nav-link {{ Request::is('admin/Core/sysModels') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cubes"></i>
                {{__('Core::sysModel.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

