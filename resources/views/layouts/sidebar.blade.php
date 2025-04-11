        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                {{-- <i class="fas fa-laugh-wink"></i> --}}
                <div class="mx-3 sidebar-brand-text">SMKN 1 Subang</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu Pengguna
            </div>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ $menuDashboard ?? '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-house-user"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Nav Item - Profil -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profil</span>
                </a>
            </li>

            <!-- Menu Role -->
            @switch(auth()->user()->role_id)
                @case(1)
                    @include('components.sidebar-admin')
                @break

                @case(2)
                    @include('components.sidebar-walas')
                @break

                @case(3)
                    @include('components.sidebar-guru')
                @break

                @case(4)
                    @include('components.sidebar-murid')
                @break
            @endswitch
            {{-- @if (auth()->user()->role_id == 1)

            @endif

            @if (auth()->user()->role_id == 2)
            <!-- Divider -->

            @endif

            @if (auth()->user()->role_id == 3)
            <!-- Divider -->

            @endif

            @if (auth()->user()->role_id == 4)

            @endif --}}

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="border-0 rounded-circle" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
