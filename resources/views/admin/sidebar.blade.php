<aside class="sidebar">
    <div class="brand"><x-logo :terang="true" /></div>
    <div class="role-tag">Panel Admin</div>

    @php
        $menuMenunggu = \App\Models\Lamaran::whereIn('status', ['diajukan', 'diproses'])->count();
    @endphp

    <nav class="nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door-fill"></i> Dashboard</a>
        <a href="{{ route('admin.lamaran') }}" class="nav-item {{ request()->routeIs('admin.lamaran*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Lamaran
            @if ($menuMenunggu > 0)<span class="count">{{ $menuMenunggu }}</span>@endif
        </a>
        <a href="{{ route('admin.pengguna') }}" class="nav-item {{ request()->routeIs('admin.pengguna*') ? 'active' : '' }}"><i class="bi bi-people"></i> Pengguna</a>
        <a href="{{ route('admin.perusahaan') }}" class="nav-item {{ request()->routeIs('admin.perusahaan*') ? 'active' : '' }}"><i class="bi bi-buildings"></i> Perusahaan</a>
        <a href="{{ route('admin.lowongan') }}" class="nav-item {{ request()->routeIs('admin.lowongan*') ? 'active' : '' }}"><i class="bi bi-briefcase"></i> Lowongan</a>
        <a href="{{ route('admin.informasi') }}" class="nav-item {{ request()->routeIs('admin.informasi*') ? 'active' : '' }}"><i class="bi bi-megaphone"></i> Informasi</a>
        <a href="{{ route('admin.laporan') }}" class="nav-item {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i> Laporan</a>
        <a href="{{ route('admin.aktivitas') }}" class="nav-item {{ request()->routeIs('admin.aktivitas') ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Riwayat Aktivitas</a>

        <div class="nav-sep"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item"><i class="bi bi-box-arrow-left"></i> Keluar</button>
        </form>
    </nav>

    <div class="foot"><b>MagangKu</b>Mulai langkah kariermu bersama kami.</div>
</aside>
