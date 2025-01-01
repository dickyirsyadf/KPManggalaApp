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
            <div class="row">
                <div class="col-5">
                    <button type="button" id="tambahDokumentasi" class="btn icon icon-left btn-light block"
                        data-bs-toggle="modal" data-bs-target="#modal-form">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Cetak {{$menu}}
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jenis Transaksi</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @php
                    $actualIteration = 0;
                    @endphp
                    @foreach ($transactions->sortByDesc('created_at') as $data)
                    @if ($data->keterangan === null)
                    @php
                    $actualIteration++;
                    @endphp
                    <tr>
                        <td>{{$actualIteration}}</td>
                        <td>{{$data->jenis_transaksi->jenis_transaksi}}</td>
                        <td>{{$data->tanggal_transaksi}}</td>
                        <td>Rp. {{ str_replace(',','.', number_format($data->nominal_transaksi, 0)) }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade text-left modal-borderless" id="modal-form" tabindex="-1" role="dialog"
    aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak {{$menu}}</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group t">
                    <div class="position-relative">
                        <select id="selectMonth" class="form-select mb-3">
                            <option selected>Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="position-relative">
                        <select id="selectYear" class="form-select mb-3">
                            <!-- Pilihan tahun di sini, misalnya dari 2020 hingga 2030 -->
                            <option selected>Tahun</option>
                            @for ($year = 2023; $year <= 2030; $year++) <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="submit" onclick="fetchPdf()" id="" class="btn btn-primary ms-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Cetak</span>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Pdf --}}
<div class="modal fade text-left modal-borderless w-100 h-100" id="pdfModal" tabindex="-1" role="dialog"
    aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-full" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close rounded-pill float-end" style="margin-bottom: 5px;"
                    data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
                <iframe id="pdfIframe" src="" style="width: 100%; height: 525px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    var pdfResponse = null; // Variabel global untuk menyimpan respons JSON

    function showPdfModal(pdfResponse) {
        if (pdfResponse && pdfResponse.pdfResponse) {
            var pdfIframe = document.getElementById('pdfIframe');
            pdfIframe.src = 'data:application/pdf;base64,' + pdfResponse.pdfResponse;

            $('#pdfModal').modal('show');
        } else {
            alert('Gagal mengambil PDF');
        }
    }

    function fetchPdf(){
        var bulan = $('#selectMonth').val();
        var tahun = $('#selectYear').val();

       if (bulan === "Bulan" || tahun === "Tahun" ) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Silakan pilih opsi yang valid untuk semua input."
            });
        } else {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var data = {
            bulan: bulan,
            tahun: tahun
            };

            fetch('{{ route('generate-pdf')}}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(response => {
                if (response.status === 'success'){
                    showPdfModal(response);
                    } else {
                        Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Terjadi kesalahan: " + response.error
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
        }
    }
</script>


@endsection
