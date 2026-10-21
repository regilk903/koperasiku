@extends('layouts.koperasi')
@section('title', 'Tambah Anggota')
@section('content')
<div class="container py-4">
    <h3 class="mb-3">Tambah Anggota</h3>
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('anggota.store') }}" method="POST">@csrf @include('anggota._form')</form>
    </div></div>
</div>
@endsection
