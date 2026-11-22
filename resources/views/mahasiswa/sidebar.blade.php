<aside class="sidebar">
    <div class="brand"><x-logo :terang="true" /></div>

    <nav class="nav">
        <a href="{{ route('mahasiswa.dashboard') }}" class="nav-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door-fill"></i> Dashboard</a>
        <a href="{{ route('mahasiswa.lowongan') }}" class="nav-item {{ request()->routeIs('mahasiswa.lowongan*') || request()->routeIs('mahasiswa.ajukan*') ? 'active' : '' }}"><i class="bi bi-search"></i> Cari Lowongan</a>
        <a href="{{ route('mahasiswa.lamaran') }}" class="nav-item {{ request()->routeIs('mahasiswa.lamaran*') ? 'active' : '' }}"><i class="bi bi-send"></i> Lamaran Saya</a>
        <a href="{{ route('mahasiswa.riwayat') }}" class="nav-item {{ request()->routeIs('mahasiswa.riwayat') ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Riwayat</a>
        <a href="{{ route('mahasiswa.logbook') }}" class="nav-item {{ request()->routeIs('mahasiswa.logbook*') ? 'active' : '' }}"><i class="bi bi-journal-bookmark"></i> Logbook</a>
        <a href="{{ route('mahasiswa.penilaian') }}" class="nav-item {{ request()->routeIs('mahasiswa.penilaian') ? 'active' : '' }}"><i class="bi bi-star"></i> Penilaian</a>
        <a href="{{ route('mahasiswa.profil') }}" class="nav-item {{ request()->routeIs('mahasiswa.profil*') ? 'active' : '' }}"><i class="bi bi-person"></i> Profil Saya</a>

        <div class="nav-sep"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item"><i class="bi bi-box-arrow-left"></i> Logout</button>
        </form>
    </nav>

    <div class="foot"><b>MagangKu</b><i>Mulai langkah kariermu bersama kami.</i></div>
</aside>
