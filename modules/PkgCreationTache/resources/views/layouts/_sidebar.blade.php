{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-prioriteTache'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgCreationTache" class="nav-item has-treeview  {{ Request::is('admin/PkgCreationTache*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgCreationTache*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgCreationTache::PkgCreationTache.icon')}}"></i>
        <p>
            {{__('PkgCreationTache::PkgCreationTache.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-prioriteTache') 
        <li class="nav-item" id="menu-prioriteTaches">
            <a href="{{ route('prioriteTaches.index') }}" class="nav-link {{ Request::is('admin/PkgCreationTache/prioriteTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list-ol"></i>
                {{__('PkgCreationTache::prioriteTache.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

