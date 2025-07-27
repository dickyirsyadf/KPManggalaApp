@extends('../layouts.admin-master')
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
    .btn-primary {
        background: linear-gradient(45deg, #435ebe, #5e72e4);
        border: none;
    }
    .btn-danger {
        background: linear-gradient(45deg, #dc3545, #f5365c);
        border: none;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .action-btn {
        padding: 5px 10px;
        font-size: 0.8rem;
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
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data {{$menu}}</h3>
                <p class="text-subtitle text-muted">Kelola semua data barang yang tersedia.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a>Master Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$menu}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title-custom mb-0"><i class="bi bi-box-seam-fill me-2"></i>Daftar Barang</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-form">
                    <i class="bi bi-plus-circle-fill me-2"></i>Tambah Barang
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="min-height:250px;">
                    <table class="table table-striped" id="barangTable" style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Barang</th>
                                <th>Deskripsi</th>
                                <th>Stok</th>
                                <th>Harga Jual</th>
                                <th>Harga Modal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan diisi oleh DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-label">Tambah {{$menu}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/tambahbarang" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Barang</label>
                        <input id="nama" name="nama" type="text" placeholder="Masukkan nama barang" class="form-control" autocomplete="off" required />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <input id="deskripsi" name="deskripsi" type="text" placeholder="Masukkan deskripsi" class="form-control" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label for="stock" class="form-label">Stok</label>
                        <input id="stock" name="stock" type="number" placeholder="Masukkan jumlah stok" class="form-control" autocomplete="off" required />
                    </div>
                    <div class="form-group">
                        <label for="harga_jual" class="form-label">Harga Jual</label>
                        <input id="harga_jual" name="harga_jual" type="number" placeholder="Masukkan harga jual" class="form-control" autocomplete="off" required />
                    </div>
                    <div class="form-group">
                        <label for="harga_modal" class="form-label">Harga Modal</label>
                        <input id="harga_modal" name="harga_modal" type="number" placeholder="Masukkan harga modal" class="form-control" autocomplete="off" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-1">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-form-edit" tabindex="-1" role="dialog" aria-labelledby="modal-form-edit-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-edit-label">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('barang.update') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="id_barang" name="id_barang">
                    <div class="form-group">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="nama_barang" id="nama_barang" required>
                    </div>
                    <div class="form-group">
                        <label for="fdeskripsi" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" id="fdeskripsi" name="fdeskripsi" required>
                    </div>
                    <div class="form-group">
                        <label for="fstock" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="fstock" name="fstock" required>
                    </div>
                    <div class="form-group">
                        <label for="fhargaj" class="form-label">Harga Jual</label>
                        <input type="number" class="form-control" id="fhargaj" name="fhargaj" required>
                    </div>
                    <div class="form-group">
                        <label for="fhargam" class="form-label">Harga Modal</label>
                        <input type="number" class="form-control" id="fhargam" name="fhargam" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-1">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-delete-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="modal-delete-label">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function formatRupiah(amount) {
        if (!amount) return 'Rp 0';
        return 'Rp ' + parseInt(amount, 10).toLocaleString('id-ID');
    }

    $(document).ready(function () {
        $('#barangTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('barang.data') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'nama', name: 'nama' },
                { data: 'deskripsi', name: 'deskripsi' },
                { data: 'stock', name: 'stock' },
                { data: 'harga_jual', name: 'harga_jual', render: formatRupiah },
                { data: 'harga_modal', name: 'harga_modal', render: formatRupiah },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-sm btn-primary action-btn edit-btn"
                                    data-bs-toggle="modal" data-bs-target="#modal-form-edit"
                                    data-id="${row.id}" data-nama="${row.nama}" data-deskripsi="${row.deskripsi}"
                                    data-stock="${row.stock}" data-hargaj="${row.harga_jual}" data-hargam="${row.harga_modal}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn btn-sm btn-danger action-btn delete-btn"
                                    data-id="${row.id}" data-bs-toggle="modal" data-bs-target="#modal-delete">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        `;
                    },
                },
            ],
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        const data = $(this).data();
        $('#id_barang').val(data.id);
        $('#nama_barang').val(data.nama);
        $('#fdeskripsi').val(data.deskripsi);
        $('#fhargaj').val(data.hargaj);
        $('#fhargam').val(data.hargam);
        $('#fstock').val(data.stock);
        $('#modal-form-edit').modal('show');
    });

    $('#barangTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        $('#deleteForm').attr('action', '/admin/barang/' + id);
        $('#modal-delete').modal('show');
    });
</script>
@endsection
