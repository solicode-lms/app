{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['show-metadataType', 'show-iPackage', 'show-fieldType', 'show-iModel', 'show-dataField', 'show-metadatum', 'show-relationship'])
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
        @can('show-iPackage') 
        <li class="nav-item">
            <a href="{{ route('iPackages.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/iPackages') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::IPackage.plural')}}
            </a>
        </li>
        @endcan
        @can('show-fieldType') 
        <li class="nav-item">
            <a href="{{ route('fieldTypes.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/fieldTypes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::FieldType.plural')}}
            </a>
        </li>
        @endcan
        @can('show-iModel') 
        <li class="nav-item">
            <a href="{{ route('iModels.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/iModels') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::IModel.plural')}}
            </a>
        </li>
        @endcan
        @can('show-dataField') 
        <li class="nav-item">
            <a href="{{ route('dataFields.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/dataFields') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::DataField.plural')}}
            </a>
        </li>
        @endcan
        @can('show-metadatum') 
        <li class="nav-item">
            <a href="{{ route('metadata.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/metadata') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::Metadatum.plural')}}
            </a>
        </li>
        @endcan
        @can('show-relationship') 
        <li class="nav-item">
            <a href="{{ route('relationships.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/relationships') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::Relationship.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

