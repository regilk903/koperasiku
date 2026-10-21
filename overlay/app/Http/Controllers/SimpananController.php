<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SimpananController extends Controller
{
    public function index(Request $request)
    {
        $simpanan = Simpanan::with(['anggota', 'pembuat', 'verifikator'])
            ->when($request->user()->role === 'anggota',
                fn ($q) => $q->whereHas('anggota', fn ($a) => $a->where('user_id', $request->user()->id)))
            ->latest('tanggal_transaksi')->paginate(10)->withQueryString();

        return view('simpanan.index', compact('simpanan'));
    }

    public function create()
    {
        return view('simpanan.create', ['anggota' => Anggota::where('status', 'aktif')->orderBy('nama')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'anggota_id' => ['required', Rule::exists('anggota', 'id')->where('status', 'aktif')],
            'jenis' => ['required', Rule::in(['pokok', 'wajib', 'sukarela'])],
            'nominal' => ['required', 'numeric', 'min:1', 'max:9999999999999'],
            'tanggal_transaksi' => ['required', 'date'],
        ]);

        $simpanan = new Simpanan($data);
        $simpanan->dibuat_oleh = $request->user()->id;
        $simpanan->status = 'menunggu';
        $simpanan->save();

        return redirect()->route('simpanan.index')->with('success', 'Simpanan dicatat, menunggu verifikasi.');
    }

    public function verifikasi(Request $request, Simpanan $simpanan)
    {
        if ($simpanan->status !== 'menunggu') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }
        $simpanan->status = 'terverifikasi';
        $simpanan->diverifikasi_oleh = $request->user()->id;
        $simpanan->save();

        return back()->with('success', 'Simpanan terverifikasi.');
    }
}
