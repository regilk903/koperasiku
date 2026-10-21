@extends('layouts.koperasi')
@section('title', 'Simpanan')
@section('content')
@php $kelola = in_array(auth()->user()->role, ['admin', 'bendahara']); @endphp
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="mb-0">Simpanan</h3>
        @if($kelola)<a href="{{ route('simpanan.create') }}" class="btn btn-primary">+ Catat Simpanan</a>@endif
    </div>
    <div class="card shadow-sm"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-primary"><tr><th>Tanggal</th><th>Anggota</th><th>Jenis</th><th class="text-end">Nominal (Rp)</th><th>Status</th><th>Dicatat</th><th>Diverifikasi</th>@if($kelola)<th>Aksi</th>@endif</tr></thead>
                <tbody>
                @forelse($simpanan as $s)
                    <tr>
                        <td>{{ $s->tanggal_transaksi->format('d-m-Y H:i') }}</td>
                        <td>{{ $s->anggota->nama }}</td>
                        <td>{{ ucfirst($s->jenis) }}</td>
                        <td class="text-end">{{ number_format($s->nominal, 2, ',', '.') }}</td>
                        <td><span class="badge text-bg-{{ $s->status === 'terverifikasi' ? 'success' : 'warning' }}">{{ ucfirst($s->status) }}</span></td>
                        <td>{{ $s->pembuat?->name ?? '-' }}</td>
                        <td>{{ $s->verifikator?->name ?? '-' }}</td>
                        @if($kelola)
                        <td>@if($s->status === 'menunggu')
                            <form action="{{ route('simpanan.verifikasi', $s) }}" method="POST">@csrf @method('PATCH')
                                <button class="btn btn-sm btn-success">Verifikasi</button></form>
                        @endif</td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4">Belum ada data simpanan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $simpanan->links() }}
    </div></div>
</div>
@endsection
