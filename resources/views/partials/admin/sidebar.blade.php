@php
    $menuMenunggu = \App\Models\Lamaran::whereIn('status', ['diajukan', 'diproses'])->count();
    $roleAktif = request('role');   // filter role di halaman Pengguna (mahasiswa / mentor)
    $diPengguna = request()->routeIs('admin.pengguna*');
@endphp
<aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebarMenu" aria-label="Menu navigasi admin">
    <div class="offcanvas-header">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Tutup menu"></button>
    </div>
    <div class="offcanvas-body">
        <div class="sidebar-inner">
            <div class="sidebar-brand"><a href="{{ route('beranda') }}"><x-logo :terang="true" /></a></div>

            <div class="sidebar-user">
                <span class="avatar">{{ auth()->user()->inisial }}</span>
                <div class="nama">{{ auth()->user()->name }}</div>
                <span class="peran">Admin</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door-fill"></i> Dashboard</a>
                <a href="{{ route('admin.lamaran') }}" class="sidebar-link {{ request()->routeIs('admin.lamaran*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Lamaran
                    @if ($menuMenunggu > 0)<span class="sidebar-count">{{ $menuMenunggu }}</span>@endif
                </a>

                <div class="sidebar-label">Data</div>
                <a href="{{ route('admin.pengguna', ['role' => 'mahasiswa']) }}" class="sidebar-link {{ $diPengguna && $roleAktif === 'mahasiswa' ? 'active' : '' }}"><i class="bi bi-mortarboard"></i> Mahasiswa</a>
                <a href="{{ route('admin.pengguna', ['role' => 'mentor']) }}" class="sidebar-link {{ $diPengguna && $roleAktif === 'mentor' ? 'active' : '' }}"><i class="bi bi-person-workspace"></i> Mentor</a>
                <a href="{{ route('admin.pengguna') }}" class="sidebar-link {{ $diPengguna && ! in_array($roleAktif, ['mahasiswa', 'mentor'], true) ? 'active' : '' }}"><i class="bi bi-people"></i> Semua Pengguna</a>
                <a href="{{ route('admin.perusahaan') }}" class="sidebar-link {{ request()->routeIs('admin.perusahaan*') ? 'active' : '' }}"><i class="bi bi-buildings"></i> Perusahaan</a>
                <a href="{{ route('admin.lowongan') }}" class="sidebar-link {{ request()->routeIs('admin.lowongan*') ? 'active' : '' }}"><i class="bi bi-briefcase"></i> Lowongan</a>

                <div class="sidebar-label">Sistem</div>
                <a href="{{ route('admin.informasi') }}" class="sidebar-link {{ request()->routeIs('admin.informasi*') ? 'active' : '' }}"><i class="bi bi-megaphone"></i> Informasi</a>
                <a href="{{ route('admin.laporan') }}" class="sidebar-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i> Laporan</a>
                <a href="{{ route('admin.aktivitas') }}" class="sidebar-link {{ request()->routeIs('admin.aktivitas') ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Riwayat Aktivitas</a>
            </nav>

            <div class="sidebar-foot">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link"><i class="bi bi-box-arrow-left"></i> Keluar</button>
                </form>
            </div>
        </div>
    </div>
</aside>
