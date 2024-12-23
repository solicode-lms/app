{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<li class="nav-item has-treeview {{ Request::is('Core*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('Core*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('Core::Core.icon')}}"></i>
        <p>
            {{__('Core::Core.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('features.index') }}" class="nav-link {{ Request::is('Core/features') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Features</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('featureDomains.index') }}" class="nav-link {{ Request::is('Core/featureDomains') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>FeatureDomains</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('sysControllers.index') }}" class="nav-link {{ Request::is('Core/sysControllers') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>SysControllers</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('sysModels.index') }}" class="nav-link {{ Request::is('Core/sysModels') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>SysModels</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('sysModules.index') }}" class="nav-link {{ Request::is('Core/sysModules') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>SysModules</p>
            </a>
        </li>
    </ul>
</li>


