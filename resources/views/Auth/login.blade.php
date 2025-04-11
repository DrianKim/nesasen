<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login - Manajemen Kelas</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

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
                <div class="text-center">
                    <a class="small" href="{{ route('welcome') }}">Kembali Ke Beranda?</a>
                </div>
            </form>
        </div>

        <!-- SIGN IN -->
        <div class="form-container sign-in">
            <form method="POST" action="{{ route('loginProses') }}">
                @csrf
                <h1>Sign In</h1>
                <span>Masuk dengan username dan password</span>

                <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required />
                @error('username')
                    <span style="color:red;">{{ $message }}</span>
                @enderror

                <input type="password" name="password" placeholder="Password" required />
                @error('password')
                    <span style="color:red;">{{ $message }}</span>
                @enderror

                <button type="submit">Sign In</button>
                <div class="text-center">
                    <a class="small" href="{{ route('welcome') }}">Kembali Ke Beranda?</a>
                </div>
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

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('sbadmin2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: '{{ session('success') }}',
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
            });
        </script>
    @endif


</body>

</html>
