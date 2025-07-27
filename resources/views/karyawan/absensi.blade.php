@extends('../layouts.admin-master')
@section('admin-master')

<meta name="csrf-token" content="{{ csrf_token() }}">

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
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Absensi Karyawan</h3>
                <p class="text-subtitle text-muted">Catat kehadiran, izin, atau sakit karyawan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Absensi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title-custom"><i class="bi bi-calendar-check-fill me-2"></i>Form Absensi</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('absensi.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="action_type" id="action_type" value="kehadiran">
                            <div class="mb-3">
                                <label for="id_karyawan" class="form-label">Karyawan</label>
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
                            <div class="d-grid">
                                <button type="submit" id="submit-absensi-btn" class="btn btn-primary mt-3">Memeriksa Status...</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title-custom"><i class="bi bi-table me-2"></i>Data Absensi Hari Ini</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Karyawan</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                    <th>Ket.</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absensi as $absen)
                                    <tr>
                                        <td>{{ $absen->user->nama }}</td>
                                        <td>{{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i:s') : '-' }}</td>
                                        <td>{{ $absen->jam_keluar ? \Carbon\Carbon::parse($absen->jam_keluar)->format('H:i:s') : '-' }}</td>
                                        <td>
                                            @if($absen->keterangan === 'Sakit' || $absen->keterangan === 'Izin')
                                                <span class="badge bg-light-secondary">{{ $absen->keterangan }}</span>
                                            @elseif(is_null($absen->jam_keluar))
                                                 <span class="badge bg-light-info">Bekerja</span>
                                            @else
                                                <span class="badge bg-light-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(Str::contains($absen->keterangan, 'Terlambat'))
                                                <span class="badge bg-light-warning">Terlambat</span>
                                            @endif
                                            @if(Str::contains($absen->keterangan, 'Lembur'))
                                                <span class="badge bg-light-primary">Lembur</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary action-btn edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#modal-form-edit"
                                                data-id="{{ $absen->id }}" data-tanggal="{{ $absen->tanggal }}"
                                                data-jam-masuk="{{ $absen->jam_masuk }}" data-jam-keluar="{{ $absen->jam_keluar }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada data absensi untuk hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $absensi->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-form-edit" tabindex="-1" role="dialog" aria-labelledby="modal-form-edit-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-edit-label">Edit Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <label for="adminPassword" class="form-label">Password Admin</label>
                        <input type="password" name="admin_password" class="form-control" id="adminPassword" required placeholder="Masukkan password untuk konfirmasi">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusCheckUrlTemplate = '{{ route("absensi.status", ["user" => "USER_ID", "tanggal" => "TANGGAL"]) }}';
        const userSelect = document.getElementById('id_karyawan');
        const dateInput = document.getElementById('date');
        const submitButton = document.getElementById('submit-absensi-btn');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const otherStatusCheckbox = document.getElementById('other_status_checkbox');
        const otherStatusContainer = document.getElementById('other_status_container');
        const statusSelect = document.getElementById('status');
        const actionTypeInput = document.getElementById('action_type');
        const editButtons = document.querySelectorAll('.edit-btn');

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

        async function updateDynamicButtonState() {
            const userId = userSelect.value;
            const tanggal = dateInput.value;
            if (!userId || !tanggal) return;

            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memeriksa...';
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

        otherStatusCheckbox.addEventListener('change', function() {
            otherStatusContainer.style.display = this.checked ? 'block' : 'none';
            updateButtonUI();
        });

        statusSelect.addEventListener('change', updateButtonUI);
        userSelect.addEventListener('change', updateButtonUI);
        dateInput.addEventListener('change', updateButtonUI);

        updateButtonUI();

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const data = this.dataset;
                document.getElementById('absensiId').value = data.id;
                document.getElementById('editTanggal').value = data.tanggal;
                document.getElementById('editJamMasuk').value = data.jamMasuk ? data.jamMasuk.substring(0, 8) : '';
                document.getElementById('editJamKeluar').value = data.jamKeluar ? data.jamKeluar.substring(0, 8) : '';
                document.getElementById('edit_status').value = "";
            });
        });
    });
</script>

@endsection
