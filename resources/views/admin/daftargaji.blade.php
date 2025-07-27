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
                <h3>{{$menu}}</h3>
                <p class="text-subtitle text-muted">Kelola daftar gaji pokok karyawan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a>Keuangan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$menu}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title-custom mb-0"><i class="bi bi-wallet-fill me-2"></i>Daftar Gaji Karyawan</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-form">
                    <i class="bi bi-plus-circle-fill me-2"></i>Tambah Gaji
                </button>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="gajiTable">
                    <thead>
                        <tr>
                            <th>ID Karyawan</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Gaji Pokok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
            <form action="{{ route('daftargaji.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Karyawan</label>
                        <select id="nama" name="id_karyawan" class="form-select" onchange="updateFields()" required>
                            <option value="" disabled selected>Pilih Nama Karyawan</option>
                            @foreach ($karyawans as $karyawan)
                                <option value="{{ $karyawan->id }}" data-nama="{{ $karyawan->nama }}" data-jabatan="{{ $karyawan->jabatan->nama_jabatan }}">
                                    {{ $karyawan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" id="hidden-nama" name="nama">
                    <div class="form-group">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input id="jabatan" name="jabatan" type="text" placeholder="Jabatan akan terisi otomatis" class="form-control" readonly />
                    </div>
                    <div class="form-group">
                        <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                        <input id="gaji_pokok" name="gaji_pokok" type="number" placeholder="Masukkan gaji pokok" class="form-control" autocomplete="off" required />
                    </div>
                    <input type="hidden" name="jml_hr_kerja" value="0">
                    <input type="hidden" name="jml_hadir" value="0">
                    <input type="hidden" name="jml_absen" value="0">
                    <input type="hidden" name="jml_izin" value="0">
                    <input type="hidden" name="jml_sakit" value="0">
                    <input type="hidden" name="jml_terlambat" value="0">
                    <input type="hidden" name="jml_lembur" value="0">
                    <input type="hidden" name="gaji_bersih" value="0">
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
                <h5 class="modal-title" id="modal-form-edit-label">Edit Gaji</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('daftargaji.update') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="id_karyawan_edit" name="id_karyawan">
                    <div class="form-group">
                        <label for="nama_karyawan_edit" class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" id="nama_karyawan_edit" name="nama" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jabatan_edit" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan_edit" name="jabatan" readonly>
                    </div>
                    <div class="form-group">
                        <label for="gaji_pokok_edit" class="form-label">Gaji Pokok</label>
                        <input type="number" class="form-control" id="gaji_pokok_edit" name="gaji_pokok" required>
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
                Apakah Anda yakin ingin menghapus data gaji untuk <strong id="delete-gaji-name"></strong>?
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
        $('#gajiTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('daftargaji.data') }}",
            columns: [
                { data: 'id_karyawan', name: 'id_karyawan' },
                { data: 'nama', name: 'nama' },
                { data: 'jabatan', name: 'jabatan' },
                { data: 'gaji_pokok', name: 'gaji_pokok', render: formatRupiah },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-sm btn-primary action-btn edit-btn"
                                    data-bs-toggle="modal" data-bs-target="#modal-form-edit"
                                    data-id_karyawan="${row.id_karyawan}" data-nama="${row.nama}"
                                    data-jabatan="${row.jabatan}" data-gaji_pokok="${row.gaji_pokok}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn btn-sm btn-danger action-btn delete-btn"
                                    data-id="${row.id_karyawan}" data-nama="${row.nama}" data-bs-toggle="modal" data-bs-target="#modal-delete">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        `;
                    },
                },
            ],
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
        });
    });

    function updateFields() {
        const select = document.getElementById("nama");
        const selectedOption = select.options[select.selectedIndex];
        document.getElementById("hidden-nama").value = selectedOption.getAttribute("data-nama");
        document.getElementById("jabatan").value = selectedOption.getAttribute("data-jabatan");
    }

    $(document).on('click', '.edit-btn', function () {
        const data = $(this).data();
        $('#id_karyawan_edit').val(data.id_karyawan);
        $('#nama_karyawan_edit').val(data.nama);
        $('#jabatan_edit').val(data.jabatan);
        $('#gaji_pokok_edit').val(data.gaji_pokok);
        $('#modal-form-edit').modal('show');
    });

    $('#gajiTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        var name = $(this).data('nama');
        $('#delete-gaji-name').text(name);
        $('#deleteForm').attr('action', '/admin/daftargaji/' + id);
        $('#modal-delete').modal('show');
    });
</script>
@endsection
