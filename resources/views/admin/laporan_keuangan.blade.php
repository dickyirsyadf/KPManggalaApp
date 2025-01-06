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
                        @foreach($laporan as $item)
                            @php
                                $saldo += $item['debet'] - abs($item['kredit']); // Calculate saldo
                            @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item['keterangan'] }}</td>
                                <td>{{ $item['tanggal'] }}</td>
                                <td class="d-flex justify-content-between">
                                    <span class="debet-text">{{ number_format($item['debet'], 0, ',', '.') }}</span>
                                    <button class="btn btn-sm btn-primary ms-2 detail-btn" data-bs-toggle="modal" data-bs-target="#modaldetail" data-margin="{{ $item['margin'] ?? 0 }}" data-total-bayar="{{ $item['total_bayar'] ?? 0 }}">
                                        Detail
                                    </button>
                                </td>
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
<div class="modal fade text-left modal-borderless" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="modaldetail" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="margin">Margin</label>
                        <input id="margin" name="margin" type="text" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="total_bayar">Total Bayar</label>
                        <input id="total_bayar" name="total_bayar" type="text" class="form-control" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Mengubah format mata uang rupiah  -->
<script>
   // Format Rupiah function
   function formatRupiah(amount) {
        if (!amount) return 'Rp. 0'; // Handle empty or null values
        return 'Rp. ' + parseInt(amount, 10).toLocaleString('id-ID', { minimumFractionDigits: 0 });
    }

    $(document).ready(function () {
        // Format "Debet (Masuk)", "Kredit (Keluar)", "Saldo" columns on page load
        $('table tbody tr').each(function () {
            const debetCell = $(this).find('td:nth-child(4)');
            const kreditCell = $(this).find('td:nth-child(5)');
            const saldoCell = $(this).find('td:nth-child(6)');

            // For Debet (Masuk), only format the numeric value and leave the button intact
            const debetText = debetCell.find('.debet-text');
            debetText.text(formatRupiah(debetText.text().replace(/[^\d,-]/g, '')));

            // Format Kredit (Keluar) and Saldo columns
            const kreditText = kreditCell.text().replace(/[^\d,-]/g, '');
            kreditCell.text(formatRupiah(kreditText));

            const saldoText = saldoCell.text().replace(/[^\d,-]/g, '');
            saldoCell.text(formatRupiah(saldoText));
        });
    });
</script>
<script>
    $(document).on('click', '.detail-btn', function () {
        const margin = $(this).data('margin');
        const totalBayar = $(this).data('total-bayar');

        $('#margin').val(formatRupiah(margin));
        $('#total_bayar').val(formatRupiah(totalBayar));

        $('#modaldetail').modal('show');
    });
</script>

@endsection
