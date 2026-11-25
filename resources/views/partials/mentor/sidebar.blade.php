@php
    $penempatanIdMentor = auth()->user()->mentor->penempatan()->pluck('id');
    $logbookMenungguMenu = \App\Models\Logbook::whereIn('penempatan_id', $penempatanIdMentor)->where('status', 'menunggu')->count();
@endphp
<aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebarMenu" aria-label="Menu navigasi mentor">
    <div class="offcanvas-header">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Tutup menu"></button>
    </div>
    <div class="offcanvas-body">
        <div class="sidebar-inner">
            <div class="sidebar-brand"><a href="{{ route('beranda') }}"><x-logo :terang="true" /></a></div>

            <div class="sidebar-user">
                <span class="avatar">{{ auth()->user()->inisial }}</span>
                <div class="nama">{{ auth()->user()->name }}</div>
                <span class="peran">Mentor</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('mentor.dashboard') }}" class="sidebar-link {{ request()->routeIs('mentor.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door-fill"></i> Dashboard</a>
                <a href="{{ route('mentor.mahasiswa') }}" class="sidebar-link {{ request()->routeIs('mentor.mahasiswa*') ? 'active' : '' }}"><i class="bi bi-people"></i> Mahasiswa</a>
                <a href="{{ route('mentor.logbook') }}" class="sidebar-link {{ request()->routeIs('mentor.logbook*') ? 'active' : '' }}">
                    <i class="bi bi-journal-check"></i> Logbook
                    @if ($logbookMenungguMenu > 0)<span class="sidebar-count">{{ $logbookMenungguMenu }}</span>@endif
                </a>
                <a href="{{ route('mentor.penilaian') }}" class="sidebar-link {{ request()->routeIs('mentor.penilaian*') ? 'active' : '' }}"><i class="bi bi-star"></i> Penilaian</a>
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
