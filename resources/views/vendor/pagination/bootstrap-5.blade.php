{{--
    Tampilan pagination MagangKu.

    File ini SENGAJA memakai nama "bootstrap-5" supaya menimpa view bawaan Laravel dengan nama sama.
    Jadi semua pemanggilan {{ $daftar->links('pagination::bootstrap-5') }} di project otomatis
    memakai tampilan ini (bahasa Indonesia, tanpa memuat CSS Bootstrap). Gayanya ada di
    public/assets/css/app.css bagian "Pagination".
--}}
@if ($paginator->hasPages())
    <nav class="pager" role="navigation" aria-label="Navigasi halaman">
        <p class="pager-info">
            Menampilkan <b>{{ $paginator->firstItem() }}</b>–<b>{{ $paginator->lastItem() }}</b>
            dari <b>{{ $paginator->total() }}</b> data
        </p>

        <ul class="pagination">
            {{-- Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true"><span aria-hidden="true">&lsaquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">&lsaquo;</a></li>
            @endif

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" aria-label="Ke halaman {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya">&rsaquo;</a></li>
            @else
                <li class="disabled" aria-disabled="true"><span aria-hidden="true">&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
