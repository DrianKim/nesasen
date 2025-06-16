@include('admin.layouts.header')

<body class="no-right-section">
    <div class="container">
        <!-- Sidebar Section -->
        @include('admin.layouts.sidebar')
        <!-- End of Sidebar Section -->

        <section class="main-wrapper">

            @include('admin.layouts.navbar')
            <main>
                @yield('content')
            </main>
        </section>
    </div>

    @include('admin.layouts.footer')
