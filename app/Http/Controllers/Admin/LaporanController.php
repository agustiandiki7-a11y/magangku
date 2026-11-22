<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Logbook;
use App\Models\Penempatan;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->kueriLamaran($request);

        return view('admin.laporan', [
            'daftar' => (clone $query)->latest()->paginate(15)->withQueryString(),
            'ringkas' => (clone $query)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'penempatanAktif' => Penempatan::where('status', 'aktif')->count(),
            'penempatanSelesai' => Penempatan::where('status', 'selesai')->count(),
            'totalLogbook' => Logbook::count(),
            'logbookMenunggu' => Logbook::where('status', 'menunggu')->count(),
            'rataNilai' => Penilaian::avg('nilai_akhir'),
            'dari' => $request->query('dari'),
            'sampai' => $request->query('sampai'),
            'status' => $request->query('status'),
        ]);
    }

    /** Unduh laporan lamaran sebagai CSV (bisa dibuka di Excel). */
    public function ekspor(Request $request)
    {
        $baris = $this->kueriLamaran($request)->latest()->get();

        return response()->streamDownload(function () use ($baris) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
            fputcsv($out, ['Tanggal', 'Mahasiswa', 'NIM', 'Lowongan', 'Perusahaan', 'Status']);
            foreach ($baris as $l) {
                fputcsv($out, [
                    $l->created_at->format('d/m/Y'), $l->mahasiswa->user->name, $l->mahasiswa->nim,
                    $l->lowongan->judul, $l->lowongan->perusahaan->nama, $l->status,
                ]);
            }
            fclose($out);
        }, 'laporan-lamaran-' . now()->format('Ymd') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function kueriLamaran(Request $request)
    {
        $query = Lamaran::with(['mahasiswa.user', 'lowongan.perusahaan']);

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->query('dari'));
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->query('sampai'));
        }
        if (in_array($request->query('status'), ['diajukan', 'diproses', 'diterima', 'ditolak'], true)) {
            $query->where('status', $request->query('status'));
        }

        return $query;
    }
}
