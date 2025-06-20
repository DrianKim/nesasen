@include('layouts.header')

@include('layouts.loading-page')

<script>
    if (window.innerWidth < 768) {
        fetch("/set-mobile-session", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                is_mobile: true
            })
        });
    }
</script>

<body id="page-top"
    class="@if (session('dark_mode', false)) dark-mode @endif
    @auth
        @if (auth()->user()->role_id == 1) admin
        @elseif (auth()->user()->role_id == 2 || auth()->user()->role_id == 3) guru
        @else siswa
        @endif
    @endauth">

    <!-- Page Wrapper -->
    <div id="wrapper">

        {{-- @include('layouts.sidebar') --}}

        <!-- Navigation Mobile -->
        @if (@auth()->user()->role_id != 1)
            {{-- @include('layouts.bottom-navbar') --}}
        @endif

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                {{-- @include('layouts.topbar') --}}
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

            @include('layouts.footer-cr')

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
</body>

</html>
