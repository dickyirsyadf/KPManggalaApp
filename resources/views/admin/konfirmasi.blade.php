@extends('admin.layouts.admin-master')
@section('admin-master')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" id="tagMenu">
                        <h6><b>{{$menu}}</b></h6>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="section" id="tableSection" style="margin-top: -40px">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Muzakki</th>
                        <th>No Transaksi</th>
                        <th>Nominal</th>
                        <th>Kategori Zakat</th>
                        <th>Tanggal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $actualIteration = 0;
                    @endphp
                    @foreach ($transactions->sortByDesc('created_at') as $data)
                    @php
                    $actualIteration++;
                    @endphp
                    <tr>
                        <td>{{$actualIteration}}</td>
                        <td>{{$data->muzakki->nama}}</td>
                        <td>{{$data->no_transaksi}}</td>
                        <td>{{ str_replace(',','.', number_format($data->nominal_transaksi, 0)) }}</td>
                        <td>{{ $data->jenis_transaksi->jenis_transaksi}}</td>
                        <td>{{ $data->tanggal_transaksi }}</td>
                        <td>
                            <button id="btn-konfirmasi" type="button" class="btn btn-danger " data-bs-toggle="modal"
                                data-bs-target="#modal-form-keterangan">
                                Konfirmasi
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>


@endsection