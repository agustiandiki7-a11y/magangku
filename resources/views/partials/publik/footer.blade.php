<footer class="public-foot">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <x-logo :terang="true" :ukuran="30" />
                <p class="mt-3 mb-0 small" style="max-width:380px">Sistem Informasi Pengajuan dan Pengelolaan Magang Mahasiswa. Cari tempat magang, kirim lamaran, isi logbook, dan lihat penilaian dalam satu tempat.</p>
            </div>
            <div class="col-6 col-lg-3 offset-lg-1">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="{{ route('beranda') }}">Beranda</a></li>
                    <li><a href="{{ route('informasi') }}">Informasi</a></li>
                    <li><a href="{{ route('lowongan') }}">Lowongan</a></li>
                    <li><a href="{{ route('tentang') }}">Tentang</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h4>Akun</h4>
                <ul>
                    @auth
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Masuk</a></li>
                        <li><a href="{{ route('register') }}">Daftar Mahasiswa</a></li>
                    @endauth
                </ul>
            </div>
        </div>
        <div class="copy">&copy; {{ date('Y') }} MagangKu. Sistem Informasi Pengajuan dan Pengelolaan Magang.</div>
    </div>
</footer>
