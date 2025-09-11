<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gaji;
use App\Models\User;

class GajiController extends Controller
{
    public function show($id)
{
    $gaji = \App\Models\Gaji::with('user')->findOrFail($id);
    return view('admin.gaji.show', compact('gaji'));
}

public function rekap(Request $request)
{
    $bulan = $request->input('bulan', now()->month);
    $tahun = $request->input('tahun', now()->year);

    $users = User::with(['division', 'jobTitle'])->get();

    $rekap = [];

    foreach ($users as $user) {
        // ambil absensi user untuk bulan/tahun
        $attendances = \App\Models\Attendance::where('user_id', $user->id)
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->get();

        $hariKerja   = 0;
        $terlambat   = 0;
        $pulangCepat = 0;
        $potongan    = 0;

        // ambil aturan dari divisi (fallback jika null)
        $gajiPerHari = $user->division->gaji_per_hari ?? 0;
        $tunjangan   = $user->division->tunjangan ?? 0;
        // potongan per 15 menit (bisa disimpan di divisions, default Rp5.000)
        $potonganPer15 = $user->division->potongan_per_15min ?? 5000;

        foreach ($attendances as $absen) {
            // anggap setiap baris attendance = hadir
            $hariKerja++;

            // cek time_in (hindari null)
            if (!empty($absen->time_in) && $absen->time_in > '07:45:00') {
                $menitTelat = (int) floor((strtotime($absen->time_in) - strtotime('07:45:00')) / 60);
                if ($menitTelat > 0) {
                    $potongan += ceil($menitTelat / 15) * $potonganPer15;
                    $terlambat++;
                }
            }

            // cek time_out (hindari null)
            if (!empty($absen->time_out) && $absen->time_out < '16:45:00') {
                $menitCepat = (int) floor((strtotime('16:45:00') - strtotime($absen->time_out)) / 60);
                if ($menitCepat > 0) {
                    $potongan += ceil($menitCepat / 15) * $potonganPer15;
                    $pulangCepat++;
                }
            }
        }

        $gajiPokok = $hariKerja * $gajiPerHari;
        $totalGaji = $gajiPokok + $tunjangan - $potongan;
        if ($totalGaji < 0) $totalGaji = 0;

        $rekap[] = [
            'nama'        => $user->name,
            'divisi'      => $user->division->name ?? '-',
            'hari_kerja'  => $hariKerja,
            'terlambat'   => $terlambat,
            'pulang_cepat'=> $pulangCepat,
            'gaji_pokok'  => $gajiPokok,
            'potongan'    => $potongan,
            'tunjangan'   => $tunjangan,
            'total_gaji'  => $totalGaji,
        ];
    }

    return view('admin.gaji.rekap', compact('rekap', 'bulan', 'tahun'));
}

    // index: kirim $gaji ke view
    public function index()
    {
        // ambil semua data gaji termasuk relasi user
        $gaji = Gaji::with('user')->orderBy('created_at','desc')->get();

        // pastikan view yang dipanggil sesuai: resources/views/admin/gaji/index.blade.php
        return view('admin.gaji.index', compact('gaji'));
    }

    // show form tambah
    public function create()
    {
        // kirim daftar users untuk dropdown
        $users = User::orderBy('name')->get();
        return view('admin.gaji.create', compact('users'));
    }

    // simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required',
            'periode'    => 'required|string',
            'total_hari' => 'nullable|integer',
            'gaji_pokok' => 'required|numeric',
            'tunjangan'  => 'nullable|numeric',
            'potongan'   => 'nullable|numeric',
        ]);

        $tunjangan = $request->input('tunjangan', 0);
        $potongan  = $request->input('potongan', 0);
        $total = $request->input('gaji_pokok') + $tunjangan - $potongan;

        Gaji::create([
            'user_id'    => $request->input('user_id'),
            'periode'    => $request->input('periode'),
            'total_hari' => $request->input('total_hari', 0),
            'gaji_pokok' => $request->input('gaji_pokok'),
            'tunjangan'  => $tunjangan,
            'potongan'   => $potongan,
            'total_gaji' => $total,
        ]);

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil disimpan.');
    }

    // show form edit
    public function edit($id)
    {
        $gaji = Gaji::findOrFail($id);
        $users = User::orderBy('name')->get();
        return view('admin.gaji.edit', compact('gaji','users'));
    }

    // update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'    => 'required',
            'periode'    => 'required|string',
            'total_hari' => 'nullable|integer',
            'gaji_pokok' => 'required|numeric',
            'tunjangan'  => 'nullable|numeric',
            'potongan'   => 'nullable|numeric',
        ]);

        $gaji = Gaji::findOrFail($id);

        $tunjangan = $request->input('tunjangan', 0);
        $potongan  = $request->input('potongan', 0);
        $total = $request->input('gaji_pokok') + $tunjangan - $potongan;

        $gaji->update([
            'user_id'    => $request->input('user_id'),
            'periode'    => $request->input('periode'),
            'total_hari' => $request->input('total_hari', 0),
            'gaji_pokok' => $request->input('gaji_pokok'),
            'tunjangan'  => $tunjangan,
            'potongan'   => $potongan,
            'total_gaji' => $total,
        ]);

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil diupdate.');
    }

    // hapus data
    public function destroy($id)
    {
        $gaji = Gaji::findOrFail($id);
        $gaji->delete();

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil dihapus.');
    }

    // (opsional) cetak slip
    public function slip($id)
    {
        $gaji = Gaji::with('user')->findOrFail($id);
        // contoh jika pakai barryvdh/laravel-dompdf
        // $pdf = \PDF::loadView('admin.gaji.slip', compact('gaji'));
        // return $pdf->download('slip-gaji-'.$gaji->user->name.'.pdf');
        return view('admin.gaji.slip', compact('gaji')); // sementara tampilkan view
    }
}