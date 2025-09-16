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
            $attendances = \App\Models\Attendance::where('user_id', $user->id)
                ->whereMonth('date', $bulan)
                ->whereYear('date', $tahun)
                ->get();

            $hariKerja   = 0;
            $terlambat   = 0;
            $pulangCepat = 0;
            $potongan    = 0;

            $gajiPerHari   = $user->division->gaji_per_hari ?? 0;
            $tunjangan     = $user->division->tunjangan ?? 0;
            $potonganPer15 = $user->division->potongan_per_15min ?? 5000;

            foreach ($attendances as $absen) {
                $hariKerja++;

                if (!empty($absen->time_in) && $absen->time_in > '07:45:00') {
                    $menitTelat = (int) floor((strtotime($absen->time_in) - strtotime('07:45:00')) / 60);
                    if ($menitTelat > 0) {
                        $potongan += ceil($menitTelat / 15) * $potonganPer15;
                        $terlambat++;
                    }
                }

                if (!empty($absen->time_out) && $absen->time_out < '16:45:00') {
                    $menitCepat = (int) floor((strtotime('16:45:00') - strtotime($absen->time_out)) / 60);
                    if ($menitCepat > 0) {
                        $potongan += ceil($menitCepat / 15) * $potonganPer15;
                        $pulangCepat++;
                    }
                }
            }

            $gajiPokok = $hariKerja * $gajiPerHari;
            $totalGaji = $gajiPokok + $tunjangan - $potongan; // bisa minus

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

    public function index()
    {
        $gaji = Gaji::with('user')->orderBy('created_at','desc')->get();
        return view('admin.gaji.index', compact('gaji'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.gaji.create', compact('users'));
    }

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
        $total     = $request->input('gaji_pokok') + $tunjangan - $potongan; // bisa minus

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

    public function edit($id)
    {
        $gaji = Gaji::findOrFail($id);
        $users = User::orderBy('name')->get();
        return view('admin.gaji.edit', compact('gaji','users'));
    }

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
        $total     = $request->input('gaji_pokok') + $tunjangan - $potongan; // bisa minus

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

    public function destroy($id)
    {
        $gaji = Gaji::findOrFail($id);
        $gaji->delete();

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil dihapus.');
    }

    public function slip($id)
    {
        $gaji = Gaji::with('user')->findOrFail($id);
        return view('admin.gaji.slip', compact('gaji'));
    }

    public function generate(Request $request)
    {
        $bulan = now()->month;
        $tahun = now()->year;

        $users = User::with(['division'])->get();

        foreach ($users as $user) {
            $cek = Gaji::where('user_id', $user->id)
                ->where('periode', strtoupper(now()->format('F Y')))
                ->first();

            if ($cek) continue;

            $attendances = \App\Models\Attendance::where('user_id', $user->id)
                ->whereMonth('date', $bulan)
                ->whereYear('date', $tahun)
                ->get();

            $hariKerja   = 0;
            $terlambat   = 0;
            $pulangCepat = 0;
            $potongan    = 0;

            $gajiPerHari   = $user->division->gaji_per_hari ?? 0;
            $tunjangan     = $user->division->tunjangan ?? 0;
            $potonganPer15 = $user->division->potongan_per_15min ?? 5000;

            foreach ($attendances as $absen) {
                $hariKerja++;

                if (!empty($absen->time_in) && $absen->time_in > '07:45:00') {
                    $menitTelat = (int) floor((strtotime($absen->time_in) - strtotime('07:45:00')) / 60);
                    if ($menitTelat > 0) {
                        $potongan += ceil($menitTelat / 15) * $potonganPer15;
                        $terlambat++;
                    }
                }

                if (!empty($absen->time_out) && $absen->time_out < '16:45:00') {
                    $menitCepat = (int) floor((strtotime('16:45:00') - strtotime($absen->time_out)) / 60);
                    if ($menitCepat > 0) {
                        $potongan += ceil($menitCepat / 15) * $potonganPer15;
                        $pulangCepat++;
                    }
                }
            }

            $gajiPokok = $hariKerja * $gajiPerHari;
            $totalGaji = $gajiPokok + $tunjangan - $potongan; // bisa minus

            Gaji::create([
                'user_id'    => $user->id,
                'periode'    => strtoupper(now()->format('F Y')),
                'total_hari' => $hariKerja,
                'gaji_pokok' => $gajiPokok,
                'tunjangan'  => $tunjangan,
                'potongan'   => $potongan,
                'total_gaji' => $totalGaji,
            ]);
        }

        return redirect()->route('admin.gaji.index')->with('success', 'Gaji bulan ini berhasil digenerate.');
    }

    public function updatePersentase(Request $request)
{
    $request->validate([
        'persentase' => 'required|integer|min:0|max:100',
        'periode'    => 'required|string'
    ]);

    // Update persentase di periode tertentu
    $gaji = Gaji::where('periode', $request->periode)->get();

    foreach ($gaji as $row) {
        $row->persentase = $request->persentase;
        $row->total_gaji = ($row->gaji_pokok + $row->tunjangan - $row->potongan) * ($request->persentase / 100);
        $row->save();
    }

    return redirect()->back()->with('success', 'Persentase gaji berhasil diperbarui!');
}

}
