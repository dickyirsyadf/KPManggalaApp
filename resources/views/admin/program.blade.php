@extends('admin.layouts.admin-master')
@section('admin-master')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <h6><b>{{$menu}}</b></h6>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="section" style="margin-top: -40px">
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn icon icon-left btn-light block" data-bs-toggle="modal"
                data-bs-target="#modal-form">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-edit">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Tambah {{$menu}}
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Program</th>
                        <th>Tanggal Dibuat</th>
                        <th>Nominal Terkumpul</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $actualIteration = 0;
                    @endphp
                    @foreach ($programs->sortByDesc('created_at') as $data)
                    @if ($data->keterangan === null)
                    @php
                    $actualIteration++;
                    @endphp
                    <tr>
                        <td>{{$actualIteration}}</td>
                        <td>{{$data->nama_program}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>Rp. {{ str_replace(',','.', number_format($data->nominal_terkumpul, 0)) }}</td>
                        @if($data->status === 'Selesai')
                        <td><span class="badge bg-success">{{$data->status}}</span></td>
                        @else
                        <td><span class="badge bg-warning">{{$data->status}}</span></td>
                        @endif
                        @if($data->nominal_terkumpul)
                        @if($data->status === 'Selesai')
                        <td></td>
                        @else
                        <td>
                            <button id="btn-keterangan" type="button" class="btn-keterangan btn icon btn-danger "
                                data-bs-toggle="modal" data-bs-target="#modal-form-keterangan"
                                data-nama="{{ $data->nama_program }}" data-nominal="{{ $data->nominal_terkumpul }}"
                                data-id="{{ $data->id }}">
                                <i class="bi bi-pencil"></i>
                                Keterangan
                            </button>
                            <button id="btn-keterangan" type="button" class="btn-selesai btn icon btn-success "
                                data-bs-toggle="modal" data-bs-target="#modal-form-selesai"
                                data-nama="{{ $data->nama_program }}" data-nominal="{{ $data->nominal_terkumpul }}"
                                data-id="{{ $data->id }}">
                                <i class="bi bi-calendar2-check"></i>
                                Selesai
                            </button>
                        </td>
                        @endif
                        @else
                        <td>
                            <button id="btn-nominal" type="button" class="btn-nominal btn icon btn-success "
                                data-bs-toggle="modal" data-bs-target="#modal-form-nominal"
                                data-nama="{{ $data->nama_program }}" data-nominal="{{ $data->nominal_terkumpul }}"
                                data-id="{{ $data->id }}">
                                <i class="bi bi-currency-dollar"></i>
                                Tambah Nominal
                            </button>
                        </td>
                        @endif
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade text-left modal-borderless" id="modal-form" tabindex="-1" role="dialog"
    aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah {{$menu}}</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/tambahprogram" method="POST">
                    @csrf
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="nama" name="nama_program" type="text" placeholder="Nama Program"
                                class="form-control" autocomplete="off" required />
                            <div class="form-control-icon">
                                <i class="bi bi-card-checklist"></i>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="submit" id="submit-btn" class="btn btn-primary ms-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Buat {{$menu}}</span>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nominal -->
<div class="modal fade text-left modal-borderless" id="modal-form-nominal" tabindex="-1" role="dialog"
    aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Nominal Terkumpul</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/editprogram" method="POST">
                    @csrf
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" name="nama_program" id="namass" required
                                autocomplete="off" readonly>
                            <div class="form-control-icon">
                                <i class="bi bi-card-checklist"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" name="nominal_terkumpul" placeholder="Nominal"
                                id="nomtranss" required autocomplete="off">
                            <div class="form-control-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="hidden" class="form-control" name="id" placeholder="No Transaksi" id="notranss"
                                required autocomplete="off" readonly>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="submit" id="submit-btn" class="btn btn-primary ms-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Tambah Nominal</span>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Keterangan --}}
<div class="modal fade text-left modal-borderless modal-md" id="modal-form-keterangan" tabindex="-1" role="dialog"
    aria-labelledby="modal-form-keterangan" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h3>Tambah Keterangan</h3>
                    <p class="text-subtitle text-muted">
                        Tambah keterangan dimaksudkan apabila melakukan kesalahan input data, maka
                        tambahkan saja
                        keterangan
                        kesalahannya ( contoh: Salah Input Nominal )
                    </p>
                </div>
            </div>
            <form method="POST" action="/admin/editprogram">
                @csrf
                <div class="modal-body">
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" name="nama_program" placeholder="Nama Muzakki"
                                id="namas" required autocomplete="off" readonly>
                            <div class="form-control-icon">
                                <i class="bi bi-card-checklist"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Nominal" id="nomtrans" required
                                autocomplete="off" readonly>
                            <div class="form-control-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Keterangan" name="keterangan" required
                                autocomplete="off">
                            <div class="form-control-icon">
                                <i class="bi bi-chat-right-text"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="hidden" class="form-control" name="id" placeholder="No Transaksi" id="notrans"
                                required autocomplete="off" readonly>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1">
                        Tambah Keterangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div class="modal fade text-left modal-borderless modal-md" id="modal-form-selesai" tabindex="-1" role="dialog"
    aria-labelledby="modal-form-keterangan" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h3>Konfirmasi Program Selesai</h3>
                </div>
            </div>
            <form method="POST" action="/admin/editprogram">
                @csrf
                <div class="modal-body">
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" name="nama_program" placeholder="Nama Muzakki"
                                id="namasss" required autocomplete="off" readonly>
                            <div class="form-control-icon">
                                <i class="bi bi-card-checklist"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Nominal" id="nomtransss" required
                                autocomplete="off" readonly>
                            <div class="form-control-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="hidden" class="form-control" name="id" placeholder="No Transaksi"
                                id="notransss" required autocomplete="off" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- passing data program dari btn.selesai ke modal  -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnKeteranganList = document.querySelectorAll(".btn-selesai");
        const noTrans = document.getElementById("notransss");
        const nomTrans = document.getElementById("nomtransss");
        const namas = document.getElementById("namasss");

        btnKeteranganList.forEach(function(button) {
            button.addEventListener("click", function() {
                const dataId = button.getAttribute("data-id");
                const dataNama = button.getAttribute("data-nama");
                const dataNominal = button.getAttribute("data-nominal");
                const nominalFormatted = formatRupiah(dataNominal);

                noTrans.value = dataId;
                namas.value = 'Nama Program : ' + dataNama;
                nomTrans.value = 'Nominal Terkumpul : Rp. ' + nominalFormatted
            });
        });
    });
    // Fungsi untuk mengubah nilai menjadi format rupiah
    function formatRupiah(nominal) {
    var reverse = nominal.toString().split('').reverse().join('');
    var thousands = reverse.match(/\d{1,3}/g);
    var formatted = thousands.join('.').split('').reverse().join('');
    return formatted;
    }
