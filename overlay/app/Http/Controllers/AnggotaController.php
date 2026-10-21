<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $anggota = Anggota::query()
            ->when($request->user()->role === 'anggota', fn ($q) => $q->where('user_id', $request->user()->id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $k = $request->search;
                $q->where(fn ($w) => $w->where('nama', 'like', "%{$k}%")->orWhere('no_anggota', 'like', "%{$k}%"));
            })
            ->orderBy('nama')->paginate(10)->withQueryString();

        return view('anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('anggota.create', ['users' => $this->userOptions()]);
    }

    public function store(Request $request)
    {
        Anggota::create($this->validated($request));

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', ['anggota' => $anggota, 'users' => $this->userOptions()]);
    }

    public function update(Request $request, Anggota $anggota)
    {
        $anggota->update($this->validated($request, $anggota));

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        if ($anggota->simpanan()->exists() || $anggota->pinjaman()->exists()) {
            return back()->with('error', 'Anggota memiliki transaksi. Ubah status menjadi nonaktif.');
        }
        $anggota->delete();

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }

    private function userOptions()
    {
        return User::where('role', 'anggota')->orderBy('name')->get();
    }

    private function validated(Request $request, ?Anggota $a = null): array
    {
        $id = $a?->id;

        return $request->validate([
            'user_id' => ['nullable', 'exists:users,id', Rule::unique('anggota', 'user_id')->ignore($id)],
            'no_anggota' => ['required', 'string', 'max:30', Rule::unique('anggota', 'no_anggota')->ignore($id)],
            'nik' => ['required', 'digits:16', Rule::unique('anggota', 'nik')->ignore($id)],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['required', 'string'],
            'no_hp' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', Rule::unique('anggota', 'email')->ignore($id)],
            'tanggal_bergabung' => ['required', 'date'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);
    }
}
