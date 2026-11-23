@php $notifBaru = auth()->user()->jumlahNotifikasiBaru(); @endphp
<header class="topbar">
    <div class="topbar-left">
        <button type="button" class="icon-btn menu-toggle" data-toggle-menu aria-label="Buka menu"><i class="bi bi-list"></i></button>
        <div class="crumb">
            <span>Mahasiswa</span><i class="bi bi-chevron-right"></i><b>@yield('judul', 'Dashboard')</b>
        </div>
    </div>
    <div class="topbar-right">
        <a href="{{ route('notifikasi.index') }}" class="icon-btn" aria-label="Notifikasi">
            <i class="bi bi-bell"></i>
            @if ($notifBaru > 0)<span class="dot">{{ $notifBaru > 9 ? '9+' : $notifBaru }}</span>@endif
        </a>
        <div class="user-menu">
            <button type="button" class="user-btn" data-dropdown="menuMhs">
                <span class="avatar">{{ auth()->user()->inisial }}</span>
                <span class="name">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <div class="dropdown" id="menuMhs">
                <div class="head">{{ auth()->user()->email }}</div>
                <a href="{{ route('mahasiswa.profil') }}"><i class="bi bi-person"></i> Profil Saya</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"><i class="bi bi-box-arrow-left"></i> Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>
