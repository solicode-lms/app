{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatsRealisationProjet', 'index-affectationProjet', 'index-realisationProjet', 'index-livrablesRealisation', 'index-validation'])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgRealisationProjets*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgRealisationProjets*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgRealisationProjets::PkgRealisationProjets.icon')}}"></i>
        <p>
            {{__('PkgRealisationProjets::PkgRealisationProjets.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-etatsRealisationProjet') 
        <li class="nav-item">
            <a href="{{ route('etatsRealisationProjets.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationProjets/etatsRealisationProjets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgRealisationProjets::EtatsRealisationProjet.plural')}}
            </a>
        </li>
        @endcan
        @can('index-affectationProjet') 
        <li class="nav-item">
            <a href="{{ route('affectationProjets.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationProjets/affectationProjets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgRealisationProjets::AffectationProjet.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationProjet') 
        <li class="nav-item">
            <a href="{{ route('realisationProjets.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationProjets/realisationProjets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgRealisationProjets::RealisationProjet.plural')}}
            </a>
        </li>
        @endcan
        @can('index-livrablesRealisation') 
        <li class="nav-item">
            <a href="{{ route('livrablesRealisations.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationProjets/livrablesRealisations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgRealisationProjets::LivrablesRealisation.plural')}}
            </a>
        </li>
        @endcan
        @can('index-validation') 
        <li class="nav-item">
            <a href="{{ route('validations.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationProjets/validations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgRealisationProjets::Validation.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

