@extends('admin.layouts.admin-master')

@section('admin-master')
<div class="page-heading">
    <h3>Laporan Keuangan</h3>
</div>

<div class="page-content">
    <section>
        <!-- Filters Section -->
        <div class="card">
            <div class="card-header">
                <h4>Filter Data</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Financial Report Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Data Keuangan</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                            <th>Debet (Masuk)</th>
                            <th>Kredit (Keluar)</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1; // Initialize row number
                            $saldo = 0; // Initialize saldo
                        @endphp
                        @foreach($laporan as $index => $item)
                            @php
                                if ($index === 0) {
                                    // First row logic
                                    if ($item['debet'] == 0) {
                                        $saldo = 0 - abs($item['kredit']); // Subtract kredit from 0
                                    } else {
                                        $saldo = $item['debet'] - abs($item['kredit']); // Add debet, subtract kredit
                                    }
                                } else {
                                    // Standard logic for subsequent rows
                                    $saldo += $item['debet'] - abs($item['kredit']); // Add debet, subtract kredit
                                }
                            @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item['keterangan'] }}</td>
                                <td>{{ $item['tanggal'] }}</td>
                                {{-- <td>{{ number_format($item['debet'], 0, ',', '.') }} <button class="btn btn-sm btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#modaldetail">Detail</button></td> --}}
                                <td>{{ number_format($item['debet'], 0, ',', '.') }}</td>
                                <td>{{ number_format($item['kredit'], 0, ',', '.') }}</td>
                                <td>{{ number_format($saldo, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach



                    </tbody>
                </table>

            </div>
        </div>

        <!-- Export Report Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Export Laporan</h4>
            </div>
            <div class="card-body text-center">
                <a href="{{ route('laporan.export', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-success">
                    Download Laporan PDF
                </a>
            </div>
        </div>
    </section>
</div>

{{-- Modal Detail --}}
<div class="modal fade text-left modal-borderless" id="modaldetail" tabindex="-1" role="dialog"
    aria-labelledby="modaldetail" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Laporan</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf
                    <!-- User Input Fields -->
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="gaji_perhari" name="gaji_perhari" type="number" placeholder="Gaji Perhari"
                                class="form-control" autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-plus-slash-minus"></i>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
