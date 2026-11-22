@php $notifBaru = auth()->user()->jumlahNotifikasiBaru(); @endphp
<header class="topbar">
    <div class="topbar-left">
        <button type="button" class="icon-btn menu-toggle" data-toggle-menu aria-label="Buka menu"><i class="bi bi-list"></i></button>
        <span class="topbar-title">@yield('judul', 'Dashboard')</span>
    </div>
    <div class="topbar-right">
        <a href="{{ route('notifikasi.index') }}" class="icon-btn" aria-label="Notifikasi">
            <i class="bi bi-bell"></i>
            @if ($notifBaru > 0)<span class="dot">{{ $notifBaru > 9 ? '9+' : $notifBaru }}</span>@endif
        </a>
        <div class="user-menu">
            <button type="button" class="user-btn" data-dropdown="menuMentor">
                <span class="avatar">{{ auth()->user()->inisial }}</span>
                <span class="name">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <div class="dropdown" id="menuMentor">
                <div class="head">Mentor · {{ auth()->user()->mentor->perusahaan->nama }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"><i class="bi bi-box-arrow-left"></i> Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>
