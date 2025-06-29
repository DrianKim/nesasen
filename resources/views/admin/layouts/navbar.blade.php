@php
    $nama = explode(' ', Auth::user()->name_admin);
    $namaPendek = implode(' ', array_slice($nama, 0, 1));
@endphp
<div class="nav">
    <button id="menu-btn">
        <span class="material-icons-sharp"> menu </span>
    </button>
    <div class="dark-mode">
        <span class="material-icons-sharp active"> light_mode </span>
        <span class="material-icons-sharp"> dark_mode </span>
    </div>

    <div class="profile">
        <div class="info">
            <p>Hallo, <b>{{ $namaPendek ?? 'Admin' }}</b></p>
            <small class="text-muted">Admin</small>
        </div>
        <div class="profile-photo">
            <img src="{{ asset('assets/img/smeapng.png') }}" />
        </div>
    </div>
</div>
