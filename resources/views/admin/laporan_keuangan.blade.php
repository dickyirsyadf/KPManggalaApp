@extends('admin.layouts.admin-master')

@section('admin-master')

{{-- CSS Kustom untuk Tampilan Baru --}}
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: none;
        transition: all 0.3s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }
    .card-header {
        background-color: transparent;
        border-bottom: 1px solid #eee;
        padding: 1.5rem;
    }
    .card-title-custom {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
    }
    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
    }
    .table tbody tr:hover {
        background-color: #2a2e45;
    }
    .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }
    .btn-success {
        background: linear-gradient(45deg, #198754, #28a745);
        border: none;
    }
    .btn-primary {
        background: linear-gradient(45deg, #435ebe, #5e72e4);
        border: none;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .modal-header {
        background: linear-gradient(45deg, #435ebe, #5e72e4);
        color: white;
    }
    .modal-title {
        color: white;
    }
    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    .text-debet {
        color: #28a745; /* Warna hijau untuk pemasukan */
        font-weight: 500;
    }
    .text-kredit {
        color: #dc3545; /* Warna merah untuk pengeluaran */
        font-weight: 500;
    }
    .summary-card {
        text-align: center;
        padding: 1.5rem;
    }
    .summary-card .icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    .summary-card h6 {
        font-size: 1rem;
        color: #6c757d;
    }
    .summary-card .h4 {
        font-size: 1.75rem;
        font-weight: 700;
    }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Laporan Keuangan</h3>
                <p class="text-subtitle text-muted">Analisis pemasukan dan pengeluaran.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Laporan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        {{-- Filter Card --}}
        <div class="card">
            <div class="card-header">
                <h4 class="card-title-custom"><i class="bi bi-funnel-fill me-2"></i>Filter Laporan</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date', $startDate) }}">
                    </div>
                    <div class="col-md-5">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date', $endDate) }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row">
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="icon text-success"><i class="bi bi-arrow-up-circle-fill"></i></div>
                    <h6>Total Pemasukan</h6>
                    <h4 class="text-success">Rp {{ number_format($totalDebet, 0, ',', '.') }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="icon text-danger"><i class="bi bi-arrow-down-circle-fill"></i></div>
                    <h6>Total Pengeluaran</h6>
                    <h4 class="text-danger">Rp {{ number_format($totalKredit, 0, ',', '.') }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="icon text-primary"><i class="bi bi-wallet2"></i></div>
                    <h6>Keuntungan Penjualan (Margin)</h6>
                    <h4 class="text-primary">Rp {{ number_format($totalMargin, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        {{-- Data Table Card --}}
        <div class="card mt-2">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title-custom mb-0"><i class="bi bi-journal-text me-2"></i>Rincian Transaksi</h4>
                <a href="{{ route('laporan.export', ['start_date' => request('start_date', $startDate), 'end_date' => request('end_date', $endDate)]) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download PDF
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="laporanTable">
                        {{-- THEAD: Pastikan ada 5 kolom --}}
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                                <th class="text-end">Debet (Masuk)</th>
                                <th class="text-end">Kredit (Keluar)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporan as $index => $item)
                                {{-- Tambahkan class 'data-row' sebagai penanda --}}
                                <tr class="data-row">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $item['keterangan'] }}
                                        @if($item['debet'] > 0 && isset($item['margin']))
                                            <button class="btn btn-sm btn-outline-primary py-0 px-1 ms-2 detail-btn"
                                                    data-bs-toggle="modal" data-bs-target="#modaldetail"
                                                    data-margin="{{ $item['margin'] }}"
                                                    data-total-bayar="{{ $item['total_bayar'] }}">
                                                <i class="bi bi-info-circle"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</td>
                                    <td class="text-end text-debet">{{ number_format($item['debet'], 0, ',', '.') }}</td>
                                    <td class="text-end text-kredit">{{ number_format($item['kredit'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada data untuk rentang tanggal yang dipilih.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="modaldetail-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaldetail-label">Detail Transaksi Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="total_bayar" class="form-label">Total Pemasukan (Omzet)</label>
                    <input id="total_bayar" type="text" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="margin" class="form-label">Keuntungan (Margin)</label>
                    <input id="margin" type="text" class="form-control" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
   function formatRupiah(amount) {
        if (amount === null || isNaN(amount)) return 'Rp 0';
        return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
    }

    $(document).on('click', '.detail-btn', function () {
        const margin = $(this).data('margin');
        const totalBayar = $(this).data('total-bayar');

        $('#margin').val(formatRupiah(margin));
        $('#total_bayar').val(formatRupiah(totalBayar));

        $('#modaldetail').modal('show');
    });

    $(document).ready(function() {
        // Cek apakah ada baris data sebelum menginisialisasi DataTables
        if ($('#laporanTable tbody tr.data-row').length > 0) {
            $('#laporanTable').DataTable({
                "pageLength": 10,
                "order": [[ 0, "desc" ]], // Mengurutkan berdasarkan tanggal terbaru
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });
        }
    });
</script>

@endsection
