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

    <!-- SIGN UP -->
    <div class="form-container sign-up">
      <form method="POST" action="{{ route('registerProses') }}">
        @csrf
        <h1>Sign Up</h1>

        <span>Silakan daftar dengan mengisi form berikut</span>

        <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required />
        @if ($errors->register->has('name'))
          <span style="color:red;">{{ $errors->register->first('name') }}</span>
        @endif

        <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required />
        @if ($errors->register->has('username'))
          <span style="color:red;">{{ $errors->register->first('username') }}</span>
        @endif

        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
        @if ($errors->register->has('email'))
          <span style="color:red;">{{ $errors->register->first('email') }}</span>
        @endif

        <input type="password" name="password" placeholder="Password" required />
        @if ($errors->register->has('password'))
          <span style="color:red;">{{ $errors->register->first('password') }}</span>
        @endif

        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required />

        <button type="submit">Sign Up</button>
      </form>
    </div>

    <!-- SIGN IN -->
    <div class="form-container sign-in">
      <form method="POST" action="{{ route('loginProses') }}">
        @csrf
        <h1>Sign In</h1>
        <span>Masuk dengan username dan password</span>

        <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required />
        @error('username') <span style="color:red;">{{ $message }}</span> @enderror

        <input type="password" name="password" placeholder="Password" required />
        @error('password') <span style="color:red;">{{ $message }}</span> @enderror

        <button type="submit">Sign In</button>
      </form>
    </div>

    <!-- TOGGLE PANEL -->
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
