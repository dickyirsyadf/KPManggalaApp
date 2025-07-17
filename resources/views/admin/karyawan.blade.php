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
                <p class="text-subtitle text-muted">Kelola semua data karyawan.</p>
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
                <h4 class="card-title-custom mb-0"><i class="bi bi-people-fill me-2"></i>Daftar Karyawan</h4>
                {{-- Tombol Tambah Karyawan bisa ditambahkan di sini jika diperlukan --}}
            </div>
            <div class="card-body">
                <table class="table table-striped" id="userTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Email</th>
                            <th>No Handphone</th>
                            <th>Hak Akses</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-form-edit" tabindex="-1" role="dialog" aria-labelledby="modal-form-edit-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-edit-label">Edit {{$menu}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('karyawan.update') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="id_karyawan" name="id_karyawan">
                    <div class="form-group">
                        <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" name="nama_karyawan" id="nama_karyawan" required>
                    </div>
                    <div class="form-group">
                        <label for="femail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="femail" name="femail" required>
                    </div>
                    <div class="form-group">
                        <label for="fno_hp" class="form-label">No Handphone</label>
                        <input type="text" class="form-control" id="fno_hp" name="fno_hp" required>
                    </div>
                    <div class="form-group">
                        <label for="fhakakses" class="form-label">Hak Akses</label>
                        <select class="form-select" id="fhakakses" name="fhakakses" required>
                            <option value="">Pilih Hak Akses</option>
                            @foreach($hakakses as $hak)
                                <option value="{{ $hak->id }}">{{ $hak->hakakses }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fjabatan" class="form-label">Jabatan</label>
                        <select class="form-select" id="fjabatan" name="fjabatan" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach($jabatan as $jbt)
                                <option value="{{ $jbt->id }}">{{ $jbt->nama_jabatan }}</option>
                            @endforeach
                        </select>
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
                Apakah Anda yakin ingin menghapus karyawan <strong id="delete-karyawan-name"></strong>? Tindakan ini tidak dapat dibatalkan.
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
    $(document).ready(function () {
        $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('karyawan.data') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'nama', name: 'nama' },
                { data: 'jabatan.nama_jabatan', name: 'jabatan.nama_jabatan', defaultContent: '-' },
                { data: 'email', name: 'email' },
                { data: 'no_hp', name: 'no_hp' },
                { data: 'hakakses.hakakses', name: 'hakakses.hakakses', defaultContent: '-' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-sm btn-primary action-btn edit-btn"
                                    data-bs-toggle="modal" data-bs-target="#modal-form-edit"
                                    data-id="${row.id}" data-nama="${row.nama}" data-email="${row.email}"
                                    data-no_hp="${row.no_hp}" data-id_hakakses="${row.id_hakakses}" data-id_jabatan="${row.id_jabatan}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn btn-sm btn-danger action-btn delete-btn"
                                    data-id="${row.id}" data-nama="${row.nama}" data-bs-toggle="modal" data-bs-target="#modal-delete">
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
        $('#id_karyawan').val(data.id);
        $('#nama_karyawan').val(data.nama);
        $('#femail').val(data.email);
        $('#fno_hp').val(data.no_hp);
        $('#fhakakses').val(data.id_hakakses);
        $('#fjabatan').val(data.id_jabatan);
        $('#modal-form-edit').modal('show');
    });

    $('#userTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        var name = $(this).data('nama');
        $('#delete-karyawan-name').text(name);
        $('#deleteForm').attr('action', '/admin/karyawan/' + id);
        $('#modal-delete').modal('show');
    });
</script>
@endsection
