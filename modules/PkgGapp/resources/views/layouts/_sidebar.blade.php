{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['show-metadataType'])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgGapp*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgGapp*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgGapp::PkgGapp.icon')}}"></i>
        <p>
            {{__('PkgGapp::PkgGapp.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('show-metadataType') 
        <li class="nav-item">
            <a href="{{ route('metadataTypes.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/metadataTypes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::MetadataType.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

