<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lengkapi Data Diri</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style-register.css') }}">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
    <link href="{{ asset('enno/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('enno/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
  </head>
  <body>
    <header>
      <div class="logo">
        <img src="{{ asset('img/ls-logo.png') }}" alt="Nesasen Logo" />
      </div>
    </header>

    <div class="register-box">
      <div class="image">
        <img
         id="role-image"
         alt="Role Image"
         src="{{ asset('img/siswa-vector.png') }}"
         data-siswa="{{ asset('img/siswa-vector.png') }}"
         data-guru="{{ asset('img/guru-vector.png') }}"
        />
      </div>
      <div class="form-container">
        <h2 id="role-title">Lengkapi Data Diri Kamu</h2>
        <p>Masukkan data diri berikut untuk melanjutkan</p>

        <form method="POST" action="{{ route('register.data.store', ['role' => request('role')]) }}">
          @csrf
          <!-- Input Nama Lengkap -->
          <div class="input-group">
            <label for="full-name">Nama Lengkap</label>
            <input
              type="text"
              id="full-name"
              name="nama"
              placeholder="Nama Lengkap"
              required
            />
            <span class="input-icon2"><i class="fa fa-user"></i></span>
          </div>

          <!-- Input Tanggal Lahir -->
          <div class="input-group">
            <label for="birth-date">Tanggal Lahir</label>
            <input
              type="date"
              id="birth-date"
              name="tanggal_lahir"
              required
            />
           <span class="input-icon2"><i class="fa fa-calendar-alt"></i></span>
          </div>

          <!-- Input Nomor Telepon -->
          <div class="input-group">
            <label for="phone-number">Nomor Handphone</label>
            <input
              type="tel"
              id="phone-number"
              name="no_hp"
              placeholder="Nomor Handphone"
              required
            />
            <span class="input-icon2"><i class="fa fa-phone-alt"></i></span>
          </div>

          <!-- Tambahkan input hidden role -->
          <input type="hidden" name="role" value="{{ request('role') }}">         
          <input type="hidden" name="email" value="{{ request('email') }}" readonly>

          <button class="button-regist2" type="submit" id="register-button">
            LANJUTKAN
          </button>
        </form>
      </div>
    </div>

    <footer>
      <p>&copy; 2025 Made with ❤️ by P & R.</p>
    </footer>

    <script>
      // Get the 'role' parameter from the URL
      const urlParams = new URLSearchParams(window.location.search);
      const role = urlParams.get("role");

      // Change the title and image based on role
      const roleTitle = document.getElementById("role-title");
      const roleImage = document.getElementById("role-image");

      if (role === "siswa") {
        roleTitle.textContent = "Lengkapi Data Diri Sebagai Siswa";
        roleImage.src = roleImage.dataset.siswa;
      } else if (role === "guru") {
        roleTitle.textContent = "Lengkapi Data Diri Sebagai Guru";
        roleImage.src = roleImage.dataset.guru;
      }

    
    </script>
  </body>
</html>
