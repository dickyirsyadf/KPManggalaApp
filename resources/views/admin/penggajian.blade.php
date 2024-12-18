@extends('admin.layouts.admin-master')

@section('admin-master')
<div class="page-heading">
    <h3>Penggajian</h3>
</div>

<div class="page-content">
    <section>
        <div class="card">
            <div class="card-header">
                <h4>Daftar Gaji</h4>
                <form action="{{ route('penggajian.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="id_karyawan" class="form-label">Nama</label>
                        <select class="form-select" name="user_id" id="id_karyawan" required>
                            <option value="" disabled selected>Pilih Nama</option>
                            @foreach($daftarGaji as $gaji)
                                <option value="{{ $gaji->id }}">{{ $gaji->nama }}</option> <!-- Menampilkan nama dari user -->
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="bagian" class="form-label">Bagian</label>
                        <input type="text" class="form-control" name="bagian" id="bagian" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="jumlah_hadir" class="form-label">Jumlah Hadir</label>
                        <input type="number" class="form-control" name="jumlah_hadir" id="jumlah_hadir" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="gaji_perhari" class="form-label">Gaji Perhari</label>
                        <input type="number" class="form-control" name="gaji_perhari" id="gaji_perhari" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="bonus" class="form-label">Bonus</label>
                        <input type="number" class="form-control" name="bonus" id="bonus" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="absen" class="form-label">Absen</label>
                        <input type="number" class="form-control" name="absen" id="absen" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="gaji_bersih" class="form-label">Gaji Bersih</label>
                        <input type="number" class="form-control" name="gaji_bersih" id="gaji_bersih" readonly>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Tambahkan AJAX Script -->
<script>
    $(document).ready(function () {
        $('#id_karyawan').change(function () {
            var userId = $(this).val();

            if (userId) {
                // AJAX Request
                $.ajax({
                    url: "{{ url('/penggajian/getGaji') }}/" + userId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        // Isi form dengan data yang didapat
                        $('#bagian').val(data.bagian);
                        $('#jumlah_hadir').val(data.jumlah_hadir);
                        $('#gaji_perhari').val(data.gaji_perhari);
                        $('#bonus').val(data.bonus);
                        $('#absen').val(data.absen);
                        $('#gaji_bersih').val(data.gaji_bersih);
                    },
                    error: function () {
                        alert('Terjadi kesalahan saat mengambil data!');
                    }
                });
            }
        });
    });
</script>
@endsection
