@include('guru.layouts.header')


<body>
    <div class="container">
        <!-- Sidebar Section -->
        @include('guru.layouts.sidebar')
        <!-- End of Sidebar Section -->
        {{-- @include('guru.layouts.navbar') --}}
        {{-- <main> --}}
        @yield('content')
        {{-- </main> --}}
    </div>

    @include('guru.layouts.footer')
