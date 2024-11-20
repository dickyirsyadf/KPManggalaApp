@extends('admin.layouts.admin-master')
@section('admin-master')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-lg-8 d-none d-md-none d-lg-block ">
                <h6><b>{{$menu}}</b></h6>
            </div>
            <div class="col-md-12 text-center d-lg-none" style="background-color: gold; border-radius: 10px; ">
                <h6 class="" style="padding-top:10px; color: #25396f;">Total Saldo Rp. {{ number_format($totalNominal)
                    }}
                </h6>
            </div>
        </div>
    </div>
</div>

<div class="page-body mt-4">
    <div class="row">
        <section class="section col-lg-9 col-md-12" style="margin-top: -40px">
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
                                <th>Pengeluaran</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $actualIteration = 0;
                            @endphp
                            @php
                            $actualIteration++;
                            @endphp
                            <tr>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <section class="section col-lg-3 d-none d-lg-block d-md-none" style="margin-top: -40px;">
            <div class="card" style="background-color: gold;">
                <div class="card-header text-center" style="background-color: gold;">
                    <h5 style="color: #25396f;">Total Saldo</h5>
                </div>
                <div class="card-content pb-4" style="margin-top: -20px">
                    <div class="align-center">
                        <h5 class="text-center mt-6" style="color: #25396f;">Rp. {{ number_format($totalNominal) }}</h5>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>



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
                <div class="form-group has-icon-left">
                    <div class="position-relative">
                        <input id="keterangan" name="keterangan" type="text" placeholder="Keterangan Pengeluaran"
                            class="form-control" autocomplete="off" required />
                        <div class="form-control-icon">
                            <i class="bi bi-card-text"></i>
                        </div>
                    </div>
                </div>
                <div class="form-group has-icon-left">
                    <div class="position-relative">
                        <input id="pengeluaran" name="pengeluaran" type="text" placeholder="Nominal Pengeluaran"
                            class="form-control" autocomplete="off" required />
                        <div class="form-control-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" id="submit-btn" class="btn btn-primary ms-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Buat {{$menu}}</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    inputPengeluaran = document.getElementById("pengeluaran");

    inputPengeluaran.addEventListener('input', function(){
        var value = this.value;

        var valueFormatted = parseInt(value.replace(/\D/g, '')).toLocaleString('id-ID');

        this.value = valueFormatted;
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnSubmit = document.getElementById("submit-btn");
        const pengeluaran = document.getElementById("pengeluaran");
        const keterangan = document.getElementById("keterangan");

        btnSubmit.addEventListener("click", function(){
            vpengeluaran = pengeluaran.value.replace(/[ .]/g, "");
            vketerangan = keterangan.value;

            var data = {
                pengeluaran: vpengeluaran,
                keterangan: vketerangan
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route('tambahpengeluaran')}}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)})
                .then(response => response.json())
                .then(result => {
                    if(result.status === 'success'){
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: result.message
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: result.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Terjadi kesalahan: " + error
                    });
                })
        });
    });
</script>
@endsection