</script>

<!-- passing data program dari btn.nominal ke modal  -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnKeteranganList = document.querySelectorAll(".btn-nominal");
        const noTrans = document.getElementById("notranss");
        const nomTrans = document.getElementById("nomtranss");
        const namas = document.getElementById("namass");

        btnKeteranganList.forEach(function(button) {
            button.addEventListener("click", function() {
                const dataId = button.getAttribute("data-id");
                const dataNama = button.getAttribute("data-nama");
                const dataNominal = button.getAttribute("data-nominal");

                noTrans.value = dataId;
                namas.value = dataNama;
            });
        });
    });
</script>

<!-- passing data keterangan dari btn.keterangan ke modal  -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnKeteranganList = document.querySelectorAll(".btn-keterangan");
        const noTrans = document.getElementById("notrans");
        const nomTrans = document.getElementById("nomtrans");
        const namas = document.getElementById("namas");
        const jentTrans = document.getElementById("jentrans");

        btnKeteranganList.forEach(function(button) {
            button.addEventListener("click", function() {
                const dataId = button.getAttribute("data-id");
                const dataNama = button.getAttribute("data-nama");
                const dataNominal = button.getAttribute("data-nominal");
                const formattedNominal = formatRupiah(dataNominal);

                noTrans.value = dataId;
                namas.value = dataNama;
                nomTrans.value = "Rp. " + formattedNominal;
                jentTrans.value = dataZakat;
            });
        });
    });
    // Fungsi untuk mengubah nilai menjadi format rupiah
    function formatRupiah(nominal) {
    var reverse = nominal.toString().split('').reverse().join('');
    var thousands = reverse.match(/\d{1,3}/g);
    var formatted = thousands.join('.').split('').reverse().join('');
    return formatted;
    }
</script>

<!-- Mengubah inputan modal #nominal-zakat-tambah menjadi format mata uang rupiah  -->
<script>
    var inputElem = document.getElementById('nomtranss');

  inputElem.addEventListener('input', function() {
    // Mengambil nilai input
    var inputVal = this.value;

    // Mengubah format menjadi mata uang rupiah
    var formattedVal = parseInt(inputVal.replace(/\D/g, '')).toLocaleString('id-ID');

    // Mengatur nilai input dengan format mata uang rupiah
    this.value = formattedVal;
  });
</script>

<script>
    // If you want to use tooltips in your project, we suggest initializing them globally
      // instead of a "per-page" level.
      document.addEventListener(
        "DOMContentLoaded",
        function () {
          var tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
          );
          var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
          });
        },
        false
      );
</script>
@endsection