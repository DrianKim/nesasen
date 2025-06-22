@include('siswa.layouts.header')

<body class="no-right-section">
    <div class="container">
        <!-- Sidebar Section -->
        @include('siswa.layouts.sidebar')
        <!-- End of Sidebar Section -->
        {{-- <section class="main-wrapper">

            @include('siswa.layouts.navbar')
            <main> --}}
                @yield('content')
            {{-- </main>
        </section> --}}
    </div>

    @include('siswa.layouts.footer')
