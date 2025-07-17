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
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .form-label {
        font-weight: 500;
    }
    .total-field {
        font-weight: bold;
        font-size: 1.1rem;
    }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Penggajian</h3>
                <p class="text-subtitle text-muted">Hitung dan proses gaji karyawan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a>Keuangan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Penggajian</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title-custom"><i class="bi bi-calculator-fill me-2"></i>Formulir Penggajian</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('penggajian.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="id_karyawan" class="form-label">Nama Karyawan</label>
                        <select class="form-select" name="id_karyawan" id="id_karyawan" required>
                            <option value="" disabled selected>Pilih Nama Karyawan</option>
                            @foreach($daftarKaryawan as $gaji)
                                <option value="{{ $gaji->id_karyawan }}">{{ $gaji->nama ?? 'Nama Tidak Ditemukan' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" readonly>
                    </div>

                    <hr class="my-4">
                    <h6><i class="bi bi-arrow-up-circle-fill text-success me-2"></i>Rincian Pendapatan</h6>
                    <div class="col-md-4">
                        <label for="gaji_pokok_display" class="form-label">Gaji Pokok</label>
                        <input type="text" class="form-control" id="gaji_pokok_display" readonly>
                        <input type="hidden" name="gaji_pokok" id="gaji_pokok">
                    </div>
                    <div class="col-md-4">
                        <label for="tjg_jabatan_display" class="form-label">Tunjangan Jabatan</label>
                        <input type="text" class="form-control" id="tjg_jabatan_display" readonly>
                        <input type="hidden" name="tjg_jabatan" id="tjg_jabatan">
                    </div>
                    <div class="col-md-4">
                        <label for="pendapatan_lembur_display" class="form-label">Pendapatan Lembur</label>
                        <input type="text" class="form-control" id="pendapatan_lembur_display" readonly>
                        <input type="hidden" name="pendapatan_lembur" id="pendapatan_lembur">
                    </div>

                    <hr class="my-4">
                    <h6><i class="bi bi-arrow-down-circle-fill text-danger me-2"></i>Rincian Potongan</h6>
                    <div class="col-md-6">
                        <label for="ptg_absen_display" class="form-label">Potongan Absen</label>
                        <input type="text" class="form-control" id="ptg_absen_display" readonly>
                        <input type="hidden" name="ptg_absen" id="ptg_absen">
                    </div>
                    <div class="col-md-6">
                        <label for="ptg_telat_display" class="form-label">Potongan Telat</label>
                        <input type="text" class="form-control" id="ptg_telat_display" readonly>
                        <input type="hidden" name="ptg_telat" id="ptg_telat">
                    </div>

                    <hr class="my-4">
                    <h6><i class="bi bi-journal-check me-2"></i>Total</h6>
                    <div class="col-md-4">
                        <label for="total_pendapatan_display" class="form-label">Total Pendapatan</label>
                        <input type="text" class="form-control bg-light-success total-field" id="total_pendapatan_display" readonly>
                        <input type="hidden" name="total_pendapatan" id="total_pendapatan">
                    </div>
                    <div class="col-md-4">
                        <label for="total_ptg_display" class="form-label">Total Potongan</label>
                        <input type="text" class="form-control bg-light-danger total-field" id="total_ptg_display" readonly>
                        <input type="hidden" name="total_ptg" id="total_ptg">
                    </div>
                     <div class="col-md-4">
                        <label for="gaji_bersih_display" class="form-label">Gaji Bersih</label>
                        <input type="text" class="form-control bg-light-primary total-field" id="gaji_bersih_display" readonly>
                        <input type="hidden" name="gaji_bersih" id="gaji_bersih">
                    </div>
                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-send-check-fill me-2"></i>Simpan & Buat Slip Gaji</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title-custom"><i class="bi bi-clock-history me-2"></i>Riwayat Slip Gaji</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Periode</th>
                            <th>Total Diterima</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($slipGajis as $slip)
                            <tr>
                                <td>{{ $slip->nama }}</td>
                                <td>{{ \Carbon\Carbon::parse($slip->periode)->format('F Y') }}</td>
                                <td>Rp {{ number_format($slip->gaji_bersih, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('penggajian.print', $slip->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="bi bi-printer-fill"></i> Print
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada riwayat slip gaji.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function formatRupiah(angka) {
        if (angka === null || isNaN(angka)) return 'Rp 0';
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }

    $(document).ready(function () {
        $('#id_karyawan').change(function () {
            var userId = $(this).val();
            if (userId) {
                const finalUrl = "{{ url('/admin/penggajian/getGaji') }}/" + userId;
                $.ajax({
                    url: finalUrl,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#jabatan').val(data.jabatan);
                        $('#gaji_pokok').val(data.gaji_pokok);
                        $('#ptg_absen').val(data.potongan_absen);
                        $('#ptg_telat').val(data.potongan_telat);
                        $('#total_ptg').val(data.total_potongan);
                        $('#tjg_jabatan').val(data.tunjangan_jabatan);
                        $('#pendapatan_lembur').val(data.pendapatan_lembur);
                        $('#total_pendapatan').val(data.total_pendapatan);
                        $('#gaji_bersih').val(data.gaji_bersih);

                        $('#gaji_pokok_display').val(formatRupiah(data.gaji_pokok));
                        $('#ptg_absen_display').val(formatRupiah(data.potongan_absen));
                        $('#ptg_telat_display').val(formatRupiah(data.potongan_telat));
                        $('#total_ptg_display').val(formatRupiah(data.total_potongan));
                        $('#tjg_jabatan_display').val(formatRupiah(data.tunjangan_jabatan));
                        $('#pendapatan_lembur_display').val(formatRupiah(data.pendapatan_lembur));
                        $('#total_pendapatan_display').val(formatRupiah(data.total_pendapatan));
                        $('#gaji_bersih_display').val(formatRupiah(data.gaji_bersih));
                    },
                    error: function (jqXHR) {
                        $('form')[0].reset();
                        $('#id_karyawan').val('');
                        alert('Data gaji untuk karyawan ini tidak ditemukan atau tidak lengkap.');
                        console.error("AJAX Error:", jqXHR.responseText);
                    }
                });
            } else {
                 $('form')[0].reset();
                 $('#id_karyawan').val('');
            }
        });
    });
</script>
@endsection
