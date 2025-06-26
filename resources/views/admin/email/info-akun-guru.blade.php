<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Informasi Akun</title>
</head>

<body>
    <h2>Halo, {{ $nama }}</h2>
    <p>Berikut adalah informasi akun untuk login ke sistem Nesasen:</p>

    <ul>
        <li><strong>Username:</strong> {{ $username }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>Silakan login di halaman <a href="{{ url('/') }}">Nesasen</a>.</p>
    <p>Silakan login ke sistem Nesasen dan segera ubah password-nya ya demi keamanan akun anda</p>

    <br>
    <p>~ Nesasen Team</p>
</body>

</html>
