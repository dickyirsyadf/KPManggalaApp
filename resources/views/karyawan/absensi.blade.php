@extends('admin.layouts.admin-master')

@section('admin-master')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Absensi</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h1>User Absensi</h1>
            </div>
            <div class="card-body">
                <!-- FORM UNTUK JAM MASUK / JAM KELUAR -->
                <form action="{{ route('absensi.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="id_karyawan" class="form-label">User</label>
                        <select name="id_karyawan" id="id_karyawan" class="form-select">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input
                            type="date"
                            name="tanggal"
                            id="date"
                            class="form-control"
                            value="{{ now()->toDateString() }}"
                            required>
                    </div>
                    <!-- Tombol Kehadiran dihapus, diganti tombol proses -->
                    <button type="submit" class="btn btn-primary mt-3">Proses Absensi (Masuk/Pulang)</button>
                </form>

                <h2 class="mt-5">Data Absensi</h2>
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Karyawan</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status Kehadiran</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensi as $absen)
                            <tr>
                                <td>{{ $absen->user->nama }}</td>
                                <td>{{ $absen->tanggal }}</td>
                                <td>{{ $absen->jam_masuk }}</td>
                                <td>{{ $absen->jam_keluar ?? 'Belum Absen Pulang' }}</td>
                                <td>
                                    @if($absen->jam_keluar)
                                        <span class="badge {{ $absen->kehadiran == 'hadir' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $absen->kehadiran == 'hadir' ? 'Hadir' : 'Tidak Hadir' }}
                                        </span>
                                    @else
                                        <span class="badge bg-info">Sedang Bekerja</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Tombol Edit disesuaikan untuk data baru -->
                                    <button class="btn btn-sm btn-primary edit-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-form-edit"
                                        data-id="{{ $absen->id }}"
                                        data-tanggal="{{ $absen->tanggal }}"
                                        data-jam-masuk="{{ $absen->jam_masuk }}"
                                        data-jam-keluar="{{ $absen->jam_keluar }}">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $absensi->links('pagination::bootstrap-4') }}
                </div>

            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>
</div>

<!-- Modal Edit Disesuaikan -->
<div class="modal fade text-left modal-borderless modal-md" id="modal-form-edit" tabindex="-1" role="dialog" aria-labelledby="modal-form-edit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Absensi</h3>
            </div>
            <form method="POST" action="{{ route('absensi.update') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id" id="absensiId" required>
                    <div class="form-group">
                        <label for="editTanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" id="editTanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="editJamMasuk" class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="form-control" id="editJamMasuk" step="1" required>
                    </div>
                    <div class="form-group">
                        <label for="editJamKeluar" class="form-label">Jam Keluar</label>
                        <input type="time" name="jam_keluar" class="form-control" id="editJamKeluar" step="1">
                    </div>
                    <div class="form-group">
                        <label for="adminPassword" class="form-label">Admin Password</label>
                        <input type="password" name="admin_password" class="form-control" id="adminPassword" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script Disesuaikan -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Mengambil semua data dari atribut data-*
                const absensiId = this.dataset.id;
                const tanggal = this.dataset.tanggal;
                const jamMasuk = this.dataset.jamMasuk;
                const jamKeluar = this.dataset.jamKeluar;

                // Mengisi nilai ke dalam form modal
                document.getElementById('absensiId').value = absensiId;
                document.getElementById('editTanggal').value = tanggal;
                document.getElementById('editJamMasuk').value = jamMasuk;
                document.getElementById('editJamKeluar').value = jamKeluar;
            });
        });
    });
</script>
@endsection
