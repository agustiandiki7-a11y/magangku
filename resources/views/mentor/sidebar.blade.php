@php
    $penempatanIdMentor = auth()->user()->mentor->penempatan()->pluck('id');
    $logbookMenungguMenu = \App\Models\Logbook::whereIn('penempatan_id', $penempatanIdMentor)->where('status', 'menunggu')->count();
@endphp
<aside class="sidebar">
    <div class="brand"><x-logo :terang="true" /></div>
    <div class="role-tag">Panel Mentor</div>

    <nav class="nav">
        <a href="{{ route('mentor.dashboard') }}" class="nav-item {{ request()->routeIs('mentor.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door-fill"></i> Dashboard</a>
        <a href="{{ route('mentor.mahasiswa') }}" class="nav-item {{ request()->routeIs('mentor.mahasiswa*') ? 'active' : '' }}"><i class="bi bi-people"></i> Mahasiswa</a>
        <a href="{{ route('mentor.logbook') }}" class="nav-item {{ request()->routeIs('mentor.logbook*') ? 'active' : '' }}">
            <i class="bi bi-journal-check"></i> Logbook
            @if ($logbookMenungguMenu > 0)<span class="count">{{ $logbookMenungguMenu }}</span>@endif
        </a>
        <a href="{{ route('mentor.penilaian') }}" class="nav-item {{ request()->routeIs('mentor.penilaian*') ? 'active' : '' }}"><i class="bi bi-star"></i> Penilaian</a>

        <div class="nav-sep"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item"><i class="bi bi-box-arrow-left"></i> Keluar</button>
        </form>
    </nav>

    <div class="foot"><b>MagangKu</b>Membimbing mahasiswa dengan lebih mudah.</div>
</aside>
