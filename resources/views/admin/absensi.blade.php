@extends('admin.layouts.admin-master')
@section('admin-master')
<div class="page-heading ">
    <div class="page-title">
        <div class="row">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        Absensi
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 ">
            <h1>User Absensi</h1>
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
                        <label class="form-check-label" for="kehadiran">
                            Present
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                </form>

                <h2 class="mt-5">Absensi Records</h2>
                <table class="table table-bordered">
                    <thead>
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
                                <td>{{ $absensis->kehadiran == 1 ? 'Present' : 'Absent' }}</td>
                                <td>
                                    <!-- Edit button to open modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="{{ $absensis->id }}"
                                        data-date="{{ $absensis->tanggal }}"
                                        data-kehadiran="{{ $absensis->kehadiran }}">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                        Masukan Password di akhir untuk mengubah absen.
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('absensi.update') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <!-- Hidden input field for absensi ID -->
                            <input type="hidden" name="id" id="absensiId">
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <label for="editTanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" id="editTanggal" required>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <label for="editKehadiran" class="form-label">Kehadiran</label>
                            <select name="kehadiran" class="form-select" id="editKehadiran" required>
                                <option value="1">Present</option>
                                <option value="0">Absent</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1">
                        Selesai Edit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle data passing to the modal
    $('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var absensiId = button.data('id');  // Extract data-id from button
    var tanggal = button.data('date'); // Extract data-date from button
    var kehadiran = button.data('kehadiran'); // Extract data-kehadiran from button

    var modal = $(this);
    modal.find('#absensiId').val(absensiId);  // Set the hidden input for absensi ID
    modal.find('#editTanggal').val(tanggal); // Set the date input field
    modal.find('#editKehadiran').val(kehadiran); // Set the kehadiran field (select input)
});

</script>

@endsection
