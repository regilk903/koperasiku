<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\PembayaranAngsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AngsuranController extends Controller
{
    /**
     * Menampilkan daftar angsuran.
     */
    public function index()
    {
        // Semua data: dipakai hanya untuk menghitung ringkasan
        $semuaAngsuran = Angsuran::with([
            'pinjaman.anggota',
            'pembayarans',
        ])->orderBy('jatuh_tempo')->get();

        $totalTagihan = $semuaAngsuran->sum('total_tagihan');
        $totalDibayar = $semuaAngsuran->sum('total_dibayar');
        $sisaPiutang = max(0, $totalTagihan - $totalDibayar);

        $jumlahAngsuran = $semuaAngsuran->count();

        // Menghitung status angsuran
        $jumlahLunas = $semuaAngsuran->where('status', 'lunas')->count();

        $belumBayar = $semuaAngsuran->filter(function ($angsuran) {
            return (float) $angsuran->total_dibayar <= 0;
        })->count();

        $sebagian = $semuaAngsuran->filter(function ($angsuran) {
            return (float) $angsuran->total_dibayar > 0
                && $angsuran->status !== 'lunas';
        })->count();

        $tunggakan = $semuaAngsuran->filter(function ($angsuran) {
            return $angsuran->status !== 'lunas'
                && $angsuran->jatuh_tempo
                && $angsuran->jatuh_tempo->isPast();
        })->count();

        // Data untuk tabel: dipaginasi supaya $angsurans->links() bisa dipakai
        $angsurans = Angsuran::with([
            'pinjaman.anggota',
            'pembayarans',
        ])
            ->orderBy('jatuh_tempo')
            ->paginate(10)
            ->withQueryString();

        return view('angsuran.index', compact(
            'angsurans',
            'totalTagihan',
            'totalDibayar',
            'sisaPiutang',
            'jumlahAngsuran',
            'jumlahLunas',
            'belumBayar',
            'sebagian',
            'tunggakan'
        ));
    }

    /**
     * Menampilkan detail angsuran.
     */
    public function show(Angsuran $angsuran)
    {
        $angsuran->load([
            'pinjaman.anggota',
            'pembayarans.petugas',
        ]);

        return view('angsuran.show', [
            'angsuran' => $angsuran,
        ]);
    }

    /**
     * Memproses pembayaran angsuran.
     */
    public function pay(Request $request, Angsuran $angsuran)
    {
        $validated = $request->validate([
            'jumlah_bayar' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'tanggal_bayar' => [
                'required',
                'date',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $angsuran,
            $request
        ) {
            // Mengunci data angsuran selama pembayaran
            $data = Angsuran::whereKey($angsuran->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Konversi pembayaran ke satuan sen
            $pembayaran = (int) round(
                (float) $validated['jumlah_bayar'] * 100
            );

            // Menghitung total tagihan
            $totalTagihan = (int) round(
                (float) $data->total_tagihan * 100
            );

            // Menghitung total yang sudah dibayar
            $totalSudahDibayar = (int) round(
                (float) $data->total_dibayar * 100
            );

            // Menghitung sisa tagihan
            $sisaTagihan = max(
                0,
                $totalTagihan - $totalSudahDibayar
            );

            // Memeriksa pembayaran
            if ($pembayaran > $sisaTagihan) {
                throw ValidationException::withMessages([
                    'jumlah_bayar' =>
                        'Pembayaran melebihi sisa tagihan.',
                ]);
            }

            // Menghitung bunga yang sudah dibayar
            $bungaSudahDibayar = (int) round(
                (float) $data->pembayarans()
                    ->sum('bunga_dibayar') * 100
            );

            // Menghitung total bunga tagihan
            $bungaTagihan = (int) round(
                (float) $data->bunga * 100
            );

            // Menghitung sisa bunga
            $sisaBunga = max(
                0,
                $bungaTagihan - $bungaSudahDibayar
            );

            // Menghitung pembayaran bunga
            $pembayaranBunga = min(
                $pembayaran,
                $sisaBunga
            );

            // Menghitung pembayaran pokok
            $pembayaranPokok = $pembayaran
                - $pembayaranBunga;

            // Konversi kembali ke desimal
            $jumlahBayarDecimal = $pembayaran / 100;
            $bungaDecimal = $pembayaranBunga / 100;
            $pokokDecimal = $pembayaranPokok / 100;

            // Menyimpan pembayaran
            PembayaranAngsuran::create([
                'angsuran_id' => $data->id,

                'kode_pembayaran' => 'BYR-'
                    . now()->format('YmdHis')
                    . '-'
                    . strtoupper(Str::random(4)),

                'tanggal_bayar' => $validated['tanggal_bayar'],

                'jumlah_bayar' => $jumlahBayarDecimal,

                'dibayar_oleh' => $request->user()->id,

                'keterangan' => $validated['keterangan'] ?? null,

                'pokok_dibayar' => $pokokDecimal,

                'bunga_dibayar' => $bungaDecimal,
            ]);

            // Memperbarui total pembayaran
            $totalDibayarBaru = $totalSudahDibayar
                + $pembayaran;

            $data->total_dibayar = $totalDibayarBaru / 100;

            // Memperbarui status angsuran
            if ($totalDibayarBaru >= $totalTagihan) {
                $data->status = 'lunas';
                $data->paid_at = now();
            }

            $data->save();
        });

        return redirect()
            ->route('angsuran.show', $angsuran->id)
            ->with(
                'success',
                'Pembayaran angsuran berhasil disimpan.'
            );
    }
}