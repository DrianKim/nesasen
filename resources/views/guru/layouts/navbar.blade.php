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
            <p>Hallo, <b>{{ Auth::user()->nama }}</b></p>
            <small class="text-muted">Guru</small>
        </div>
        <div class="profile-photo">
            <img src="images/profile-1.jpg" />
        </div>
    </div>
</div>
