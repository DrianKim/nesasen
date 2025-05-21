<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pilih Peran</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style-role.css') }}">
    <link href="{{ asset('enno/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('enno/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
  </head>
  <body>
    <!-- Hiasan -->
    <img
      src="img/corner-right.png"
      alt="Hiasan Kanan Atas"
      class="decorative-image top-right"
    />
    <img
      src="img/corner-left.png"
      alt="Hiasan Kiri Bawah"
      class="decorative-image bottom-left"
    />

    <!-- Header dengan logo -->
    <header>
      <div class="logo">
        <img src="{{ asset('img/ls-logo.png') }}" alt="Nesasen Logo" />
      </div>
    </header>

    <main class="container">
      <h1>Ayo Mulai</h1>
      <p>Silahkan login sebagai:</p>

      <div class="grid">
        <!-- Admin -->
        <a href="{{ url('login') }}?role=admin" class="card admin">
          <div class="card-content">
            <h2>Admin Sekolah</h2>
            <p>Jika kamu adalah admin sekolah, pilih yang ini</p>
          </div>
          <img
            src="{{ asset('img/atmin-vector.png') }}"
            alt="Admin Sekolah"
            class="vector"
          />
        </a>

        <!-- Guru -->
        <a href="{{ url('login') }}?role=guru" class="card guru">
          <div class="card-content">
            <h2>Guru</h2>
            <p>Jika kamu adalah pahlawan tanpa tanda jasa, pilih yang ini</p>
          </div>
          <img src="{{ asset('img/guru-vector.png') }}" alt="Guru" class="vector" />
        </a>

        <!-- Siswa -->
        <a href="{{ url('login') }}?role=murid" class="card siswa">
          <div class="card-content">
            <h2>Siswa</h2>
            <p>Jika kamu murid NESAS yang CEREN, pilih yang ini</p>
          </div>
          <img src="{{ asset('img/siswa-vector.png') }}" alt="Siswa" class="vector" />
        </a>
      </div>
    </main>

    <!-- Sosial Media Links -->
    <footer>
      <div class="social-media">
        <a href="https://www.youtube.com/@NesasCeren" target="_blank"
          >YouTube</a
        >
        <a href="https://www.instagram.com/officialsmkn1subang/" target="_blank"
          >Instagram</a
        >
        <a href="https://www.facebook.com/officialsmkn1subang/" target="_blank"
          >Facebook</a
        >
      </div>
      <p>&copy; {{ date('Y') }} Made with ❤️ by R & P.</p>
    </footer>

    <script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6'
        });
    </script>
    @endif

  </body>
</html>
