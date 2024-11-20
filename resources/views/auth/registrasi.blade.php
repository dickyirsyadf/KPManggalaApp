@extends('auth.auth-master')
@section('auth-main')
<form action="buatakun" method="POST">
    @csrf
    <div class="row mt-1">
        <div class="col-md-6 col-12">
            <div class="form-group position-relative has-icon-left mb-4">
                <input name="nama" type="text" class="form-control form-control-xl" placeholder="Nama Lengkap"
                    autocomplete="off" />
                <div class="form-control-icon">
                    <i class="bi bi-person"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group position-relative has-icon-left mb-4">
                <input name="alamat" type="text" class="form-control form-control-xl" placeholder="Alamat"
                    autocomplete="off" />
                <div class="form-control-icon">
                    <i class="bi bi-journal-text"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group position-relative has-icon-left mb-4">
                <input name="no_hp" type="text" class="form-control form-control-xl" placeholder="No. WhatsApp"
                    autocomplete="off" />
                <div class="form-control-icon">
                    <i class="bi bi-whatsapp"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group position-relative has-icon-left mb-4">
                <input name="email" type="text" class="form-control form-control-xl" placeholder="Email"
                    autocomplete="off" />
                <div class="form-control-icon">
                    <i class="bi bi-envelope"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 ">
            <div class="form-group position-relative has-icon-left mb-4">
                <input name="password" type="password" class="form-control form-control-xl" placeholder="Password" />
                <div class="form-control-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 ">
            <div class="form-group position-relative has-icon-left mb-4">
                <input name="password1" type="password" class="form-control form-control-xl"
                    placeholder="Ketik Ulang Password" />
                <div class="form-control-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
        </div>
    </diV>
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