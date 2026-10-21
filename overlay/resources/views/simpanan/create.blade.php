@extends('layouts.koperasi')
@section('title', 'Catat Simpanan')
@section('content')
<div class="container py-4">
    <h3 class="mb-3">Catat Simpanan</h3>
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('simpanan.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Anggota</label>
                    <select name="anggota_id" class="form-select @error('anggota_id') is-invalid @enderror" required>
                        <option value="">— pilih —</option>
                        @foreach($anggota as $a)
                            <option value="{{ $a->id }}" @selected((string) old('anggota_id') === (string) $a->id)>{{ $a->no_anggota }} — {{ $a->nama }}</option>
                        @endforeach
                    </select>
                    @error('anggota_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        @foreach(['pokok', 'wajib', 'sukarela'] as $j)
                            <option value="{{ $j }}" @selected(old('jenis') === $j)>{{ ucfirst($j) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nominal (Rp)</label>
                    <input type="number" step="0.01" min="1" name="nominal" value="{{ old('nominal') }}" class="form-control @error('nominal') is-invalid @enderror" required>
                    @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Transaksi</label>
                    <input type="datetime-local" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', now()->format('Y-m-d\TH:i')) }}" class="form-control @error('tanggal_transaksi') is-invalid @enderror" required>
                    @error('tanggal_transaksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('simpanan.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</div>
@endsection
