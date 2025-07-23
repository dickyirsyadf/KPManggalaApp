@extends('admin.layouts.admin-master')
@section('admin-master')

{{-- Gaya Kustom --}}
<style>
    .card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: none; }
    .card-header { background-color: transparent; border-bottom: 1px solid #eee; }
    .card-title-custom { font-size: 1.25rem; font-weight: 600; color: #333; }
    .table thead th { background-color: #f8f9fa; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .table tbody tr:hover { background-color: #2a2e45; }
    .btn { border-radius: 8px; font-weight: 500; }
    .btn-success { background: linear-gradient(45deg, #198754, #28a745); border: none; }
    .btn-danger { background: linear-gradient(45deg, #dc3545, #f5365c); border: none; }
    .badge-status { padding: 0.5em 0.75em; font-size: 0.8rem; }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12">
                <h3>{{ $menu }}</h3>
                <p class="text-subtitle text-muted">Setujui atau tolak permintaan preorder dari staff.</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title-custom"><i class="bi bi-check2-circle me-2"></i>Daftar Pengajuan Preorder</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="preorderAdminTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Diajukan Oleh</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($semuaPreorder as $po)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($po->tanggal)->format('d M Y') }}</td>
                                <td>{{ $po->nama_karyawan }}</td>
                                <td>{{ $po->nama_barang }}</td>
                                <td>{{ $po->jumlah }}</td>
                                <td>Rp {{ number_format($po->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($po->status == 'Pending')
                                        <span class="badge bg-light-warning badge-status">Menunggu Persetujuan</span>
                                    @elseif($po->status == 'Disetujui')
                                        <span class="badge bg-light-success badge-status">Disetujui oleh {{ $po->status_dirubah_oleh }}</span>
                                    @elseif($po->status == 'Ditolak')
                                        <span class="badge bg-light-danger badge-status">Ditolak oleh {{ $po->status_dirubah_oleh }}</span>
                                    @elseif($po->status == 'Selesai')
                                        <span class="badge bg-light-secondary badge-status">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    @if($po->status == 'Pending')
                                        <form action="{{ route('preorder.updateStatus', $po->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Disetujui">
                                            <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                        </form>
                                        <form action="{{ route('preorder.updateStatus', $po->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                        </form>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada pengajuan preorder.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#preorderAdminTable').DataTable({
            "order": [[ 0, "desc" ]],
            "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
        });
    });
</script>
@endsection
