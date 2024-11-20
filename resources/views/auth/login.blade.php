@extends('auth.auth-master')
@section('auth-main')

<form class="mt-2" action="{{asset('login')}}" method="POST">
    @csrf
    <div class="col-md-7 col-12 mx-auto">
        <div class="form-group position-relative has-icon-left mb-4">
            <input name="email" type="text" class="form-control form-control-xl" placeholder="Email"
                autocomplete="off" />
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-12 mx-auto">
        <div class="form-group position-relative has-icon-left mb-4">
            <input name="password" type="password" class="form-control form-control-xl" placeholder="Kata Sandi" />
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-12 mx-auto">
        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-3" type="submit">
            Masuk
        </button>
    </div>
    <div class="text-center mt-5 text-lg fs-4">
        <p class="text-gray">
            Tidak Punya Akun?
            <a href="{{asset('registrasi')}}" class="font-bold">Daftar</a>
        </p>
        <p>
            <a class="font-bold" href="{{asset('forgot-password')}}">Lupa Password?</a>
        </p>
    </div>
</form>

@endsection