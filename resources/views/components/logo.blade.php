@props(['terang' => false, 'ukuran' => 30])
{{-- Logo MagangKu: tanda "M" + nama. Pakai: <x-logo /> atau <x-logo :terang="true" /> (untuk latar gelap) --}}
<span
    style="display:inline-flex;align-items:center;gap:.55rem;font-weight:800;font-size:1.2rem;letter-spacing:-.01em;color:{{ $terang ? '#fff' : '#0f2557' }};">
    <svg width="{{ $ukuran }}" height="{{ $ukuran }}" viewBox="0 0 32 32" aria-hidden="true">
        <rect width="32" height="32" rx="8" fill="#1d4ed8" />
        <path d="M7 23V9.5l4.2 0 4.8 8 4.8-8H25V23h-3.6v-8.2l-3.9 6.4h-3l-3.9-6.4V23H7z" fill="#fff" />
    </svg>
    <span>Magang<span style="color:{{ $terang ? '#8fb3ff' : '#1d4ed8' }};">Ku</span></span>
</span>
