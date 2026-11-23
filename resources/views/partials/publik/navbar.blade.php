<header class="public-nav">
    <div class="inner" style="position:relative">
        <a href="{{ route('beranda') }}"><x-logo /></a>
        <button type="button" class="icon-btn public-toggle" aria-label="Menu"><i class="bi bi-list"></i></button>
        <nav class="public-links">
            <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('informasi') }}" class="{{ request()->routeIs('informasi') ? 'active' : '' }}">Informasi</a>
            <a href="{{ route('lowongan') }}" class="{{ request()->routeIs('lowongan*') ? 'active' : '' }}">Lowongan</a>
            <a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'active' : '' }}">Tentang</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-sm">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm">Masuk</a>
            @endauth
        </nav>
    </div>
</header>
