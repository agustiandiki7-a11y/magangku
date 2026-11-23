@php
    $menuMenunggu = \App\Models\Lamaran::whereIn('status', ['diajukan', 'diproses'])->count();
    $roleAktif = request('role');   // filter role di halaman Pengguna (mahasiswa / mentor)
    $diPengguna = request()->routeIs('admin.pengguna*');
@endphp
<aside class="sidebar">
    <div class="brand"><x-logo :terang="true" /></div>
    <div class="role-tag">Admin</div>

    <nav class="nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door-fill"></i> Dashboard</a>
        <a href="{{ route('admin.lamaran') }}" class="nav-item {{ request()->routeIs('admin.lamaran*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Lamaran
            @if ($menuMenunggu > 0)<span class="count">{{ $menuMenunggu }}</span>@endif
        </a>

        <div class="nav-label">Data</div>
        <a href="{{ route('admin.pengguna', ['role' => 'mahasiswa']) }}" class="nav-item {{ $diPengguna && $roleAktif === 'mahasiswa' ? 'active' : '' }}"><i class="bi bi-mortarboard"></i> Mahasiswa</a>
        <a href="{{ route('admin.pengguna', ['role' => 'mentor']) }}" class="nav-item {{ $diPengguna && $roleAktif === 'mentor' ? 'active' : '' }}"><i class="bi bi-person-workspace"></i> Mentor</a>
        <a href="{{ route('admin.pengguna') }}" class="nav-item {{ $diPengguna && ! in_array($roleAktif, ['mahasiswa', 'mentor'], true) ? 'active' : '' }}"><i class="bi bi-people"></i> Semua Pengguna</a>
        <a href="{{ route('admin.perusahaan') }}" class="nav-item {{ request()->routeIs('admin.perusahaan*') ? 'active' : '' }}"><i class="bi bi-buildings"></i> Perusahaan</a>
        <a href="{{ route('admin.lowongan') }}" class="nav-item {{ request()->routeIs('admin.lowongan*') ? 'active' : '' }}"><i class="bi bi-briefcase"></i> Lowongan</a>

        <div class="nav-label">Sistem</div>
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
