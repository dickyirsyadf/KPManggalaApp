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
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
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
                        @foreach($laporan as $item)
                            @php
                                // Properly calculate saldo by deducting kredit correctly when it's negative
                                $saldo += $item['debet'] + $item['kredit']; // If kredit is negative, it will decrease saldo
                            @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item['keterangan'] }}</td>
                                <td>{{ $item['tanggal'] }}</td>
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
@endsection
