<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Manajemen Kelas</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
</head>

<body>
  <div class="container" id="container">
    <!-- Sign Up (REGISTER) -->
    <div class="form-container sign-up">
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <h1>Sign Up</h1>
        <div class="social-icons">
          <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
        <span>atau gunakan email untuk daftar</span>

        <input type="text" name="name" placeholder="Username" value="{{ old('name') }}" required />
        @error('name') <span style="color:red;">{{ $message }}</span> @enderror

        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
        @error('email') <span style="color:red;">{{ $message }}</span> @enderror

        <input type="password" name="password" placeholder="Password" required />
        @error('password') <span style="color:red;">{{ $message }}</span> @enderror

        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required />

        <button type="submit">Sign Up</button>
      </form>
    </div>

    <!-- Sign In (LOGIN) -->
    <div class="form-container sign-in">
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <h1>Sign In</h1>
        <div class="social-icons">
          <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
        <span>Masuk dengan email & password</span>

        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
        @error('email') <span style="color:red;">{{ $message }}</span> @enderror

        <input type="password" name="password" placeholder="Password" required />
        @error('password') <span style="color:red;">{{ $message }}</span> @enderror

        <a href="#">Lupa password?</a>
        <button type="submit">Sign In</button>
      </form>
    </div>

    <!-- Panel toggle -->
    <div class="toggle-container">
      <div class="toggle">
        <div class="toggle-panel toggle-left">
          <h1>Selamat Datang Kembali!</h1>
          <p>Untuk melanjutkan, silakan masuk ke akun yang sudah ada</p>
          <button class="hidden" id="login">Sign In</button>
        </div>
        <div class="toggle-panel toggle-right">
          <h1>Halo, Teman!</h1>
          <p>Daftarkan dirimu untuk mendapatkan akses ke semua fitur yang ada</p>
          <button class="hidden" id="register">Sign Up</button>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
