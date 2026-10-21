@php $a = $anggota ?? null; $f = fn ($k, $d = '') => old($k, $a?->$k ?? $d); @endphp
<div class="row g-3">
    @foreach([['no_anggota','No. Anggota','text'],['nik','NIK (16 digit)','text'],['nama','Nama Lengkap','text'],
              ['tempat_lahir','Tempat Lahir','text'],['tanggal_lahir','Tanggal Lahir','date'],['no_hp','No. HP','text'],
              ['email','Email','email'],['tanggal_bergabung','Tanggal Bergabung','date']] as [$n, $l, $t])
        <div class="col-md-6">
            <label class="form-label">{{ $l }}</label>
            <input type="{{ $t }}" name="{{ $n }}" class="form-control @error($n) is-invalid @enderror"
                   value="{{ $t === 'date' ? old($n, $a?->$n?->format('Y-m-d')) : $f($n) }}"
                   @if(in_array($n, ['no_anggota','nik','nama','no_hp','tanggal_bergabung'])) required @endif>
            @error($n)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @endforeach
    <div class="col-md-6">
        <label class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-select" required>
            <option value="L" @selected($f('jenis_kelamin') === 'L')>Laki-laki</option>
            <option value="P" @selected($f('jenis_kelamin') === 'P')>Perempuan</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="aktif" @selected($f('status', 'aktif') === 'aktif')>Aktif</option>
            <option value="nonaktif" @selected($f('status') === 'nonaktif')>Nonaktif</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Akun Login (opsional)</label>
        <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
            <option value="">— tidak ada —</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" @selected((string) $f('user_id') === (string) $u->id)>{{ $u->name }} ({{ $u->email }})</option>
            @endforeach
        </select>
        @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" required>{{ $f('alamat') }}</textarea>
        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('anggota.index') }}" class="btn btn-outline-secondary">Batal</a>
</div>
