@extends('superadmin.layouts.superadmin-master')
@section('admin-master')
<div class="page-heading ">
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 ">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body px-8 py-4-5">
                            <h3 style="text-align: center">Selamat Datang di Mangony APP !</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                {{-- Penjualan --}}
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon purple mb-2">
                                    <i class="iconly-boldPaper"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted font-semibold">Penjualan</h6>

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Hari Ini</span>
                                        <span class="font-extrabold ms-3">{{ $hariIni }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Minggu Ini</span>
                                        <span class="font-extrabold ms-3">{{ $mingguIni }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Bulan Ini</span>
                                        <span class="font-extrabold ms-3">{{ $bulanIni }}</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Absensi --}}
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon green mb-2">
                                    <i class="iconly-boldAdd-User"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted font-semibold">Absensi</h6>
                                    @if ($hasCheckedIn === 'yes')
                                        <h6 class="font-extrabold text-success">Sudah Absen</h6>
                                    @else
                                        <h6 class="font-extrabold text-danger">Belum Absen</h6>
                                        <a href="{{ route('absensi.index') }}" class="btn btn-primary mt-2">Menuju Absensi</a>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
