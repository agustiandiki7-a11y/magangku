@php $notifBaru = auth()->user()->jumlahNotifikasiBaru(); @endphp
<header class="topbar">
    <div class="topbar-left">
        <button type="button" class="icon-btn d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Buka menu"><i class="bi bi-list"></i></button>
        <div class="crumb">
            <span class="d-none d-sm-inline">Mahasiswa</span><i class="bi bi-chevron-right d-none d-sm-inline"></i><b>@yield('judul', 'Dashboard')</b>
        </div>
    </div>
    <div class="topbar-right">
        <a href="{{ route('notifikasi.index') }}" class="icon-btn" aria-label="Notifikasi">
            <i class="bi bi-bell"></i>
            @if ($notifBaru > 0)<span class="dot">{{ $notifBaru > 9 ? '9+' : $notifBaru }}</span>@endif
        </a>
        <div class="dropdown">
            <button type="button" class="user-btn" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar">{{ auth()->user()->inisial }}</span>
                <span class="name d-none d-sm-inline">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down small d-none d-sm-inline"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                <li><a class="dropdown-item" href="{{ route('mahasiswa.profil') }}"><i class="bi bi-person"></i> Profil Saya</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
