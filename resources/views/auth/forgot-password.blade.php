@extends('auth.auth-master')
@section('auth-main')
<form class="mt-5" action="{{asset('')}}" method="POST">
    @csrf
    <div class="col-md-7 col-12 mx-auto">
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="text" class="form-control form-control-xl" placeholder="Email / No.WA" />
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-12 mx-auto">
        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-3" type="submit">
            Kirim
        </button>
    </div>
    <div class="text-center mt-5 text-lg fs-4">
        <p class="text-gray">
            Kembali Ke Halaman
            <a href="{{asset('login')}}" class="font-bold">Masuk</a>
        </p>
    </div>
</form>
@endsection