@include('layouts.header')

<!-- Loading Overlay -->
<div id="page-loading-overlay">
    <div class="loading-spinner">
        <div class="board">Mengantar Anda ke Halaman Tujuan...</div>

        <div class="graduation-cap"></div>

        <svg viewBox="0 0 100 100" class="spinner-p" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="40" stroke="#3498db" stroke-width="8" fill="none"
                stroke-linecap="round" stroke-dasharray="200" stroke-dashoffset="150">
                <animateTransform attributeName="transform" type="rotate" from="0 50 50" to="360 50 50" dur="1s"
                    repeatCount="indefinite" />
                <animate attributeName="stroke-dashoffset" values="150;100;150" dur="1.5s"
                    repeatCount="indefinite" />
            </circle>
        </svg>
        {{-- <svg viewBox="0 0 100 100" class="spinner-p">
<path d="M20,90 V10 H60 A20,20 0 1,1 20,30" fill="none" stroke="#3498db" stroke-width="8"
    stroke-linecap="round" />
<circle cx="60" cy="22" r="5" fill="#f1c40f" opacity="0.7">
    <animate attributeName="opacity" values="0.7;0.3;0.7" dur="2s" repeatCount="indefinite" />
</circle>
</svg> --}}

        <div class="book"></div>

        <div class="pencil"></div>

        <p class="loading-text">LOADING...</p>

        <div class="academic-stars">
            <span class="star" style="top: 20%; left: 85%;">★</span>
            <span class="star" style="top: 15%; left: 10%;">★</span>
            <span class="star" style="top: 70%; left: 15%;">★</span>
            <span class="star" style="top: 60%; left: 90%;">★</span>
            <span class="star" style="top: 40%; left: 40%;">★</span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pageLoadingOverlay = document.getElementById('page-loading-overlay');

        const hariIndo = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];
        const bulanIndo = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        const now = new Date();
        const hari = hariIndo[now.getDay()];
        const tanggal = now.getDate();
        const bulan = bulanIndo[now.getMonth()];
        const tahun = now.getFullYear();

        const keterangan = `${hari}, ${tanggal} ${bulan} ${tahun}`;
        console.log(keterangan);

        document.getElementById("tanggalSekarang").textContent = keterangan;
        // Cek apakah submit dari modal form
        const isModalSubmit = sessionStorage.getItem('modalSubmit') === 'true';

        if (isModalSubmit) {
            // Jangan tampilkan loading
            pageLoadingOverlay.classList.add('hide');
            pageLoadingOverlay.style.display = 'none';
            sessionStorage.removeItem('modalSubmit');
        } else {
            // Tampilkan loading hanya untuk normal reload/page load
            pageLoadingOverlay.classList.remove('hide');

            window.addEventListener('load', function() {
                pageLoadingOverlay.classList.add('hide');
                pageLoadingOverlay.addEventListener('transitionend', function() {
                    pageLoadingOverlay.style.display = 'none';
                });
            });
        }

        // Tambahkan event untuk semua form yg pakai modal atau ajax-like submit
        const modalForms = document.querySelectorAll('form.form-modal, form.form-delete');
        modalForms.forEach(form => {
            form.addEventListener('submit', function() {
                sessionStorage.setItem('modalSubmit', 'true');
            });
        });
    });
</script>

<body id="page-top"
    class="@if (session('dark_mode', false)) dark-mode @endif
