@extends('auth.auth-master')

@section('auth-main')
<form action="buatakun" method="POST">
    @csrf
    <div class="row mt-1">
        <div class="col-md-6 col-12">
            <div class="form-group position-relative has-icon-left mb-4">
                <input name="nama" type="text" class="form-control form-control-xl @error('nama') is-invalid @enderror" placeholder="Nama Lengkap"
                    autocomplete="off" value="{{ old('nama') }}" required />
                <div class="form-control-icon">
                    <i class="bi bi-person"></i>
                </div>
                 @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group position-relative has-icon-left mb-4">
                <input name="alamat" type="text" class="form-control form-control-xl @error('alamat') is-invalid @enderror" placeholder="Alamat"
                    autocomplete="off" value="{{ old('alamat') }}" required />
                <div class="form-control-icon">
                    <i class="bi bi-journal-text"></i>
                </div>
                 @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group position-relative has-icon-left mb-4">
                <input name="no_hp" type="text" class="form-control form-control-xl @error('no_hp') is-invalid @enderror" placeholder="No. WhatsApp"
                    autocomplete="off" value="{{ old('no_hp') }}" required />
                <div class="form-control-icon">
                    <i class="bi bi-whatsapp"></i>
                </div>
                 @error('no_hp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group position-relative has-icon-left mb-4">
                <input name="email" type="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Email"
                    autocomplete="off" value="{{ old('email') }}" required />
                <div class="form-control-icon">
                    <i class="bi bi-envelope"></i>
                </div>
                 @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Selector Jabatan ditambahkan disini --}}
        <div class="col-md-12 col-12">
            <div class="form-group position-relative has-icon-left mb-4">
                <select name="id_jabatan" class="form-control form-control-xl @error('id_jabatan') is-invalid @enderror" required>
                    <option value="" disabled selected>Pilih Jabatan</option>
                    @if(isset($jabatan))
                        @foreach ($jabatan as $j)
                            <option value="{{ $j->id }}" {{ old('id_jabatan') == $j->id ? 'selected' : '' }}>
                                {{ $j->nama_jabatan }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <div class="form-control-icon">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                @error('id_jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Akhir Selector Jabatan --}}

        <div class="col-md-6 col-12">
            <div class="form-group position-relative has-icon-left mb-4">
                <input name="password" type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" placeholder="Password" required />
                <div class="form-control-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group position-relative has-icon-left mb-4">
                {{-- Mengganti nama menjadi password_confirmation untuk validasi Laravel --}}
                <input name="password_confirmation" type="password" class="form-control form-control-xl"
                    placeholder="Ketik Ulang Password" required />
                <div class="form-control-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-5 mx-auto mt-3">
        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-2">
            Daftar
        </button>
    </div>
    <div class="text-center mt-3 text-lg fs-4">
        <p class="text-gray">
            Sudah Punya Akun?
            <a href="{{asset('login')}}" class="font-bold">Masuk</a>.
        </p>
    </div>
</form>
@endsection
