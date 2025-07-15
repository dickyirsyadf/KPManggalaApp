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
                <form action="{{ route('penggajian.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="id_karyawan" class="form-label">Nama</label>
                        <select class="form-select" name="id_karyawan" id="id_karyawan" required>
                            <option value="" disabled selected>Pilih Nama</option>
                            @foreach($daftarGaji as $gaji)
                                <option value="{{ $gaji->id_karyawan }}">{{ $gaji->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" id="jabatan" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                        <input type="number" class="form-control" name="gaji_pokok" id="gaji_pokok" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="ptg_absen" class="form-label">Potongan Absen</label>
                        <input type="number" class="form-control" name="ptg_absen" id="ptg_absen" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="ptg_telat" class="form-label">Potongan Telat</label>
                        <input type="number" class="form-control" name="ptg_telat" id="ptg_telat" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="total_ptg" class="form-label">Total Potongan</label>
                        <input type="number" class="form-control" name="total_ptg" id="total_ptg" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="tjg_jabatan" class="form-label">Tunjangan Jabatan</label>
                        <input type="number" class="form-control" name="tjg_jabatan" id="tjg_jabatan" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="pendapatan_lembur" class="form-label">Pendapatan Lembur</label>
                        <input type="number" class="form-control" name="pendapatan_lembur" id="pendapatan_lembur" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="total_pendapatan" class="form-label">Total Pendapatan</label>
                        <input type="number" class="form-control" name="total_pendapatan" id="total_pendapatan" readonly>
                    </div>
                     <div class="col-md-4">
                        <label for="gaji_bersih" class="form-label">Gaji Bersih</label>
                        <input type="number" class="form-control" name="gaji_bersih" id="gaji_bersih" readonly>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Simpan & Buat Slip Gaji</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Slip Gaji Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Riwayat Slip Gaji</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Total Diterima</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slipGajis as $slipGaji)
                            <tr>
                                <td>{{ $slipGaji->nama }}</td>
                                <td>{{ $slipGaji->tanggal }}</td>
                                <td>Rp {{ number_format($slipGaji->total, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('penggajian.print', $slipGaji->id) }}" target="_blank" class="btn btn-primary btn-sm">
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

<!-- AJAX Script dengan Mode Debug -->
<script>
    $(document).ready(function () {
        console.log('DEBUG: Skrip Penggajian Dimuat.');

        $('#id_karyawan').change(function () {
            var userId = $(this).val();
            console.log('DEBUG: Karyawan dipilih. ID:', userId);

            if (userId) {
                // Menggunakan URL hardcoded seperti pada kode awal Anda
                const finalUrl = "{{ url('/admin/penggajian/getGaji') }}/" + userId;
                console.log('DEBUG: Meminta data dari URL:', finalUrl);

                $.ajax({
                    url: finalUrl,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        console.log('DEBUG: Data berhasil diterima dari server:', data);

                        // Mengisi form dengan data yang didapat dari controller
                        $('#jabatan').val(data.jabatan);
                        $('#gaji_pokok').val(data.gaji_pokok);
                        $('#ptg_absen').val(data.potongan_absen);
                        $('#ptg_telat').val(data.potongan_telat);
                        $('#total_ptg').val(data.total_potongan);
                        $('#tjg_jabatan').val(data.tunjangan_jabatan);
                        $('#pendapatan_lembur').val(data.pendapatan_lembur);
                        $('#total_pendapatan').val(data.total_pendapatan);
                        $('#gaji_bersih').val(data.gaji_bersih);

                        console.log('DEBUG: Form telah diisi.');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // Reset form jika terjadi error
                        $('form')[0].reset();
                        $('#id_karyawan').val('');
                        alert('Data gaji untuk karyawan ini tidak ditemukan!');
                        console.error("DEBUG: AJAX Error:", {
                            status: jqXHR.status,
                            statusText: jqXHR.statusText,
                            responseText: jqXHR.responseText,
                            textStatus: textStatus,
                            errorThrown: errorThrown
                        });
                    }
                });
            }
        });
    });
</script>
@endsection
