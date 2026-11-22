<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

class PenilaianController extends Controller
{
    public function index()
    {
        $penempatan = auth()->user()->mahasiswa->penempatan()
            ->with(['penilaian.mentor.user', 'lamaran.lowongan.perusahaan'])->latest()->get();

        return view('mahasiswa.penilaian', ['daftar' => $penempatan]);
    }
}
