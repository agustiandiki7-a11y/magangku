{{-- Pagination. Pakai: <x-pagination :paginator="$daftar" />  (tampilannya ada di vendor/pagination/bootstrap-5.blade.php) --}}
@props(['paginator'])
{{ $paginator->links('pagination::bootstrap-5') }}
