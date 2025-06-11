<!-- Topbar -->
<nav class="mb-2 shadow navbar navbar-expand navbar-light topbar static-top" id="main-topbar">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="mr-3 btn btn-link d-md-none rounded-circle">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Hari -->
    <div class="mr-auto d-none d-sm-inline-block form-inline ml-md-0 my-md-0 mw-100">
        <div class="input-group global-search">
            <span id="tanggalSekarang" class="bg-transparent border-0 input-group-text">
            </span>
        </div>
    </div>

    <!-- Topbar Navbar -->
    <ul class="ml-auto navbar-nav">

        <!-- Dark Mode Toggle -->
        <li class="mx-1 nav-item dropdown no-arrow">
            <a class="nav-link" href="#" id="darkModeToggle" role="button">
                <i class="fas fa-moon fa-fw"></i>
            </a>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="mx-1 nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-primary badge-counter">3</span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="shadow dropdown-list dropdown-menu dropdown-menu-right animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Notification Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="text-white fas fa-file-alt"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500 small">Today</div>
                        <span class="font-weight-bold">New assignment has been added</span>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-success">
                            <i class="text-white fas fa-check"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500 small">Yesterday</div>
                        <span>Your submission was graded</span>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-warning">
                            <i class="text-white fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500 small">April 18, 2025</div>
                        <span>Assignment deadline approaching</span>
                    </div>
                </a>
                <a class="text-center text-primary small dropdown-item" href="#">Show All
                    Notifications</a>
            </div>
        </li>

        <!-- Nav Item - Messages -->
        <li class="mx-1 nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-primary badge-counter">4</span>
            </a>
            <!-- Dropdown - Messages -->
            <div class="shadow dropdown-list dropdown-menu dropdown-menu-right animated--grow-in"
                aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                    Message Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3 dropdown-list-image">
                        <img class="rounded-circle" src="{{ asset('sbadmin2\img\undraw_profile_3.svg') }}"
                            alt="User Avatar">
                        <div class="status-indicator bg-success"></div>
                    </div>
                    <div class="font-weight-bold">
                        <div class="text-truncate">Hi there! Do you have time for the upcoming meeting?
                        </div>
                        <div class="text-gray-500 small">Bu Siti · Just now</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3 dropdown-list-image">
                        <img class="rounded-circle" src="{{ asset('sbadmin2\img\undraw_profile_2.svg') }}"
                            alt="User Avatar">
                        <div class="status-indicator"></div>
                    </div>
                    <div>
                        <div class="text-truncate">I've sent you the assignment details, please check
                        </div>
                        <div class="text-gray-500 small">Pak Ahmad · 1h</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3 dropdown-list-image">
                        <img class="rounded-circle" src="{{ asset('sbadmin2\img\undraw_profile.svg') }}"
                            alt="User Avatar">
                        <div class="status-indicator bg-warning"></div>
                    </div>
                    <div>
                        <div class="text-truncate">Your project submission looks great! Just one minor
                            change needed.</div>
                        <div class="text-gray-500 small">Pak Rahmat · 2h</div>
                    </div>
                </a>
                <a class="text-center text-primary small dropdown-item" href="#">Read More
                    Messages</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span
                    class="mr-2 d-none d-lg-inline small">{{ auth()->user()->name_admin ?? (auth()->user()->siswa->nama ?? auth()->user()->guru->nama) }}</span>
                <img class="img-profile rounded-circle"
                    src="{{ asset('images/avatars/profile-' . auth()->id() % 5 . '.jpg') }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="shadow dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profil.index') }}">
                    <i class="mr-2 text-gray-500 fas fa-user fa-sm fa-fw"></i>
                    Profil Saya
                </a>
                {{-- <a class="dropdown-item" href="#">
                        <i class="mr-2 text-gray-500 fas fa-cogs fa-sm fa-fw"></i>
                        Settings
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="mr-2 text-gray-500 fas fa-list fa-sm fa-fw"></i>
                        Histori
                    </a> --}}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}">
                    <i class="mr-2 text-gray-500 fas fa-sign-out-alt fa-sm fa-fw"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
