<header class="topbar">
    <div class="topbar-left">
        <button type="button" class="icon-btn menu-toggle" data-toggle-menu aria-label="Buka menu"><i class="bi bi-list"></i></button>
        <div class="crumb">
            <span>Admin</span><i class="bi bi-chevron-right"></i><b>@yield('judul', 'Dashboard')</b>
        </div>
    </div>
    <div class="topbar-right">
        <span class="topbar-date">{{ now()->translatedFormat('l, d F Y') }}</span>
        <div class="user-menu">
            <button type="button" class="user-btn" data-dropdown="menuAdmin">
                <span class="avatar">{{ auth()->user()->inisial }}</span>
                <span class="name">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <div class="dropdown" id="menuAdmin">
                <div class="head">{{ auth()->user()->email }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"><i class="bi bi-box-arrow-left"></i> Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>
