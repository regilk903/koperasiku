@extends('layouts.koperasi')
@section('title', 'Edit Anggota')
@section('content')
<div class="container py-4">
    <h3 class="mb-3">Edit Anggota</h3>
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('anggota.update', $anggota) }}" method="POST">@csrf @method('PUT') @include('anggota._form')</form>
    </div></div>
</div>
@endsection
