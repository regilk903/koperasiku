@extends('layouts.koperasi')
@section('title', 'Data Anggota')
@section('content')
@php $kelola = in_array(auth()->user()->role, ['admin', 'pengurus']); @endphp
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="mb-0">Data Anggota</h3>
        @if($kelola)<a href="{{ route('anggota.create') }}" class="btn btn-primary">+ Tambah Anggota</a>@endif
    </div>
    <div class="card shadow-sm"><div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5"><input type="text" name="search" class="form-control" placeholder="Cari nama atau no. anggota" value="{{ request('search') }}"></div>
            <div class="col-auto"><button class="btn btn-primary">Cari</button> <a href="{{ route('anggota.index') }}" class="btn btn-outline-secondary">Reset</a></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-primary"><tr><th>No. Anggota</th><th>Nama</th><th>No. HP</th><th>Bergabung</th><th>Status</th>@if($kelola)<th>Aksi</th>@endif</tr></thead>
                <tbody>
                @forelse($anggota as $a)
                    <tr>
                        <td>{{ $a->no_anggota }}</td><td>{{ $a->nama }}</td><td>{{ $a->no_hp }}</td>
                        <td>{{ $a->tanggal_bergabung->format('d-m-Y') }}</td>
                        <td><span class="badge text-bg-{{ $a->status === 'aktif' ? 'success' : 'secondary' }}">{{ ucfirst($a->status) }}</span></td>
                        @if($kelola)
                        <td><div class="d-flex gap-1">
                            <a href="{{ route('anggota.edit', $a) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('anggota.destroy', $a) }}" method="POST" onsubmit="return confirm('Hapus anggota ini?')">
                                @csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div></td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">Belum ada data anggota.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $anggota->links() }}
    </div></div>
</div>
@endsection
