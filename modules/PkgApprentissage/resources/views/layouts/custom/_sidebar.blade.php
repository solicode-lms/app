{{-- Si l'utilisateur est admin → afficher l'item --}}
@if(Auth::user()->hasRole('admin'))

    <li class="nav-item" id="menu-realisationModules">
        <a href="{{ route('realisationModules.index') }}" 
           class="nav-link {{ request()->routeIs('realisationModules.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-medal"></i>
            <p>{{ __('PkgApprentissage::realisationModule.plural') }}</p>
        </a>
    </li>

{{-- Sinon → inclure le sidebar "normal" --}}
@else
    @include('PkgApprentissage::layouts._sidebar')
@endif