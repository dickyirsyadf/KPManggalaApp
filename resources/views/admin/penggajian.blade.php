@extends('admin.layouts.admin-master')

@section('admin-master')
<div class="page-heading">
    <h3>Penggajian</h3>
</div>

<div class="page-content">
    <section>
        <!-- Daftar Penerima Gaji -->
        <div class="card">
            <div class="card-header">
                <h4>Formulir Penggajian</h4>
                <p>Pilih karyawan untuk menghitung rincian gaji bulan sebelumnya.</p>
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
                    <h6>Rincian Pendapatan</h6>
                    <div class="col-md-4">
                        <label for="gaji_pokok_display" class="form-label">Gaji Pokok</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="gaji_pokok_display" readonly>
                            <input type="hidden" name="gaji_pokok" id="gaji_pokok">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="tjg_jabatan_display" class="form-label">Tunjangan Jabatan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="tjg_jabatan_display" readonly>
                            <input type="hidden" name="tjg_jabatan" id="tjg_jabatan">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="pendapatan_lembur_display" class="form-label">Pendapatan Lembur</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="pendapatan_lembur_display" readonly>
                            <input type="hidden" name="pendapatan_lembur" id="pendapatan_lembur">
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6>Rincian Potongan</h6>
                    <div class="col-md-6">
                        <label for="ptg_absen_display" class="form-label">Potongan Absen</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="ptg_absen_display" readonly>
                            <input type="hidden" name="ptg_absen" id="ptg_absen">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="ptg_telat_display" class="form-label">Potongan Telat</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="ptg_telat_display" readonly>
                            <input type="hidden" name="ptg_telat" id="ptg_telat">
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6>Total</h6>
                    <div class="col-md-4">
                        <label for="total_pendapatan_display" class="form-label">Total Pendapatan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control bg-light-success" id="total_pendapatan_display" readonly>
                            <input type="hidden" name="total_pendapatan" id="total_pendapatan">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="total_ptg_display" class="form-label">Total Potongan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control bg-light-danger" id="total_ptg_display" readonly>
                            <input type="hidden" name="total_ptg" id="total_ptg">
                        </div>
                    </div>
                     <div class="col-md-4">
                        <label for="gaji_bersih_display" class="form-label">Gaji Bersih</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control bg-light-primary" id="gaji_bersih_display" readonly>
                            <input type="hidden" name="gaji_bersih" id="gaji_bersih">
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">Simpan & Buat Slip Gaji</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Riwayat Slip Gaji -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Riwayat Slip Gaji</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Periode</th>
                            <th>Total Diterima</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slipGajis as $slip)
                            <tr>
                                <td>{{ $slip->nama }}</td>
                                <td>{{ $slip->periode }}</td>
                                <td>Rp {{ number_format($slip->gaji_bersih, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('penggajian.print', $slip->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                        Print
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- AJAX Script -->
<script>
    function formatRupiah(angka) {
        if (angka === null || isNaN(angka)) {
            return '0';
        }
        return parseInt(angka).toLocaleString('id-ID');
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
                        // Mengisi input Jabatan
                        $('#jabatan').val(data.jabatan);

                        // Mengisi input tersembunyi (hidden) dengan nilai mentah
                        $('#gaji_pokok').val(data.gaji_pokok);
                        $('#ptg_absen').val(data.potongan_absen);
                        $('#ptg_telat').val(data.potongan_telat);
                        $('#total_ptg').val(data.total_potongan);
                        $('#tjg_jabatan').val(data.tunjangan_jabatan);
                        $('#pendapatan_lembur').val(data.pendapatan_lembur);
                        $('#total_pendapatan').val(data.total_pendapatan);
                        $('#gaji_bersih').val(data.gaji_bersih);

                        // Mengisi input yang terlihat (text) dengan nilai yang sudah diformat
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
                        // Reset form jika terjadi error
                        $('form')[0].reset();
                        $('#id_karyawan').val('');
                        alert('Data gaji untuk karyawan ini tidak ditemukan atau tidak lengkap.');
                        console.error("AJAX Error:", jqXHR.responseText);
                    }
                });
            } else {
                 // Reset form jika tidak ada user yang dipilih
                 $('form')[0].reset();
                 $('#id_karyawan').val('');
            }
        });
    });
</script>
@endsection
