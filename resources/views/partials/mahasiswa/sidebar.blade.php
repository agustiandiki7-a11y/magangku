<aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebarMenu" aria-label="Menu navigasi mahasiswa">
    <div class="offcanvas-header">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Tutup menu"></button>
    </div>
    <div class="offcanvas-body">
        <div class="sidebar-inner">
            <div class="sidebar-brand"><a href="{{ route('beranda') }}"><x-logo :terang="true" /></a></div>

            <div class="sidebar-user">
                <span class="avatar">{{ auth()->user()->inisial }}</span>
                <div class="nama">{{ auth()->user()->name }}</div>
                <span class="peran">Mahasiswa</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('mahasiswa.dashboard') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door-fill"></i> Dashboard</a>

                <div class="sidebar-label">Magang</div>
                <a href="{{ route('mahasiswa.lowongan') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.lowongan*') || request()->routeIs('mahasiswa.ajukan*') ? 'active' : '' }}"><i class="bi bi-search"></i> Cari Lowongan</a>
                <a href="{{ route('mahasiswa.lamaran') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.lamaran*') ? 'active' : '' }}"><i class="bi bi-send"></i> Lamaran Saya</a>
                <a href="{{ route('mahasiswa.riwayat') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.riwayat') ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Riwayat</a>
                <a href="{{ route('mahasiswa.logbook') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.logbook*') ? 'active' : '' }}"><i class="bi bi-journal-bookmark"></i> Logbook</a>
                <a href="{{ route('mahasiswa.penilaian') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.penilaian') ? 'active' : '' }}"><i class="bi bi-star"></i> Penilaian</a>

                <div class="sidebar-label">Akun</div>
                <a href="{{ route('mahasiswa.profil') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.profil*') ? 'active' : '' }}"><i class="bi bi-person"></i> Profil Saya</a>
            </nav>

            <div class="sidebar-foot">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link"><i class="bi bi-box-arrow-left"></i> Logout</button>
                </form>
            </div>
        </div>
    </div>
</aside>
