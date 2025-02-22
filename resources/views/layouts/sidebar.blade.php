{{-- // TODO : Affichage de menu par couleur de rôle  --}}
<aside class="main-sidebar sidebar-dark-info elevation-4">
    {{-- Logo --}}
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="logo ofppt" class="brand-image img-circle elevation-3 style="opacity: .8"">
        <span class="brand-text font-weight-light">SoliLMS</span>
        <span class="brand-text font-weight-light"> {{$sessionState->all()["user_annee_formation"]}} </span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
              <img src="{{ asset('images/man.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
              <a href="#" class="d-block">
                @if (Auth::check() && Auth::user()->name)
                    {{ Auth::user()->name }} 
                @endif
              </a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent nav-legacy" data-widget="treeview" role="menu"
                data-accordion="false"
                id="menu-side-bar"
                >
                @include('layouts.menu-sidebar')
            </ul>
        </nav>
    </div>
</aside>
