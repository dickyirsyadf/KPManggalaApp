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
                        <label for="date" class="form-label">Date</label>
                        <input
                            type="date"
                            name="date"
                            id="date"
                            class="form-control"
                            value="{{ now()->toDateString() }}"
                            required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kehadiran" id="kehadiran" checked>
                        <label class="form-check-label" for="kehadiran">Hadir</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                </form>

                <h2 class="mt-5">Absensi Records</h2>
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Karyawan</th>
                            <th>Tanggal</th>
                            <th>Kehadiran</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensi as $absensis)
                            <tr>
                                <td>{{ $absensis->user->nama }}</td>
                                <td>{{ $absensis->tanggal }}</td>
                                <td>
                                    <span class="badge {{ $absensis->kehadiran == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $absensis->kehadiran == 1 ? 'Hadir' : 'Tidak Hadir' }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Edit button -->
                                    <button class="btn btn-sm btn-primary edit-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-form-edit"
                                        data-id="{{ $absensis->id }}"
                                        data-tanggal="{{ $absensis->tanggal }}"
                                        data-kehadiran="{{ $absensis->kehadiran }}">
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

<!-- Modal Edit -->
<div class="modal fade text-left modal-borderless modal-md" id="modal-form-edit" tabindex="-1" role="dialog" aria-labelledby="modal-form-edit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h3>Edit Absen</h3>
                    <p class="text-subtitle text-muted">
                        Enter admin password to authorize the edit.
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('absensi.update') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="absensiId">Absensi ID</label>
                        <input type="hidden" name="id" id="absensiId" required>
                    </div>
                    <div class="form-group">
                        <label for="editTanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" id="editTanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="editKehadiran" class="form-label">Kehadiran</label>
                        <select name="kehadiran" class="form-select" id="editKehadiran" required>
                            <option value="1">Hadir</option>
                            <option value="0">Tidak Hadir</option>
                        </select>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const absensiId = this.dataset.id;
                const tanggal = this.dataset.tanggal;
                const kehadiran = this.dataset.kehadiran;

                document.getElementById('absensiId').value = absensiId;
                document.getElementById('editTanggal').value = tanggal;
                document.getElementById('editKehadiran').value = kehadiran;
            });
        });
    });
</script>
@endsection