@if (auth()->user()->role_id == 1) admin
@elseif (auth()->user()->role_id == 2 || auth()->user()->role_id == 3) guru
@else siswa @endif">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('layouts.sidebar')

        <!-- Navigation Mobile -->
        @if (@auth()->user()->role_id != 1)
            @include('layouts.bottom-navbar')
        @endif

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

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

                    <!-- Quick Actions -->
                    {{-- <div class="mx-2 nav-item dropdown no-arrow d-none d-sm-block">
            <a class="nav-link dropdown-toggle" href="#" id="quickActionsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bolt fa-fw text-primary"></i>
                <span class="ml-1 d-none d-lg-inline small">Quick Actions</span>
            </a>
            <div class="shadow dropdown-menu dropdown-menu-right animated--grow-in"
                aria-labelledby="quickActionsDropdown">
                <h6 class="dropdown-header">
                    Quick Actions
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3 text-primary">
                        <i class="fas fa-plus-circle fa-sm"></i>
                    </div>
                    <span>New Task</span>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3 text-success">
                        <i class="fas fa-calendar-plus fa-sm"></i>
                    </div>
                    <span>Schedule Meeting</span>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3 text-info">
                        <i class="fas fa-file-alt fa-sm"></i>
                    </div>
                    <span>Create Report</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3 text-warning">
                        <i class="fas fa-cog fa-sm"></i>
                    </div>
                    <span>Settings</span>
                </a>
            </div>
        </div> --}}

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
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                        <img class="rounded-circle"
                                            src="{{ asset('sbadmin2\img\undraw_profile_3.svg') }}" alt="User Avatar">
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
                                        <img class="rounded-circle"
                                            src="{{ asset('sbadmin2\img\undraw_profile_2.svg') }}" alt="User Avatar">
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
                                        <img class="rounded-circle"
                                            src="{{ asset('sbadmin2\img\undraw_profile.svg') }}" alt="User Avatar">
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
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="mr-2 d-none d-lg-inline small">{{ auth()->user()->name_admin ?? (auth()->user()->siswa->nama ?? auth()->user()->guru->nama) }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('images/avatars/profile-' . auth()->id() % 5 . '.jpg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="shadow dropdown-menu dropdown-menu-right animated--grow-in"
                                aria-labelledby="userDropdown">
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
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page heading and quick status -->
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        {{-- <h1 class="mb-0 text-gray-800 h3">{{ $pageTitle ?? $title }}</h1> --}}

                        <!-- Status indicators -->
                        @if (isset($showStatus) && $showStatus)
                            <div class="d-flex status-indicators">
                                <div class="mx-2 text-xs">
                                    <span class="mx-1 badge badge-primary">Semester:
                                        {{ $currentSemester ?? '2' }}</span>
                                    <span class="mx-1 badge badge-success">Tahun Ajaran:
                                        {{ $currentYear ?? '2024/2025' }}</span>
                                    @if (auth()->user()->role_id == 4)
                                        <span class="mx-1 badge badge-info">Kelas:
                                            {{ auth()->user()->siswa->kelas->jurusan->kode_jurusan ?? '-' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="my-auto text-center copyright">
                        <span class="text-gray-600">© {{ date('Y') }} SMKN 1 Subang</span>
                        <span class="mx-2">·</span>
                        <span class="text-gray-600">Made with <i class="text-danger fas fa-heart"></i> by
                            <a href="#" class="font-weight-bold">R</a> &
                            <a href="#" class="font-weight-bold">P</a>
                        </span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="rounded scroll-to-top" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Todo List Modal -->
    <div class="modal fade" id="todoModal" tabindex="-1" role="dialog" aria-labelledby="todoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="todoModalLabel">Task Manager</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 todo-input-container">
                        <div class="input-group">
                            <input type="text" id="newTodoInput" class="form-control"
                                placeholder="Add a new task...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="addTodoBtn">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="todo-list-container">
                        <ul class="list-group todo-list" id="todoList">
                            <!-- Tasks will be loaded here -->
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-danger" id="clearCompletedBtn">Clear Completed</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced Auto-Hide Sidebar dengan Hover & Pin Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggleTop');
            const contentWrapper = document.getElementById('content-wrapper');

            // Create pin button
            const pinButton = document.createElement('button');
            pinButton.className = 'sidebar-pin-btn';
            pinButton.innerHTML = '<i class="fas fa-thumbtack"></i>';
            pinButton.title = 'Pin/Unpin Sidebar';

            // Create sidebar trigger area
            const sidebarTrigger = document.createElement('div');
            sidebarTrigger.className = 'sidebar-trigger';

            // Create sidebar edge indicator
            const sidebarEdge = document.createElement('div');
            sidebarEdge.className = 'sidebar-edge';

            // Create toggle button for auto-hide mode
            const toggleButton = document.createElement('button');
            toggleButton.className = 'sidebar-toggle';
            toggleButton.innerHTML = '<i class="fas fa-bars"></i>';
            toggleButton.title = 'Toggle Sidebar';

            if (sidebar && window.innerWidth >= 768) {
                // Add elements to sidebar
                sidebar.appendChild(pinButton);
                sidebar.appendChild(sidebarEdge);
                document.body.appendChild(sidebarTrigger);
                document.body.appendChild(toggleButton);

                // Initialize auto-hide mode
                let isAutoHide = localStorage.getItem('sidebarAutoHide') !== 'false';
                let isPinned = localStorage.getItem('sidebarPinned') === 'true';

                function updateSidebarState() {
                    if (isAutoHide) {
                        sidebar.classList.add('auto-hide');
                        if (isPinned) {
                            sidebar.classList.add('pinned');
                            pinButton.classList.add('pinned');
                        } else {
                            sidebar.classList.remove('pinned');
                            pinButton.classList.remove('pinned');
                        }
                    } else {
                        sidebar.classList.remove('auto-hide', 'pinned');
                        pinButton.classList.remove('pinned');
                    }
                }

                // Initialize state
                updateSidebarState();

                // Pin button functionality
                pinButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isPinned = !isPinned;
                    localStorage.setItem('sidebarPinned', isPinned);
                    updateSidebarState();
                });

                // Toggle button functionality
                toggleButton.addEventListener('click', function() {
                    isAutoHide = !isAutoHide;
                    localStorage.setItem('sidebarAutoHide', isAutoHide);
                    updateSidebarState();
                });

                // Hover functionality
                let hoverTimeout;

                sidebarTrigger.addEventListener('mouseenter', function() {
                    if (isAutoHide && !isPinned) {
                        clearTimeout(hoverTimeout);
                        sidebar.style.transform = 'translateX(0)';
                    }
                });

                sidebar.addEventListener('mouseenter', function() {
                    if (isAutoHide && !isPinned) {
                        clearTimeout(hoverTimeout);
                        this.style.transform = 'translateX(0)';
                    }
                });

                sidebar.addEventListener('mouseleave', function() {
                    if (isAutoHide && !isPinned) {
                        hoverTimeout = setTimeout(() => {
                            this.style.transform = 'translateX(-210px)';
                        }, 300); // 300ms delay sebelum hide
                    }
                });

                // Click outside to unpin
                document.addEventListener('click', function(e) {
                    if (isAutoHide && isPinned &&
                        !sidebar.contains(e.target) &&
                        !pinButton.contains(e.target)) {
                        isPinned = false;
                        localStorage.setItem('sidebarPinned', false);
                        updateSidebarState();
                    }
                });

                // Keyboard shortcuts
                document.addEventListener('keydown', function(e) {
                    // Ctrl + Shift + S untuk toggle auto-hide
                    if (e.ctrlKey && e.shiftKey && e.key === 'S') {
                        e.preventDefault();
                        isAutoHide = !isAutoHide;
                        localStorage.setItem('sidebarAutoHide', isAutoHide);
                        updateSidebarState();
                    }

                    // Ctrl + Shift + P untuk toggle pin
                    if (e.ctrlKey && e.shiftKey && e.key === 'P' && isAutoHide) {
                        e.preventDefault();
                        isPinned = !isPinned;
                        localStorage.setItem('sidebarPinned', isPinned);
                        updateSidebarState();
                    }
                });
            }

            // Mobile functionality
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (window.innerWidth <= 767) {
                        sidebar.classList.toggle('show');

                        // Create/remove overlay
                        let overlay = document.querySelector('.sidebar-overlay');
                        if (sidebar.classList.contains('show')) {
                            if (!overlay) {
                                overlay = document.createElement('div');
                                overlay.className = 'sidebar-overlay';
                                overlay.style.cssText = `
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: rgba(0,0,0,0.5);
                            z-index: 999;
                            display: block;
                            backdrop-filter: blur(2px);
                        `;
                                document.body.appendChild(overlay);

                                // Close sidebar when clicking overlay
                                overlay.addEventListener('click', function() {
                                    sidebar.classList.remove('show');
                                    overlay.remove();
                                });
                            }
                        } else if (overlay) {
                            overlay.remove();
                        }
                    }
                });
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 767) {
                    // Mobile mode
                    if (sidebar) {
                        sidebar.classList.remove('auto-hide', 'pinned');
                        sidebar.style.transform = '';
                    }
                    const overlay = document.querySelector('.sidebar-overlay');
                    if (overlay) overlay.remove();
                } else {
                    // Desktop mode
                    if (sidebar) {
                        sidebar.classList.remove('show');
                        updateSidebarState();
                    }
                }
            });

            // Bottom navbar active state
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.bottom-navbar .nav-link');

            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.includes(href.split('/').pop())) {
                    link.classList.add('active');
                }
            });

            // Smooth scroll untuk sidebar menu
            sidebar?.addEventListener('click', function(e) {
                const link = e.target.closest('.nav-link');
                if (link && link.getAttribute('href')?.startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(link.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            });

            console.log('Enhanced Auto-Hide Sidebar initialized! 🚀');
            console.log('Shortcuts: Ctrl+Shift+S (toggle auto-hide), Ctrl+Shift+P (toggle pin)');
        });
    </script>

    @include('layouts.footer')
