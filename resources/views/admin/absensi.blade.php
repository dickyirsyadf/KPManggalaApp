@extends('admin.layouts.admin-master')

@section('admin-master')

<meta name="csrf-token" content="{{ csrf_token() }}">

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
                <form action="{{ route('absensi.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action_type" id="action_type" value="kehadiran">
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
                        <input type="date" name="tanggal" id="date" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="other_status_checkbox">
                        <label class="form-check-label" for="other_status_checkbox">Tandai Status Lain (Sakit/Izin)</label>
                    </div>
                    <div class="mb-3" id="other_status_container" style="display: none;">
                        <label for="status" class="form-label">Pilih Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                        </select>
                    </div>
                    <button type="submit" id="submit-absensi-btn" class="btn btn-primary mt-3">Memeriksa Status...</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="mt-2">Data Absensi</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Karyawan</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status Kehadiran</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensi as $absen)
                            <tr>
                                <td>{{ $absen->user->nama }}</td>
                                <td>{{ $absen->tanggal }}</td>
                                <td>{{ $absen->jam_masuk ?? '-' }}</td>
                                <td>{{ $absen->jam_keluar ?? '-' }}</td>
                                <td>
                                    @if($absen->keterangan === 'Sakit' || $absen->keterangan === 'Izin')
                                        <span class="badge bg-secondary">{{ $absen->keterangan }}</span>
                                    @elseif(is_null($absen->kehadiran))
                                        <span class="badge bg-info">Sedang Bekerja</span>
                                    @else
                                        <span class="badge {{ $absen->kehadiran == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $absen->kehadiran == 1 ? 'Hadir' : 'Tidak Hadir' }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if(Str::contains($absen->keterangan, 'Terlambat'))
                                        <span class="badge bg-warning me-1">Terlambat</span>
                                    @endif
                                    @if(Str::contains($absen->keterangan, 'Lembur'))
                                        <span class="badge bg-info me-1">Lembur</span>
                                    @endif
                                    @if(!Str::contains($absen->keterangan, ['Terlambat', 'Lembur', 'Sakit', 'Izin']))
                                        -
                                    @endif
                                </td>
                                <td>
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
        </div>
    </section>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-form-edit" tabindex="-1" role="dialog">
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
                        <input type="time" name="jam_masuk" class="form-control" id="editJamMasuk" step="1">
                    </div>
                    <div class="form-group">
                        <label for="editJamKeluar" class="form-label">Jam Keluar</label>
                        <input type="time" name="jam_keluar" class="form-control" id="editJamKeluar" step="1">
                    </div>
                    <div class="form-group">
                        <label for="edit_status" class="form-label">Ubah Status Menjadi (Opsional)</label>
                        <select name="edit_status" id="edit_status" class="form-select">
                            <option value="">-- Biarkan Hadir --</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
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

{{-- Skrip dipindahkan ke sini untuk memastikan dieksekusi --}}
<script>
    // URL untuk AJAX check
    const statusCheckUrlTemplate = '{{ route("absensi.status", ["user" => "USER_ID", "tanggal" => "TANGGAL"]) }}';

    // Fungsi ini akan dijalankan setelah seluruh halaman HTML dimuat
    document.addEventListener('DOMContentLoaded', function () {
        // Definisi semua elemen form
        const userSelect = document.getElementById('id_karyawan');
        const dateInput = document.getElementById('date');
        const submitButton = document.getElementById('submit-absensi-btn');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const otherStatusCheckbox = document.getElementById('other_status_checkbox');
        const otherStatusContainer = document.getElementById('other_status_container');
        const statusSelect = document.getElementById('status');
        const actionTypeInput = document.getElementById('action_type');

        const editButtons = document.querySelectorAll('.edit-btn');

        // Fungsi utama untuk menentukan teks dan status tombol
        function updateButtonUI() {
            if (otherStatusCheckbox.checked) {
                const selectedStatus = statusSelect.value;
                submitButton.textContent = 'Simpan Status ' + selectedStatus;
                submitButton.disabled = false;
                actionTypeInput.value = 'status';
            } else {
                actionTypeInput.value = 'kehadiran';
                updateDynamicButtonState();
            }
        }

        // Fungsi untuk mengecek status ke server
        async function updateDynamicButtonState() {
            const userId = userSelect.value;
            const tanggal = dateInput.value;

            if (!userId || !tanggal) return;

            submitButton.textContent = 'Memeriksa...';
            submitButton.disabled = true;

            const finalUrl = statusCheckUrlTemplate.replace('USER_ID', userId).replace('TANGGAL', tanggal);

            try {
                const response = await fetch(finalUrl, {
                    method: 'GET',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                submitButton.textContent = data.text;
                submitButton.disabled = data.disabled;
            } catch (error) {
                console.error('Gagal fetch:', error);
                submitButton.textContent = 'Gagal Memuat';
                submitButton.disabled = true;
            }
        }

        // --- Event Listeners ---
        otherStatusCheckbox.addEventListener('change', function() {
            otherStatusContainer.style.display = this.checked ? 'block' : 'none';
            updateButtonUI();
        });

        statusSelect.addEventListener('change', updateButtonUI);
        userSelect.addEventListener('change', updateButtonUI);
        dateInput.addEventListener('change', updateButtonUI);

        // Panggil fungsi utama saat halaman pertama kali dimuat
        updateButtonUI();

        // Event listener untuk tombol edit
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const absensiId = this.dataset.id;
                const tanggal = this.dataset.tanggal;
                const jamMasuk = this.dataset.jamMasuk;
                const jamKeluar = this.dataset.jamKeluar;

                document.getElementById('absensiId').value = absensiId;
                document.getElementById('editTanggal').value = tanggal;
                document.getElementById('editJamMasuk').value = jamMasuk;
                document.getElementById('editJamKeluar').value = jamKeluar;
                document.getElementById('edit_status').value = "";
            });
        });
    });
</script>

@endsection
