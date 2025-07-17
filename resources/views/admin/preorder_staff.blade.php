@extends('admin.layouts.admin-master')
@section('admin-master')

{{-- Gaya Kustom --}}
<style>
    .card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: none; }
    .card-header { background-color: transparent; border-bottom: 1px solid #eee; }
    .card-title-custom { font-size: 1.25rem; font-weight: 600; color: #333; }
    .table thead th { background-color: #f8f9fa; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .table tbody tr:hover { background-color: #f1f1f1; }
    .btn { border-radius: 8px; font-weight: 500; }
    .btn-primary { background: linear-gradient(45deg, #435ebe, #5e72e4); border: none; }
    .btn-success { background: linear-gradient(45deg, #198754, #28a745); border: none; }
    .badge-status { padding: 0.5em 0.75em; font-size: 0.8rem; }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12">
                <h3>{{ $menu }}</h3>
                <p class="text-subtitle text-muted">Pilih barang yang stoknya menipis untuk diajukan preorder.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Pengajuan Preorder -->
        <div class="col-md-12">
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title-custom"><i class="bi bi-box-seam me-2"></i>Barang dengan Stok Kurang dari 5</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('preorder.store') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Pilih</th>
                                            <th>Nama Barang</th>
                                            <th>Stok Saat Ini</th>
                                            <th>Harga Modal</th>
                                            <th style="width: 15%;">Jumlah Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($barangHampirHabis as $barang)
                                            <tr>
                                                <td>
                                                    <input class="form-check-input checkbox-item" type="checkbox" name="items[{{ $barang->id }}][selected]" value="1">
                                                </td>
                                                <td>{{ $barang->nama }}</td>
                                                <td><span class="badge bg-light-danger">{{ $barang->stock }}</span></td>
                                                <td>Rp {{ number_format($barang->harga_modal, 0, ',', '.') }}</td>
                                                <td>
                                                    <input type="number" class="form-control quantity-input" name="items[{{ $barang->id }}][jumlah]" placeholder="Qty" min="1">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">Tidak ada barang yang perlu di-restock saat ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($barangHampirHabis->isNotEmpty())
                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill me-2"></i>Ajukan Preorder</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <!-- Riwayat Pengajuan -->
        <div class="col-md-12">
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title-custom"><i class="bi bi-clock-history me-2"></i>Riwayat Pengajuan Anda</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($riwayatPreorder as $po)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($po->tanggal)->format('d M Y') }}</td>
                                        <td>{{ $po->nama_barang }}</td>
                                        <td>{{ $po->jumlah }}</td>
                                        <td>Rp {{ number_format($po->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            @if($po->status == 'Pending')
                                                <span class="badge bg-light-warning badge-status">Menunggu Persetujuan</span>
                                            @elseif($po->status == 'Disetujui')
                                                <span class="badge bg-light-success badge-status">Disetujui</span>
                                            @elseif($po->status == 'Ditolak')
                                                <span class="badge bg-light-danger badge-status">Ditolak</span>
                                            @elseif($po->status == 'Selesai')
                                                <span class="badge bg-light-secondary badge-status">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($po->status == 'Disetujui')
                                                <form action="{{ route('preorder.selesaikan', $po->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Barang Sampai</button>
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Anda belum pernah mengajukan preorder.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dapatkan semua input kuantitas
    const quantityInputs = document.querySelectorAll('.quantity-input');

    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Temukan checkbox yang berada di baris yang sama
            const row = this.closest('tr');
            const checkbox = row.querySelector('.checkbox-item');

            // Jika input kuantitas diisi (lebih dari 0), centang checkboxnya
            if (this.value > 0) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
        });
    });
});
</script>
@endsection
