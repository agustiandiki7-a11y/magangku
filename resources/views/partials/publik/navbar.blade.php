<header class="public-nav">
    <nav class="navbar navbar-expand-lg p-0">
        <div class="container">
            <a href="{{ route('beranda') }}" class="navbar-brand m-0"><x-logo /></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPublik" aria-controls="menuPublik" aria-expanded="false" aria-label="Menu">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="collapse navbar-collapse" id="menuPublik">
                <ul class="navbar-nav mx-lg-auto gap-lg-1 py-2 py-lg-0">
                    <li class="nav-item"><a href="{{ route('beranda') }}" class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}">Beranda</a></li>
                    <li class="nav-item"><a href="{{ route('informasi') }}" class="nav-link {{ request()->routeIs('informasi') ? 'active' : '' }}">Informasi</a></li>
                    <li class="nav-item"><a href="{{ route('lowongan') }}" class="nav-link {{ request()->routeIs('lowongan*') ? 'active' : '' }}">Lowongan</a></li>
                    <li class="nav-item"><a href="{{ route('tentang') }}" class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}">Tentang</a></li>
                </ul>
                <div class="d-flex gap-2 pb-2 pb-lg-0">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
